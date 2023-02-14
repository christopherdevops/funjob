<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Core\Configure;

use App\Model\Entity\Quiz;

/**
 * QuizQuestionCounter cell
 */
class QuizQuestionCounterCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [
    ];

    /**
     * Crea contatore
     *
     * @param  Quiz   $quiz [description]
     * @return [type]       [description]
     */
    public function funjobCounter(Quiz $quiz)
    {
        $this->loadModel('QuizQuestions');

        $minQuestionForLevel = Configure::read(sprintf('app.quiz.%s.minQuestions', $quiz->type));
        $counters            = [];
        $levelCounters       = $this->QuizQuestions ->find('isPublished') ->find('countByLevel', ['quiz_id' => $quiz->id])->hydrate(false)->first();
        $levels              = ['_total' => 0, '_lefts' => $minQuestionForLevel];

        // Contatore: domande totali
        foreach ($levelCounters as $levelString => $length) {
            $levels['complexity'][$levelString]['created'] = $length;
            $levels['_total'] += $length;

            if ($levels['_lefts'] > 0) {
                $diff = $levels['_lefts'] - $length;
                $levels['_lefts'] = $diff > 0 ? $diff : 0;
            }
        }

        // Calcolo attributi secondari (perc, etc)
        foreach ($levels['complexity'] as $levelString => $levelData) {
            $progressClass = $this->__getProgressClass($levelString);

            $created  = $levelData['created'];
            $required = $minQuestionForLevel; // $minQuestionForLevel / sizeof($levels['complexity']);

            if ($created > $required) {
                $lefts = 0;
            } else {
                $lefts = abs($created - $required);
            }

            // Previene eccezione division by zero
            if ($levels['_total'] > 0) {
                if ($levels['_lefts'] == 0) { // Tutte le domande richieste sono state create
                    $chunkPerc = (($created / $levels['_total']) * 100);
                } else {
                    $chunkPerc = (($created / $required) * 100);
                }
            } else {
                $chunkPerc = 0;
            }

            $totalPerc     = number_format($chunkPerc, 2); // percentuale totale domande inserite
            $progressClass = $this->__getProgressClass($levelString);

            $counters[$levelString] = compact('created', 'required', 'lefts', 'progressClass', 'totalPerc');
        }

        $this->set(compact('counters'));
    }

    public function defaultCounter(Quiz $quiz)
    {
        $this->loadModel('QuizQuestions');

        $minQuestionForLevel = Configure::read(sprintf('app.quiz.%s.minQuestions', $quiz->type));
        $counters            = [];
        $levelCounters       = $this->QuizQuestions->find('countByLevel', ['quiz_id' => $quiz->id])->hydrate(false)->first();
        $levels              = ['_total' => 0, '_lefts' => $minQuestionForLevel];

        // Contatore: domande totali
        foreach ($levelCounters as $levelString => $length) {
            $levels['complexity'][$levelString]['created'] = $length;
            $levels['_total'] += $length;

            if ($levels['_lefts'] > 0) {
                $diff = $levels['_lefts'] - $length;
                $levels['_lefts'] = $diff > 0 ? $diff : 0;
            }
        }

        // Calcolo attributi secondari (perc, etc)
        foreach ($levels['complexity'] as $levelString => $levelData) {
            $progressClass = $this->__getProgressClass($levelString);
            $created       = $levelData['created'];
            $required      = null;                                      // Configure::read(sprintf('app.quiz.%s.minQuestions', $quiz->type));
            $lefts         = null;                                      // domande aspettate per livello (il default non ha questa logica)

            // Previene eccezione division by zero
            if ($levels['_total'] > 0) {
                if ($levels['_lefts'] == 0) { // Tutte le domande richieste sono state create
                    $chunkPerc = (($created / $levels['_total']) * 100);
                } else {
                    $chunkPerc = (($created / $minQuestionForLevel) * 100);
                }
            } else {
                $chunkPerc = 0;
            }

            $totalPerc     = number_format($chunkPerc, 2); // percentuale totale domande inserite
            $progressClass = $this->__getProgressClass($levelString);

            $counters[$levelString] = compact('created', 'required', 'lefts', 'progressClass', 'totalPerc');
        }

        $this->set(compact('counters'));
    }



    private function __getProgressClass($levelString)
    {
        switch($levelString) {
            case 'easy':
                $progressClass = 'success';
            break;

            case 'medium':
                $progressClass = 'warning';
            break;

            case 'hard':
                $progressClass = 'danger';
            break;

            default:
                $progressClass = 'default';
        }

        return $progressClass;
    }

}
