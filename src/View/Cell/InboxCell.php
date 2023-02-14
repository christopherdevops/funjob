<?php
namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Inbox cell
 */
class InboxCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [
        'user_id'
    ];

    /**
     * Messaggi non letti
     *
     * @return void
     */
    public function unreadCount($user_id = null)
    {
        $this->loadModel('UserMessageRecipients');

        $messages_unread_count = $this->UserMessageRecipients
            ->find('unread', [
                'user_id' => $user_id
            ])
            ->count();

        $this->set('messages_unread', $messages_unread_count);
    }
}
