<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserIgnoreList Entity
 *
 * @property int $id
 * @property int $recipient_user_id
 * @property int $ignored_user_id
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\RecipientUser $recipient_user
 * @property \App\Model\Entity\IgnoredUser $ignored_user
 */
class UserIgnoreList extends Entity
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
}
