<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StoreLogo Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $total
 * @property int $used
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 */
class StoreProductCompany extends Entity
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
        '*'  => true,
        'id' => false
    ];


    protected function _getImage()
    {
        if ($this->_properties['image']) {
            return $this->_properties['image'];
        }

        if ($this->_properties['name']) {
            return sprintf(
                'webroot/img/gift-logos/'. \Cake\Utility\Inflector::slug($this->_properties['name']).' .png'
            );
        }

        return null;
    }

    protected function _getUrl() {
        return ['_name' => 'store:product:archive'];
    }
}
