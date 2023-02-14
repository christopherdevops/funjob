<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;

/**
 * HomeUserLatests cell
 */
class HomeUserLatestsCell extends Cell
{
    const MAX_USERS = 18;

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
    public function display()
    {
        $this->loadModel('Users');

        $users = Cache::remember('home_latests_users', function() {
            $q = $this->Users->find('user');
            $q->find('isActive');

            // NOTE: non più utilizzato al momento
            //$q->find('withAccountInfo');

            $q->select(['id', 'username', 'email', 'first_name', 'last_name', 'title', 'is_bigbrain', 'avatar']);
            $q->order(['id' => 'DESC']);
            return $q->limit(self::MAX_USERS)->all();
        }, 'home_latests_users');

        $this->set('users_latest_registred', $users);
    }
}
