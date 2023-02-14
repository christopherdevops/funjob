<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Quiz component
 */
class QuizComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    /**
     * Crea QuizAnswer.1 per le QuizQuestion.type=true_or_false
     *
     * Poichè nell'UI non c'è il riferimento a quiz_answers.1
     *
     */
    public function buildAnswerData()
    {
        $ctrl = $this->_registry->getController();
        $req  = $ctrl->request;

        switch($req->data('type')) {
            case 'true_or_false':
                $isTrue = (bool) $req->data('quiz_answers.0.is_correct');

                if ($isTrue) {
                    $req->data('quiz_answers.0.answer', '__TRUE__');
                    $req->data('quiz_answers.0.is_correct', '1');
                    $req->data('quiz_answers.1.answer', '__FALSE__');
                    $req->data('quiz_answers.1.is_correct', '0');
                } else {
                    $req->data('quiz_answers.0.answer', '__FALSE__');
                    $req->data('quiz_answers.0.is_correct', '1');
                    $req->data('quiz_answers.1.answer', '__TRUE__');
                    $req->data('quiz_answers.1.is_correct', '0');
                }

            break;

            default:

        }

    }
}
