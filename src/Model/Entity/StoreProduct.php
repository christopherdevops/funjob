<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Routing\Router;

use Hiryu85\Traits\UploadImageTrait;

/**
 * StoreProduct Entity
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property float $amount
 * @property int $qty
 * @property bool $is_visible
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\User $user
 */
class StoreProduct extends Entity
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

    protected function _getSlug()
    {
        return Text::slug($this->name, '-');
    }

    protected function _getUrl()
    {
        return Router::url([
            '_name' => 'store:product:view',
            'id'    => $this->id,
            'slug'  => $this->_getSlug()
        ]);
    }

    protected function _getUrlEdit()
    {
        return Router::url([
            '_name' => 'store:admin:product:edit',
            'id'    => $this->id,
            'slug'  => $this->_getSlug()
        ]);
    }

    protected function _getDescrSmall()
    {
        return Text::truncate($this->descr, 150);
    }
}
