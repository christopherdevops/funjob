<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SocialUser Entity
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $tag
 *
 * @property \App\Model\Entity\SocialUser $quiz
 */
class SocialUser extends Entity
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
        '*'     => true,
        'id'    => false
    ];

    protected function _getEmail($email)
    {
        $email = $this->_fields['email'];

        if (!empty($this->_fields['email_verified'])) {
            $email = 'verified::' . $this->_fields['email_verified'];
        }

        return $email;
    }
}
