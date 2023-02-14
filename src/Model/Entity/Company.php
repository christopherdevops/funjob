<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

use Hiryu85\Traits\UploadImageTrait;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $fullname
 * @property string $password
 * @property string $email
 * @property string $group
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\Quiz[] $quizzes
 * @property \App\Model\Entity\StoreOrder[] $store_orders
 * @property \App\Model\Entity\StoreProduct[] $store_products
 * @property \App\Model\Entity\UserCredit[] $user_credits
 */
class Company extends User
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

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    /**
     * Url a profilo pubblico utente
     *
     * Override UserEntity::_getUrl()
     * @return array
     */
    protected function _getUrl()
    {
        return ['_name' => 'companies:profile', 'id' => $this->id, 'username' => $this->_getSlug() ];
    }

    /**
     * Slug username
     *
     * @return str
     */
    protected function _getSlug()
    {
        return \Cake\Utility\Text::slug($this->username, '-');
    }

}
