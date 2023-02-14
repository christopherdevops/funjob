<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Homepages Controller
 */
class LeaderboardsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow();
    }

    public function index()
    {
        //throw new \Cake\Network\Exception\ForbiddenException(__('Prossimamente'));
    }
}
