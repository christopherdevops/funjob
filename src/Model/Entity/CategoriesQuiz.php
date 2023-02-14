<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CategoriesQuiz Entity
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $category_id
 *
 * @property \App\Model\Entity\Quiz $quiz
 * @property \App\Model\Entity\Category $category
 */
class CategoriesQuiz extends Entity
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
        'id' => false,
    ];
}
