<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Hiryu85\Traits\UploadImageTrait;

/**
 * UserGroup Entity
 *
 * @property int $id
 * @property int $group
 * @property int $user_id
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\User $user
 */
class UserGroup extends Entity
{
    use UploadImageTrait;

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

    protected function _getUrl()
    {
        return ['_name' => 'groups:view', 'id' => $this->id, 'slug' => $this->_getSlug()];
    }

    protected function _getSlug()
    {
        if (!empty($this->_properties['name'])) {
            return \Cake\Utility\Text::slug($this->_properties['name'], '-');
        }

        return null;
    }

    /**
     * Percorso completo immagine caricata
     *
     * @return str
     */
    protected function _getCoverSrc()
    {
        if (!empty($this->id) && !empty($this->_properties['image'])) {
            return '/uploads/user_groups/cover/'. $this->id .'/'. $this->_properties['image'];
        } else {
            return '/img/default-group-cover.png';
        }

        return null;
    }
}
