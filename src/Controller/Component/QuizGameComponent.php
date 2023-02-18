<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

use \Cake\Core\Configure;
use \Cake\Utility\Hash;
use Cake\Routing\Router;

use Cake\Event\Event;

/**
 * Quiz component
 */
class QuizGameComponent extends Component
{

    const QUESTION_FOR_LEVEL = 10;
    const MAX_QUESTION = 10;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'quiz_id' => null
    ];

    /**
     * QuizEntity
     *
     * @var [type]
     */
    public $entity;

    private $_quiz;
    private $_questions;


    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->Controller = $this->_registry->getController();

        //$this->Session    = $this->Controller->getRequest()->getSession();
        // Crea sessione di gioco
        $this->_initgetSession();
    }

    /**
     * Restituisce sessione del quiz corrente
     *
     * @param  str $key
     * @return mixed
     */
    public function session($key)
    {
        $sessionKey = $this->getSessionPath(); // sprintf('Quiz.%d', $this->_config['quiz_id']);

        if (!empty($key)) {
            $sessionKey .= '.' . $key;
        }

        return $this->Controller->getRequest()->getSession()->read($sessionKey);
    }

    /**
     * Restituisce sessione dello step $step del quiz corrente
     *
     * @param  int $step
     * @param  str $key
     * @return mixed
     */
    public function sessionStep($step, $key)
    {
        $sessionKey = $this->getSessionPathStep($step); // sprintf('Quiz.%d.%d', $this->_config['quiz_id'], $step);

        if (!empty($key)) {
            $sessionKey .= '.' . $key;
        }

        return $this->Controller->getRequest()->getSession()->read($sessionKey);
    }


    /**
     * Restituisce il nome della sessione del quiz corrente
     *
     * @param  string $key [description]
     * @return str
     */
    public function getSessionPath($key = '') {
        // Componente instanziato tramite $this->loadModel ma senza $config
        // Potrebbe essere istanziato senza settings e impostati al volo (tramite $this->QuizGame->config('quiz_id', N)) in quel caso
        // $this->_config['quiz_id'] è nullo
        if (
            empty($this->_config['quiz_id']) &&
            !empty($this->Controller->getRequest()->getParam('id'))
        ) {
            $this->_config['quiz_id'] = (int) $this->Controller->getRequest()->getParam('id');
        }

        $keyPath = sprintf('Quiz.%d', $this->_config['quiz_id']);
        if (!empty($key)) {
            $keyPath .= '.' . $key;
        }

        return $keyPath;
    }

    public function getSessionPathStep($step = 1) {
        return sprintf('%s.%d', $this->getSessionPath('replies'), $step);
    }


    public function play()
    {
        // Determina step attivo
        $step = $this->getStep();
        $this->_entity = $this->initializeGame($step);
    }

    /**
     * Restituisce session per QuizAttivo
     *
     * @return array
     */
    private function _initgetSession()
    {
        $keyRoot = $this->getSessionPath();

        // Crea sessione Quiz.<id>
        if (!$this->Controller->getRequest()->getSession()->check($keyRoot)) {
            $this->Controller->getRequest()->getSession()->write($keyRoot, []);
        }

        // Inizializza sessione
        for ($i=1; $i <= self::QUESTION_FOR_LEVEL; $i++) {

            if ($this->Controller->getRequest()->getSession()->check("{$keyRoot}.replies.{$i}")) {
                continue;
            }

            $data = [
                'Question'   => null,   // Domanda + risposte possibili
                'is_correct' => null,   // Risposta utente (true=corretta  false=sbagliata  null=senza risposta)
                'started_at' => null,   // Data in cui è partita la domanda (necessaria per calcolare expire -vedi sotto-)
                'expire_at'  => null
            ];

            $this->Controller->getRequest()->getSession()->write($keyRoot. '.replies.' .$i, $data);
        }

        return $this->Controller->getRequest()->getSession()->read($keyRoot);
    }

    /**
     * Crea QuizAnswer.1 per le QuizQuestion.type=true_or_false
     *
     * Poichè nell'UI non c'è il riferimento a quiz_answers.1
     *
     */
    public function initializeGame($step = 1)
    {
        $session = $this->Controller->getRequest()->getSession();

        // Quiz.n.quiz
        if (!$session->read($this->getSessionPath('quiz'))) {
            $session->write($this->getSessionPath('quiz'), $this->_getQuiz()->first());
        }

        if (!$session->read($this->getSessionPath('level'))) {
            $session->write($this->getSessionPath('level'), $this->Controller->getRequest()->getParam('level'));
        }

        $this->_quiz = $session->read($this->getSessionPath('quiz'));

        // Quiz.n.questions
        if (
            !$session->read($this->getSessionPath('questions')) ||
            isset($this->Controller->getRequest()->query['force'])
        ) {
            $session->write($this->getSessionPath('questions'), $this->_getQuestions()->all());
        }

        $this->_questions        = $session->read($this->getSessionPath('questions'));

        $this->Controller->_quiz = $this->_quiz; // TODO: metodo getter?

        // Determinare se le domande siano realmente 10
        if (sizeof($this->_questions) < 10) {
            $this->Controller->Flash->error(__('Non sono state trovate abbastanza domande per questo livello'));
            return $this->Controller->redirect($this->Controller->referer('/'));
        }

        // Crea step su sessione
        //
        // Ogni step contiene i dati:
        // 1. question          -> Question entity
        // 2. started_at        -> unixtime in cui è stata assegnata la domanda all'utente
        // 3. expire_at         -> unixtime in cui scade la domanda
        // 4. already_rendered  -> se la domanda è già stata visualizzata dall'utente
        $stepSession = $this->getSessionPathStep($step);

        if (empty($this->sessionStep($step, 'Question'))) {
            // \Cake\Log\Log::write('debug', sprintf('Estrazione domande quiz'));

            $Question = $this->_getQuestion();

            $session->write($stepSession. '.Question', $Question);
            $session->write($stepSession. '.started_at', time());
            $session->write($stepSession. '.expire_at', time() + (int) Configure::read('app.quizAnswer.timeout'));
            $session->write($stepSession. '.already_rendered', false);
        } else {
            // \Cake\Log\Log::write('debug', sprintf('Estrazione domande quiz (da cache)'));

            $Question = $session->read($stepSession);
        }


        // Passo variabili a vista
        $this->Controller->set('quiz', $this->_quiz);
        $this->Controller->set('question', $Question);
        $this->Controller->set('step', $step);

        // Sessione: suggerimenti
        $sessionPath = $this->getSessionPath('suggestions');
        if (!$session->check($sessionPath)) {
            // Inizializza sessione
            $session->write($sessionPath, [
                'skip'   => true,
                'perc50' => true,
                'perc25' => true
            ]);
        }

        if ($this->_hasSuggestionsAvailable()) {
            $this->Controller->set('suggestions', $this->_prepareSuggestions());
        }

        return $Question;
    }

    /**
     * Estrae la domanda dello step corrente prendendola dalla Collection in sessione
     *
     * @return \App\Model\QuestionEntity
     */
    private function _getQuestion()
    {
        $questions     = $this->Controller->getRequest()->getSession()->read($this->getSessionPath('questions'));
        $questionIndex = $this->getStep() - 1;

        return $this->_questions->take(1, $questionIndex)->first();
    }

    /**
     * Estrae Quiz
     */
    private function _getQuiz()
    {
        $q = $this->Controller->Quizzes->find();
        $q->where(['Quizzes.id' => $this->_config['quiz_id']]);
        $q->contain([
            'Author' => function($q) {
                return $q->select(['id', 'username', 'email', 'avatar', 'is_disabled']);
            }
        ]);

        //$q->enableHydration(false);
        return $q;
    }

    /**
     * Estrae domande
     */
    private function _getQuestions()
    {

        $q = $this->Controller->Quizzes->QuizQuestions->find();
        $q->find('isPublished');

        $q->where(['quiz_id' => $this->_config['quiz_id']]);
        $q->contain([
            'QuizAnswers' => function($q) {
                if (Configure::read('app.quiz.randomizeAnswers')) {
                    $q->order(['RAND(CURRENT_TIMESTAMP)']);
                }
                return $q;
            }
        ]);

        $q->order(['RAND(CURRENT_TIMESTAMP)']);
        $q->limit(10);

        // Implementare difficoltà domande in base al livello richiesto
        $level = $this->Controller->getRequest()->getParam('level');

        // NOTE:
        // Attacca alla query dei filtri
        $this->_getQuestionByLevel($q, $level);


        //$q->enableHydration(false);
        return $q;
    }

    /**
     * Restituisce le domande in base al range di difficoltà specificato a $level
     *
     * Valido solo per Quizzes Funjob
     *
     * @param  \Cake\ORM\Query $q
     * @param  integer $level
     * @return void
     */
    private function _getQuestionByLevel($q, $level = 1)
    {
        $configPath = sprintf('app.quiz.funjob.answerDifficultyLevel.%d', $level);
        $config     = Configure::read($configPath);

        if (!$config) {
            $errmsg = __('Livello {level} non previsto per la tipologia di quiz {quiz_type}', [
                'quiz_type' => $this->_quiz->type,
                'level'     => $level
            ]);

            throw new \Cake\Network\Exception\NotImplementedException($errmsg, 500);
        }

        // Difficoltà in base al livello
        $difficultyRange = [1,2,3,4,5,6,7,8,9]; // default

        if ($this->_quiz->type == 'funjob') {
            $difficultyRange = array_values($config);
        }

        $qSession = $this->Controller->Quizzes->QuizSessions->find();
        $qSession->where([
            'QuizSessions.quiz_id'    => $this->_config['quiz_id'],
            'QuizSessions.user_id'    => $this->Controller->Auth->user('id'),
            'QuizSessions.is_deleted' => false
        ]);
        $qSession->contain([
            'Levels' => function($q) {
                return $q;
            },

            // Filtra solo domande per il ranges richiesto
            'Quizzes.QuizQuestions' => function($q) use ($difficultyRange) {
                $q->where(['complexity IN' => $difficultyRange]);
                return $q;
            },
            // Esclude domande già poste all'utente
            'Levels.Replies' => function($q) {
                $q->select(['quiz_session_level_id', 'question_id']);
                return $q;
            }
        ]);

        $questions = $qSession->enableHydration(false)->first();
        if (!$questions) {
            return [];
        }


        // Determino se l'utente ha già giocato il livello richiesto
        // Se si, ricavo la lista di domande già assegnate
        $LevelSession = (object) [
            'played'   => false,
            'level'    => $level,
            'index'    => null
        ];

        foreach ($questions['levels'] as $index => $sessionLevel) {
            if ($sessionLevel['level'] == $level) {
                $LevelSession->played = true;
                $LevelSession->index  = $index;
                break;
            }
        }

        // Mostra sempre le stesse domande per il livello corrente
        // Questo ha senso se si utilizzano 9 livelli (altrimenti le brucierebbe tutte immediatamente) ritentando
        // il quiz nuovamente.

        // IMPORTANTE:
        // Questo codice crea un problema nel caso la domanda già giocata con il tempo
        // viene disattivata dall'utente o dall'admin.
        // Estrae sempre lo stesso subsets di domande per il livello, ma se quella domanda specifica non è pubblicata
        // non viene restituita nel SELECT e quindi si trovano meno domande di quelle necessari per avviare il quiz.
        // Di conseguenza verrà visualizzato l'errore "Domande non trovate".
        //
        // Per ora, che ci sono tre livelli questo controllo su whitelist domande già giocate può essere disabilitato
        // poichè era utile nel caso ci fossero 3 livelli per ogni livello di difficoltà. Ma ora che c'è un solo livello
        // per difficoltà non è più necessario.
        // Eliminata percui condizione where 'QuizQuestions.id NOT IN'
        if ($LevelSession->played == false) {
            $q->distinct(['QuizQuestions.id']);
            // $q->where([
            //     'QuizQuestions.id NOT IN' => Hash::extract($questions['levels'], '{*}.replies.{*}.question_id')
            // ]);
        } else {
            $q->distinct(['QuizQuestions.id']);
            // $q->where([
            //     'QuizQuestions.id IN' => Hash::extract($questions['levels'][$LevelSession->index]['replies'], '{*}.question_id')
            // ]);
        }


        //$ids = \Cake\Utility\Hash::extract($questions['levels'], '{*}.replies.{*}.question_id');
        // rimuove ids duplicati (anche se non dovrebbero esserci)
        //$ids = array_keys(array_flip($ids));
        //return $ids;
    }

    /**
     * In base alla sessione determina in che step è il client
     *
     * @return int
     */
    private function _getQuestionFromgetSession()
    {

        for ($i=1; $i <= self::MAX_QUESTION; $i++) {
            if (is_null($this->sessionStep($i, 'is_correct'))) {
                return $i;
            }
        }

        return self::MAX_QUESTION;
    }

    /**
     * Restituisce step corrente in base a sessiond di gioco
     *
     * @return int
     */
    public function getStep()
    {
        return $this->_getQuestionFromgetSession();
    }

    /**
     * Restituisce data associata a sessione di gicoo attiva
     *
     * @return int
     */
    public function getStepData()
    {
        return $this->sessionStep( $this->getStep() );
    }

    public function restart()
    {
        return $this->Controller->getRequest()->getSession()->delete($this->getSessionPath());
    }

    /**
     * Determina se la domanda è stata risposta correttamente
     *
     */
    public function replyCheck()
    {
        // Disabilita aiuti se utilizzati (vengono passati booleani tramite campo hidden)
        $this->_setSuggestiongetSession();

        $reply   = (int) $this->Controller->getRequest()->data('reply');
        $question = $this->_getQuestion();
        $answers = $question->quiz_answers;
        $correct = null;

        // Determino risposta corretta
        foreach ($answers as $answer) {
            if ($answer->is_correct) {
                $correct = (int) $answer->id;
                break;
            }
        }

        if (empty($correct)) {
            throw new \Cake\Network\Exception\BadRequestException(__('Errore Quiz data: nessuna risposta corretta!!!'));
        }

        $step       = $this->getStep();
        $sessionKey = $this->getSessionPathStep($step);
        $isCorrect  = $reply === $correct;

        // debug(__('{0} selezionata, corretta {1}', $reply, $correct));
        // debug($isCorrect);
        // die;

        $this->Controller->getRequest()->getSession()->write($sessionKey . '.answer_id', $reply);
        $this->Controller->getRequest()->getSession()->write($sessionKey . '.is_correct', (bool) $isCorrect);

        $secs = Configure::readOrFail('app.quizAnswer.timeout') - (int) $this->Controller->getRequest()->getData('secs', '1');
        $this->Controller->getRequest()->getSession()->write($sessionKey . '.reply_after_secs', $secs);

        $params = [
            'id'    => $this->request->getParam('id'),
            'title' => $this->request->getParam('title'),
            'step'  => $this->request->getParam('step'),
            'level' => $this->request->getParam('level')
        ];

        //\Cake\Log\Log::debug(sprintf('Risposta n° %d fornita ... completato? %s', $this->getStep(), $this->isCompleted()));

        if (!$this->isCompleted()) {
            $params['step'] = $this->getStep();
            $url = array_merge(['_name' => 'quiz:play'], $params);

            $this->response->body(json_encode([
                'redirect' => Router::url($url, true),
                'status'   => 'next'
            ]));
            return $this->response;
        } else {
            $this->response->body(json_encode([
                'redirect' => Router::url(['action' => 'save', $this->request->getParam('id')], true),
                'status'   => 'next'
            ]));
        }

    }


    /**
     * Restituisce se ci sono altre schermate di gioco
     *
     * @return boolean
     */
    public function hasNextStep()
    {
        return $this->getStep() < self::MAX_QUESTION;
    }


    /**
     * Verifica che la sessione di gioco sia stata avviata
     *
     * Controlla se la sessione contine true/false
     *
     * @todo  non viene utilizzata
     * @return boolean
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Verifica che il tempo per la domanda corrente sia scaduto
     *
     * TODO:
     * Aggiungere tolleranza di 2 secondi e mezzo
     * @return boolean
     */
    public function isExpired() {
        // Si potrebbe rispondere a -2secondi ma la connessione potrebbe essere lenta e non farcela a contattare il server.
        // Per questo nasce la tolleranza
        $secsTollerance = (int) Configure::read('app.quizAnswer.timeoutTolerance');

        return time() > ( $this->sessionStep($this->getStep(), 'expire_at') + $secsTollerance );
    }

    /**
     * Verifica che l'utente abbia già visualizzato la pagina della domanda
     *
     * Utilizzato per evitare che l'utente possa rivedere la pagina, e magari creare problemi con il
     * contatore js (ora viene effettuato un controllo lato server, percui sarebbe inutile)
     *
     * NOTE:
     * Viene impostata tramite vista src/Template/quizzes/play.ctp (ultime righe)
     *
     * @return boolean
     */
    public function isRefreshed()
    {
        return (bool) $this->sessionStep($this->getStep(), 'already_rendered') === true;
    }

    /**
     * Restituisce se l'utente ha terminato il quiz
     *
     * @return boolean
     */
    public function isCompleted()
    {
        $completed = true;

        for ($i=1; $i <= self::MAX_QUESTION; $i++) {
            if (is_null($this->sessionStep($i, 'is_correct'))) {
                $completed = false;
                break;
            }
        }

        return $completed;
    }


    /**
     * Passa i suggerimenti alla vista
     *
     * Nel caso siano stati già utilizzati questo metodo non ne tiene conto.
     */
    protected function _prepareSuggestions()
    {
        $question  = $this->_getQuestion();
        $correct   = null;
        $uncorrect = null;

        foreach ($question['quiz_answers'] as $Answer) {
            if ($Answer->is_correct) {
                $correct = $Answer->id;
            } else {
                $uncorrect[] = $Answer->id;
            }
        }

        $hashids   = new \Hashids\Hashids('myubersecuresalt');
        $correct   = $hashids->encode($correct);
        $uncorrect = $hashids->encode($uncorrect);

        return [0 => $uncorrect, 1 => $correct];
    }

    /**
     * Restituisce un array contenente tutti gli aiuti disponibili, che come valore ha un booleano
     * che indica se è stato o no usato quel determinato tipo di aiuto
     *
     * @return array
     */
    protected function _getSuggestiongetSession() {
        $sessionPath = $this->getSessionPath('suggestions');
        return $this->Controller->getRequest()->getSession()->read($sessionPath);
    }

    /**
     * Aggiorna sessione dei suggerimenti
     */
    protected function _setSuggestiongetSession()
    {
        // Boolean: true=da utilizzare     false=giù utilizzato
        // Valori: ['skip' => false, 'perc50' => true, 'perc25' => true]
        $suggestionSession = $this->_getSuggestionSession('suggestions');

        // Boolean: true=non utilizzato   false=utilizzato)
        // Valori: ['skip' => false, 'perc50' => true, 'perc25' => true]
        $suggestionData    = $this->Controller->getRequest()->data('suggestions');

        foreach ($suggestionData as $name => $used) {

            // Suggerimenti utilizzati nella risposta corrente
            // true=utilizzato    false=non utilizzato
            if ((bool) $suggestionData[$name] === false || !isset($suggestionSession[$name])) {
                continue;
            }

            if ($suggestionSession[$name] === false) {
                throw new \Cake\Network\Exception\BadRequestException(__('Suggerimento {name} già utilizzato!', ['name' => $name]));
            }

            $this->Controller->getRequest()->getSession()->write($this->getSessionPath('suggestions.' . $name), false);
        }
    }

    /**
     * Verifica che minimo un aiuto sia disponibile
     * @return boolean
     */
    protected function _hasSuggestionsAvailable()
    {
        $suggestions = $this->_getSuggestiongetSession();

        foreach ($suggestions as $name => $used) {
            if ($used === true) {
                return true;
            }
        }

        return false;
    }

}
