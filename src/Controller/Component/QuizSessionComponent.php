<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;

use \Cake\Core\Configure;
use \Cake\Log\Log;
use \Cake\ORM\TableRegistry;

use \App\Model\QuizSession;

use Cake\Network\Exception\ForbiddenException;

/**
 * Quiz component
 */
class QuizSessionComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'quiz_id' => null
    ];


    public $components = ['QuizScore'];


    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->Controller   = $this->_registry->getController();
        $this->QuizSessions = \Cake\ORM\TableRegistry::get('QuizSessions');
    }

    public function setController($controller)
    {
        $this->Controller = $controller;
    }

    public function startup(\Cake\Event\EventInterface $event)
    {
        $this->setController($event->subject());
    }

    /**
     * Verifica se esiste o no una sessione già precedentemente salvata per tale livello
     *
     * @param  int $quiz_id
     * @param  integer $level
     * @return mixed
     *         false: not exists
     *         int: id of quiz_session_id
     */
    public function exists($quiz_id)
    {
        $q = $this->QuizSessions->find();
        $q->where(['quiz_id' => $quiz_id]);
        $q->where(['user_id' => $this->Controller->getRequest()->getSession()->read('Auth.User.id')]);
        $q->where(['is_deleted' => false]);

        $q->contain([
            'Levels' => function($q) {
                // NOTE:
                // C'è un solo QuizSessionLevel per livello (i tentativi falliti non vengono salvati sul database)
                // Percui questo group potrebbe essere anche rimosso.
                // Veniva utilizzato prima perchè venivano salvati anche i quiz falliti sul database
                //$q->group(['level']);
                //$q->orderAsc('level');
                return $q;
            }
        ]);

        return $q->first();
    }

    /**
     * Crea nuova sessione di quiz
     * @return \App\Model\QuizSession
     */
    public function create($quiz_id)
    {
        $attrs = [
            'user_id' => $this->Controller->getRequest()->getSession()->read('Auth.User.id'),
            'quiz_id' => $quiz_id,
        ];

        $QuizSession = $this->QuizSessions->newEntity($attrs, ['contain' => 'QuizSessionLevel']);

        $this->QuizSessions->saveOrFail($QuizSession);
        return $QuizSession;
    }


    /**
     * Crea sessione di gioco
     *
     * @param  array $session
     * @return array dettagli sessione (encrypt tramite Hashids)
     */
    public function store($session)
    {
        // TODO: controllo "sessione quiz completata"
        // PS: Viene effettuata nel controller

        $quizSessionPath = sprintf('Quiz.%d', $session['quiz']->id);
        $alreadyStore    = $this->Controller->getRequest()->getSession()->read($quizSessionPath . '._alreadySaved', true);
        if ($alreadyStore) {
            throw new ForbiddenException();
        }

        // Aggiunge livello su $session
        //$session['level'] = $this->Controller->getRequest()->getParam('level');

        $score = $this->calcScore($session);
        $session['points'] = $score['score'];
        $session['score']  = $score['score_final'];
        $session['User']   = $this->Controller->getRequest()->getSession()->read('Auth.User');

        $quizSession = $this->exists($session['quiz']->id);
        $self        = $this;
        $tokens = [
            'level'   => $session['level'],
            'score'   => $session['score']
        ];

        Log::info(__('Quiz::end - Livello giocato={level}   Punteggio ottenuto={score}', $tokens));


        $insertionData = $this->QuizSessions->connection()->transactional(function($connection) use ($self, $quizSession, $session) {
            $connection->begin();

            if (!$quizSession) {
                $quizSession = $self->create($session['quiz']->id);
                $quizSession->levels = null;

                Log::info(__('Quiz::end - QuizSession#{session} creato', ['session' => $quizSession->id]));
            } else {
                Log::info(__('Quiz::end - QuizSession#{session} trovata ... aggiornamento', ['session' => $quizSession->id]));
            }

            // Determino se livello giocato è già salvato
            $QuizSessionLevel = null;
            foreach ((array) $quizSession->levels as $i => $sessionLevel) {
                if ($sessionLevel->level == $session['level']) {
                    $QuizSessionLevel = $sessionLevel;

                    Log::info(__('Quiz::end - QuizSessionLevel#{id} già giocato (livello: {level} punteggio: {score})', [
                        'id'    => $sessionLevel->id,
                        'level' => $sessionLevel->level,
                        'score' => $sessionLevel->score
                    ]));
                    break;
                }
            }

            // Serve per raggruppare le domande/risposte in quiz_session_level_replies in base al tentativo
            $tries_id = time();

            // Livello già giocato (determinare se il punteggio ottenuto è maggiore di quello salvato)
            if ($QuizSessionLevel !== NULL) {
                $saved = true;

                if ((int) $session['score'] > (int) $QuizSessionLevel->score) {
                    // Aggiornare QuizSessionLevel con i nuovi dati
                    // points = punti domanda quiz
                    // score  = punti domanda + punti bonus da suggerimenti non utilizzati
                    $QuizSessionLevel->points        = $session['points'];
                    $QuizSessionLevel->score         = $session['score'];
                    $QuizSessionLevel->best_try_seed = $tries_id;

                    $QuizSessionLevel->help_25perc_used = $session['suggestions']['perc25'] === false;
                    $QuizSessionLevel->help_50perc_used = $session['suggestions']['perc50'] === false;
                    $QuizSessionLevel->help_75perc_used = $session['suggestions']['skip']   === false;

                    $saved = $self->QuizSessions->Levels->saveOrFail($QuizSessionLevel);
                }
            } else {
                $QuizSessionLevel = $self->QuizSessions->Levels->newEntity([
                    'quiz_session_id'         => $quizSession->id,
                    'level'                   => $session['level'],
                    'points'                  => $session['points'],
                    'score'                   => $session['score'],
                    'best_try_seed'           => $tries_id
                ]);

                $saved = $self->QuizSessions->Levels->saveOrFail($QuizSessionLevel);
            }

            $answers = [];
            foreach ($session['replies'] as $reply) {
                $answers[] = $self->QuizSessions->Levels->Replies->newEntity([
                    'quiz_session_level_id' => $QuizSessionLevel->id,
                    'question_id'           => $reply['Question']->id,
                    'answer_id'             => $reply['answer_id'],
                    'reply_after_secs'      => (int) $reply['reply_after_secs'],

                    // Visto che:
                    // C'è una sola sessione di livello per quiz (QuizSessionLevel) ma tuttavia possono essere stati fatti più tentativi)
                    // In quiz_level_questions troverò più domande, ma non ho nessun campo per raggruppare le domande in base alla sessione.
                    // Tramite questo campo:
                    // √ posso determinare quale sono state le risposte dell'utente per quel tentativo specifico
                    // √ posso determinare quale sono state tutte le domande visualizzate dall'utente per quel livello specifico
                    'try_seed'  => $tries_id,
                ]);
            }

            if (!$self->QuizSessions->Levels->Replies->saveMany($answers)) {
                $connection->rollback();
                return false;
            }

            if ($saved) {
                $connection->commit();
                return [
                    'QuizSession'      => $quizSession,
                    'QuizSessionLevel' => $QuizSessionLevel
                ];
            }

            $connection->rollback();
            return false;
        });

        // Assegna PIX dopo giocata
        if ($session['_adv']) {
            $UserCredits = TableRegistry::get('UserCredits');

            $session['pix'] = ['player' => 0, 'author' => 0];
            $_gamePassed    = $session['points'] >= Configure::readOrFail('app.quiz.minScoreRequired');

            // TODO: implementare
            $session['pix']['player'] = $this->QuizScore->getPlayerPix();
            $session['pix']['author'] = $this->QuizScore->getAuthorPix();

            // NOTE: verifica che le domande risposte siano maggiori di quelle minime richieste
            // all'assegnamento dei PIXs
            if ($_gamePassed) {
                $this->Controller->eventManager()->dispatch(
                    new Event('Controller.User.Game.afterQuizCompleted', (object) $session['User'], [
                        //'entities' => $insertionData,
                        'session' => $session
                    ])
                );

                $this->Controller->eventManager()->dispatch(
                    new Event('Controller.User.Game.afterUserPlayedMyQuiz', (object) $session['quiz']['author'], [
                        'session' => $session
                    ])
                );
            } else {
                $this->Controller->eventManager()->dispatch(
                    new Event('Controller.User.Game.afterUserPlayedMyQuiz', (object) $session['quiz']['author'], [
                        'session' => $session
                    ])
                );
            }

        }

        // Sessione aggiunta:
        // _saved = se questa sessione di quiz è già stata salvata e sono già stati assegnati i PIX
        $quizSessionPath = sprintf('Quiz.%d', $session['quiz']->id);
        $this->Controller->getRequest()->getSession()->write($quizSessionPath . '._alreadySaved', true);

        return [
            'qsessid' => $insertionData['QuizSession']->id,
            'level'   => $insertionData['QuizSessionLevel']->id
        ];
    }


    /**
     * Calcola punteggio in base alla sessione di gioco
     *
     * @param  array $session sessione di gioco
     * @return array
     */
    public function calcScore(&$session)
    {
        $score       = $this->Controller->QuizScore->getScore();
        $score_final = $this->Controller->QuizScore->getScoreWithBonus();

        Log::debug(__('Quiz::score - Ha ottenuto un punteggio di {score}', ['score' => $score]), ['scope' => ['game']]);

        if ($score_final > $score) {
            $scoreDiff = $score_final - $score;

            Log::debug(
                __('Quiz::score - Punteggio bonus (per aiuti non utilizzati) {score} + {score_diff} = {score_bonus}', [
                    'score'       => $score,
                    'score_diff'  => $scoreDiff,
                    'score_bonus' => $score_final
                ]),
                ['scope' => ['game']]
            );
        }

        // Aggiunge risultato a sessione
        $session['_score'] = [
            'score'       => $score,
            'score_final' => $score_final,
        ];

        return compact('score', 'score_final');
    }

}
