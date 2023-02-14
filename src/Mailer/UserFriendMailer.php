<?php
namespace App\Mailer;

use App\Model\Entity\User;
use Cake\Mailer\Mailer;

/**
 * UserFriend mailer.
 */
class UserFriendMailer extends Mailer
{

    /**
     * Mailer's name.
     *
     * @var string
     */
    static public $name = 'UserFriend';


    public function newFriendRequest(User $UserRecipient, User $UserRequester)
    {
        $this->setProfile('default');
        $this->setTo($UserRecipient->email, $UserRecipient->username);
        $this->setSubject(__('[funjob.it] Richiesta di amicizia di {username}', ['username' => $UserRequester->username]));

        $this->setLayout('default')
            ->setTemplate('Users/new_friend_request')
            ->setEmailFormat('html')
            ->viewVars(compact('UserRecipient', 'UserRequester'));
    }
}
