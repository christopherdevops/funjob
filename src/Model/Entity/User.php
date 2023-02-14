<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

use Cake\Utility\Text;

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
class User extends Entity
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
        '*'  => true,
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
     * @return array
     */
    protected function _getUrl()
    {
        return ['_name' => 'user:profile:home', 'id' => $this->id, 'username' => $this->_getSlug() ];
    }

    /**
     * Slug username
     *
     * @return str
     */
    protected function _getSlug()
    {
        return Text::slug($this->username, '-');
    }

    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
          return (new DefaultPasswordHasher)->hash($password);
        }
    }

    /**
     * Nome completo utente
     *
     * @return str
     */
    protected function _getFullname()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    protected function _getUsername()
    {
        if (isset($this->_properties['is_disabled']) && $this->_properties['is_disabled']) {
            return __('Anonimo');
        }

        if (isset($this->_properties['username'])) {
            return $this->_properties['username'];
        }

        return null;
    }

    /**
     * Restituisce l'URL dell'avatar
     *
     * @return str
     */
    protected function _getAvatarSrc()
    {
        $defaultAvatar = $avatar = '/img/default-user-avatar.png';
        $avatarDir = '/uploads/user/avatar/';

        if (!empty($this->_properties['avatar'])) {
            $avatar = sprintf($avatarDir . '%d/%s', $this->_properties['id'], $this->_properties['avatar']);
        }

        if (isset($this->_properties['is_disabled']) && $this->_properties['is_disabled']) {
            $avatar = $defaultAvatar;
        }

        return $avatar;
    }

    /**
     * Restituisce l'URL dell'avatar (versione mobile)
     *
     * @return str
     */
    protected function _getAvatarSrcMobile($sizeSuffix = '')
    {
        $avatar = $this->get('avatarSrc');
        $file   = pathinfo($avatar);
        $avatar = $file['dirname'] .DS. $file['filename'] .'--28x28.'. $file['extension'];

        return $avatar;
    }

    /**
     * Restituisce l'URL dell'avatar (versione desktop)
     *
     * @return str
     */
    protected function _getAvatarSrcDesktop()
    {
        $avatar = $this->get('avatarSrc');
        $file   = pathinfo($avatar);
        $avatar = $file['dirname'] .DS. $file['filename'] .'--32x32.'. $file['extension'];

        return $avatar;
    }

}
