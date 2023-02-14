<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * QuizAnswer Helper
 */
class QuizAnswerHelper extends Helper
{

    /**
     * Restituisce la risposta corretta tra una lista di risposte
     *
     * @param  array $QuizQuestionAnswersList QuizQuestionAnswers
     * @return \App\Model\Entity\QuizQuestionAnswer
     */
    public function getCorrect($QuizQuestionAnswersList) {
        $correct = array_filter($QuizQuestionAnswersList, function($item) {
            return $item['is_correct'] === true;
        });

        if (empty($correct)) {
            return null;
        }

        return array_shift($correct);
    }

    /**
     * Mostra la risposta
     *
     * @param  str $answerText
     * @return str
     */
    public function answer($answerText)
    {
        if (in_array($answerText, ['__TRUE__', '__FALSE__'])) {
            return $answerText == '__TRUE__' ? __('Vero') : __('Falso');
        }

        return $answerText;
    }

}
