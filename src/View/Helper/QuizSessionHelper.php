<?php
namespace App\View\Helper;

use App\Model\Entity\QuizSessionLevel;

use Cake\View\Helper;
use Cake\View\View;

/**
 * QuizResult helper
 *
 * Richiede:
 * $this->QuizSession->config('entity', \App\Model\Entity\QuizSession $QuizSessionEntity)
 */
class QuizSessionHelper extends Helper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'entity' => null
    ];

    protected $scoreTitles = [];
    protected $scoreTitleHeads = [];

    public function initialize(array $config = [])
    {
        parent::initialize($config);
        $this->scoreTitleHeads = ['default' => __('Gioco non certificato (creato dagli utenti)'), 'funjob' => __('Gioco certificato Funjob')];
        $this->scoreTitles = [
            'default' => [
                '0-7'   => ['text' => __x('titolo punteggio totale gioco', 'Appassionato'), 'icon' => '<i class="text-danger fa fa-heart"></i>'],
                '8-9'   => ['text' => __x('titolo punteggio totale gioco', 'Esperto'), 'icon' => '<i class="text-color-primary fa fa-book"></i>'],
                '10-13' => ['text' => __x('titolo punteggio totale gioco', 'Professionista'), 'icon' => '<i class="text-color-primary fa fa-graduation-cap"></i>'],
            ],
            'funjob' => [
                '0-21'  => ['text' => __x('titolo punteggio totale gioco', 'Appassionato'), 'icon' => '<i class="text-danger fa fa-heart"></i>'],
                '22-27' => ['text' => __x('titolo punteggio totale gioco', 'Esperto'), 'icon' => '<i class="text-color-primary fa fa-book"></i>'],
                '28-39' => ['text' => __x('titolo punteggio totale gioco', 'Professionista'), 'icon' => '<i class="text-color-primary fa fa-graduation-cap"></i>'],
            ]
        ];
    }

    public function getEntity() {
        $entity = $this->config('entity');

        if (empty($entity)) {
            throw new \Exception(__(
                'Require $this->QuizSession->config("entity", \App\Model\Entity\QuizSession $QuizSessionEntity)', ['class' => __CLASS__]
            ));
        }

        return $entity;
    }

    public function getLegend() {
        $out  = '';
        $out .= '<div class="row">';

        foreach ($this->scoreTitles as $type => $ranges) {

            $out .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
            $out .= '<ul class="list-group">';
            $out .= '<li class="list-group-item list-group-item--'.$type.' disabled">';
            $out .= '<i class="fontello-quiz-play"></i> <span class="text-bold">' .$this->scoreTitleHeads[$type]. '</span>';
            $out .= '</li>';

            foreach ($ranges as $range => $data) {
                $out .= '<li class="list-group-item">' .$data['icon']. ' <strong>' .$data['text'].  '</strong> (' .__('{range} domande corrette', ['range' => $range]). ')</li>';
            }

            $out .= '</div>';
        }

        $out .= '</div>';
        return $out;
    }

    /**
     * Il quiz supporta più livelli
     *
     * @return boolean
     */
    public function hasMultiLevel() {
        return $this->getEntity()->quiz->type != 'default';
    }

    /**
     * Restituisce i livelli previsti per il quiz corrente
     *
     * @return int
     */
    public function getLevels()
    {
        switch($this->getEntity()->quiz->type) {
            case 'funjob':
                return 3;
            break;

            default:
                return 1;
        }
    }

    public function getLevelsPassed($level = null)
    {
        if (!empty($level)) {
            foreach ($this->getEntity()['levels_passed'] as $levelData) {
                if ($levelData->level == $level) {
                    return $levelData;
                }
            }

            return new QuizSessionLevel(['level' => $level]);
        }

        return $this->getEntity()['levels_passed'];
    }

    /**
     * Restituisce il totale di tutti i livelli giocati
     *
     * @return int
     */
    public function getFinalScore()
    {
        $total = 0;
        foreach ($this->getLevelsPassed() as $level) {
            $total += $level->score;
        }

        return $total;
    }

    /**
     * Restituisce il totale di tutti i livelli giocati
     *
     * @return int
     */
    public function getFinalScoreMax()
    {
        return 13 * $this->getLevels();
    }

    public function getCompletedLevel()
    {
        return $this->getLevelsPassed();
    }

    /**
     * Restituisce titolo in base a punteggio finale ottenuto
     *
     * @return array
     */
    public function getFinalScoreTitle()
    {
        $type   = $this->getLevels() > 1 ? 'funjob' : 'default';
        $score  = $this->getFinalScore();
        $titles = $this->scoreTitles[$type];
        $_scores = [];

        foreach ($titles as $range => $titleData) {
            list($from, $to) = explode('-', $range);

            $ranges = range($from, $to);
            foreach ($ranges as $i) {
                $_scores[$i] = $titleData;
            }
        }

        if (isset($_scores[ $score ])) {
            return $_scores[ $score ];
        }

        return null;
    }

}
