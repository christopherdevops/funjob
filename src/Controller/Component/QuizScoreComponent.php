<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Log\Log;

/**
 * QuizScore component
 */
class QuizScoreComponent extends Component
{
    const PIX_PERCENTAGE_AUTHOR = 30;
    const PIX_PERCENTAGE_PLAYER = 70;


    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'quizSessionPath' => null
    ];


    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->Controller = $this->_registry->getController();
        $this->Session    = $this->Controller->request->session();
    }



    /**
     * Restituisce sessione di gioco per quiz corrente
     *
     * @return array
     */
    public function getSession($key = null)
    {
        $sessionPath = $this->config('quizSessionPath');

        if (!empty($key)) {
            $sessionPath .= '.' . $key;
        }

        return $this->Session->read($sessionPath);
    }

    /**
     * Determina se il quiz è stato superato (in base alle domande corrette)
     *
     * @return boolean [description]
     */
    public function isPassed()
    {
        $filterAnswerCorrect = $this->getAnswerCorrects();
        return sizeof($filterAnswerCorrect) >= Configure::read('app.quiz.minScoreRequired');
    }

    /**
     * Determina punteggio giocata
     *
     * @return int
     */
    public function getScore()
    {
        $filterAnswerCorrect = $this->getAnswerCorrects();
        $score = sizeof($filterAnswerCorrect);
        return $score;
    }

    /**
     * Determina punteggio giocata
     *
     * @return int
     */
    public function getScoreWithBonus()
    {
        $filterAnswerCorrect = $this->getAnswerCorrects();
        $score = sizeof($filterAnswerCorrect);

        if ($score == 10) {
            // Assegna punteggi extra per aiuti non utilizzati
            foreach ((array) $this->getSession('suggestions') as $suggestion => $isAvailable) {
                if ($isAvailable) {
                    $score++;
                }
            }
        }

        return $score;
    }

    /**
     * Restituisce le domande corrette date dall'utente
     *
     * @return array
     */
    public function getAnswerCorrects()
    {
        $filterAnswerCorrect   = array_filter((array) $this->getSession('replies'), function($item) {
            return $item['is_correct'] === true;
        });

        return $filterAnswerCorrect;
    }

    public function getReplies()
    {
        // FUTURE:
        // Si potrebbe leggere le domande direttamente da database
        // piuttosto che da sessione.
        $replies   = $this->getSession('replies');
        $questions = [];

        foreach ($replies as $key => $value) {
            if (is_numeric($key)) {
                $questions[$key] = $value;
            }
        }

        return $questions;
    }


    /**
     * Restituisce il numero di pubblicità che il client (giocatore) ha visualizzato durante la giocata
     *
     * @return int
     */
    public function getAdvViewed()
    {
        if (empty($this->getSession('_advViewed'))) {
            return 0;
        }

        return (int) $this->getSession('_advViewed');
    }


    /**
     * Crediti maturati dall'autore del quiz
     *
     * @return int
     */
    public function getAuthorPix()
    {
        $_advViewed = $this->getAdvViewed();
        $pixs       = 0;

        if ($this->isPassed()) {
            if ($_advViewed > 0) {
                $pixs = ($_advViewed * self::PIX_PERCENTAGE_AUTHOR) / 100;
            }
        } else {
            if (in_array($_advViewed, [0,1])) {
                $pixs = 0;
            } elseif (in_array($_advViewed, [2])) {
                $pixs = 1;
            } elseif (in_array($_advViewed, [3,4,5])) {
                $pixs = 2;
            } else {
                $pixs = 3;
            }
        }

        return (int) $pixs;
    }

    /**
     * PIX gudagagnati dall'utente nella sessione corrente.
     *
     * @return int
     */
    public function getPlayerPix()
    {

        if (!$this->isPassed()) {
            return 0;
        }

        $_advViewed = $this->getAdvViewed();
        if (!$_advViewed) {
            return 0;
        }

        return (int) (($_advViewed * self::PIX_PERCENTAGE_PLAYER) / 100);
    }

}
