<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * QuizSession Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $quiz_id
 * @property int $category_id
 * @property string $lang
 * @property string $score
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Quiz $quiz
 * @property \App\Model\Entity\Category $category
 */
class QuizSession extends Entity
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


    /**
     * URL per report sessione di gioco
     *
     * Viene utilizzato dalla condivisione
     *
     * @return [type] [description]
     */
    public function _getUrl()
    {
        return ['_name' => 'quiz:report', $this->id];
    }
}
