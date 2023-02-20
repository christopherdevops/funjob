<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

use Cake\Event\Event;
use Cake\Core\Configure;

/**
 * Quiz component
 */
class QuizQuestionComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->Controller = $this->_registry->getController();
    }

    public function startup(\Cake\Event\EventInterface $event)
    {
        $this->setController($event->subject());
    }

    public function setController($controller)
    {
        $this->Controller = $controller;
    }

    /**
     * Restituisce il conteggio delle domande per il quiz attivo
     *
     */
    public function getQuestionsCounter()
    {
        $quiz_id  = $this->Controller->quiz->id;

        // array => "easy" => [0,1,3], "medium" => [4,5,6], ....
        $complexityRanges = $this->_complexityRangesFromLevelsConfig();
        $questionsCounter = [];

        foreach (['easy', 'medium', 'hard'] as $level) {
            $complexity = $complexityRanges[ $level ];

            if (empty($this->Controller->QuizQuestions)) {
                $this->Controller->loadModel('QuizQuestions');
            }

            $find = $this->Controller->QuizQuestions->find('questionsByLevel', compact('quiz_id', 'complexity'));
            $questionsCounter[ $level ] = $find->count();
        }

        $this->Controller->set(compact('questionsCounter'));

        return $questionsCounter;
    }

    /**
     * Restituisce lista di complexity a seconda del livello
     *
     * @return array
     */
    private function _complexityRangesFromLevelsConfig() {
        // $easy   = Configure::read('app.quiz.funjob.answerDifficultyLevel.1');
        // $medium = Configure::read('app.quiz.funjob.answerDifficultyLevel.4');
        // $hard   = Configure::read('app.quiz.funjob.answerDifficultyLevel.7');

        $easy   = Configure::read('app.quiz.funjob.answerDifficultyLevel.1');
        $medium = Configure::read('app.quiz.funjob.answerDifficultyLevel.2');
        $hard   = Configure::read('app.quiz.funjob.answerDifficultyLevel.3');

        return compact('easy', 'medium', 'hard');
    }

    /**
     * Mostra fieldset risposte a seconda del tipo di tipologia di domanda selezionata (radio)
     *
     * Nel caso si fà un POST con errori di validazione, mostra il fieldset contenente le risposte automaticamente.
     * (Viene mostrato all'onclick sui radio)
     *
     * @return void
     */
    public function autoShowAnswerByType()
    {
        $answerFieldsets            = Configure::read('app.quizQuestion.types');
        $answerFieldsets            = array_fill_keys($answerFieldsets, false);
        $answerFieldsets['default'] = true;

        if ($this->Controller->getRequest()->is('post')) {
            $answerFieldsets['default']                      = false;
            $answerFieldsets[ $this->request->getData('type') ] = true;
        }

        return $this->Controller->set(compact('answerFieldsets'));
    }

    /**
     * Verifica che nel quiz ci siano un numero di domande minime
     *
     * Utile per verificare se un quiz può essere pubblicato
     *
     * @return bool
     */
    public function hasMinimiumQuestions()
    {
        $type = ucwords($this->Controller->quiz->type);
        return call_user_func_array([&$this, '_' . $type . 'HasMinimumQuestions'], []);
    }

    private function _defaultHasMinimumQuestions()
    {
        $complexities = $this->getQuestionsCounter();
        $result = 0;

        foreach ($complexities as $count) {
            $result += $count;
        }

        return $result >= (int) Configure::readOrFail('app.quiz.default.minQuestions');
    }

    private function _funjobHasMinimumQuestions()
    {
        $complexities = $this->getQuestionsCounter();
        $result = true;

        $minQuestions = (int) Configure::readOrFail('app.quiz.funjob.minQuestions');
        foreach ($complexities as $count) {
            if ($count < $minQuestions) {
                $result = false;
                break;
            }
        }

        return $result;
    }

}
