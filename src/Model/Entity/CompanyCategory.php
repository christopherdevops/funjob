<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CompanyCategory Entity
 *
 * @property int $id
 * @property int $parent_id
 * @property int $level
 * @property int $lft
 * @property int $rght
 * @property string $name
 *
 * @property \App\Model\Entity\ParentCompanyCategory $parent_company_category
 * @property \App\Model\Entity\ChildCompanyCategory[] $child_company_categories
 */
class CompanyCategory extends Entity
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


    protected function _getSlug()
    {
        return \Cake\Utility\Text::slug($this->name, '-');
    }
}
