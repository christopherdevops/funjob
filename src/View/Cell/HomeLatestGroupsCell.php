<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;

/**
 * HomeLatestGroups cell
 */
class HomeLatestGroupsCell extends Cell
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
        $this->loadModel('UserGroups');

        $groups = Cache::remember('home_latests_groups', function() {
            // Ultimi gruppi creati
            //$q = $this->UserGroups->find('latests');

            // Richiesta del 15 novembre: mostrare gruppi randomici (per questo momento)
            $q = $this->UserGroups->find();

            return $q->order(['RAND()'])->limit(7)->all();
        }, 'home_latests_groups');

        $this->set(compact('groups'));
    }
}
