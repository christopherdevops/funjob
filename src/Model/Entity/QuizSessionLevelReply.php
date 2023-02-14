<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * QuizSessionLevelReply Entity
 *
 * @property int $id
 * @property int $quiz_session_level_id
 * @property int $question_id
 * @property int $quiz_answer_id
 *
 * @property \App\Model\Entity\QuizSessionLevel $quiz_session_level
 * @property \App\Model\Entity\Question $question
 * @property \App\Model\Entity\QuizAnswer $quiz_answer
 */
class QuizSessionLevelReply extends Entity
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
