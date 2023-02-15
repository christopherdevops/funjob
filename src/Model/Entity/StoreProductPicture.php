<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Hiryu85\Traits\UploadImageTrait;

/**
 * StoreProductPicture Entity
 *
 * @property int $id
 * @property int $product_id
 * @property string $image
 * @property string $dir
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\Product $product
 */
class StoreProductPicture extends Entity
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
        'product_id' => false
    ];

    protected function _getSrcFallback()
    {
        $_src = str_replace('webroot/', '', $this->_fields['dir']) .'/'. $this->_fields['image'];
        if (file_exists(WWW_ROOT . $_src)) {
            return $_src;
        }

        return 'holder.js/200x200&text=404&auto=yes';
    }

    protected function _getUrlDelete()
    {
        return \Cake\Routing\Router::url([
            'controller' => 'StoreProductPictures',
            'action'     => 'delete',
            0            => $this->id
        ]);
    }
}
