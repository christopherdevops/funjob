<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\ORM\TableRegistry;

/**
 * UserFriendsRequests cell
 */
class UserFriendsRequestsCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($user_id)
    {
        $counter = null;

        //if ($this->request->session()->check('Auth.User')) {
        if ($user_id) {
            $UserFriends = TableRegistry::get('UserFriends');
            $q = $UserFriends->find(); // ('waitingPending');
            $q->where([
                'friend_id'    => $user_id,
                'is_requester' => true,
                'is_accepted'  => false,
            ]);
            $counter = $q->count();
        }

        $this->set(compact('counter'));
    }
}
