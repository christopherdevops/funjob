<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * QuizSessionLevel Entity
 *
 * @property int $id
 * @property int $quiz_session_id
 * @property int $level
 * @property int $points
 * @property int $score
 * @property bool $help_50perc_used
 * @property bool $help_25perc_used
 * @property bool $help_75perc_used
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 *
 * @property \App\Model\Entity\QuizSession $quiz_session
 */
class QuizSessionLevel extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
