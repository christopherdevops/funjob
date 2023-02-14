<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;

/**
 * HomeCompanyLatests cell
 */
class HomeCompaniesLatestsCell extends Cell
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
    public function display()
    {
        $this->loadModel('Users');

        $users = Cache::remember('home_latests_companies', function() {
            $q = $this->Users->find('company');
            $q->select(['id', 'username', 'email', 'title', 'is_bigbrain', 'avatar']);
            $q->order(['created' => 'DESC', 'id' => 'DESC']);
            $q->contain([]);
            return $q->limit(18)->all();
        }, 'home_latests_companies');

        $this->set('users', $users);
    }
}
