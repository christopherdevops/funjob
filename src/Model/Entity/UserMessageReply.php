<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Core\Configure;


/**
 * UserMessageReply Entity
 *
 * @property int $id
 * @property int $user_message_thread
 * @property int $sender_id
 * @property string $body
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\Sender $sender
 */
class UserMessageReply extends Entity
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

    /**
     * Converte testo in testo più elaborato (emoticon)
     *
     * Vedi config/emoticon.php
     * @return str
     */
    protected function _getBodyFormatted()
    {
        if (empty($this->_properties['body'])) {
            return;
        }

        Configure::load('emoticon');
        $emoticons = Configure::read('emoticon.map');

        return str_replace(
            array_keys($emoticons),
            array_values($emoticons),
            $this->_properties['body']
        );
    }
}
