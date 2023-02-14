<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;
use Cake\Collection\Collection;

/**
 * HomeRandomBigBrains cell
 */
class HomeRandomBigBrainsCell extends Cell
{
    const MAX_USERS = 12;

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

        $fields = ['id', 'username', 'email', 'title', 'avatar'];
        $vip     = $this->Users->find('bigbrainHome')->select($fields)->all();
        $vip_ids = $vip->extract('id')->toList();

        $users = Cache::remember('home_random_bigbrains', function() use ($fields, $vip_ids) {
            $q = $this->Users->find('bigbrain');

            $q->select($fields);
            $q->order(['RAND()']);

            if (!empty($vip_ids)) {
                $q->where(function($exp, $q) use ($vip_ids) {
                    return $exp->notIn('Users.id', $vip_ids);
                });
            }

            $q->limit( self::MAX_USERS - sizeof($vip_ids));

            return $q->all();
        }, 'home_random_bigbrains');

        $users = $users->toArray();
        foreach ($vip as $User) {
            $users[] = $User;
        }

        $users = array_reverse($users);
        //$users = new Collection($users);

        $this->set('bigbrains', $users);
    }
}
