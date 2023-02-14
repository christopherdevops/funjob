<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * MyAccounts Controller
 *
 * @property \App\Model\Table\MyAccountsTable $MyAccounts
 */
class MyAccountsController extends AppController
{

    public function initialize() {
        parent::initialize();

        $this->loadComponent('Paginator');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
    }

    public function quizzes()
    {
        $this->loadModel('Quizzes');
        $query   = $this->Quizzes->find();
        $query->where(['user_id' => $this->Auth->user('id')]);

        $quizzes = $this->Paginator->paginate($query, ['limit' => 10]);

        $this->set(compact('quizzes'));
        $this->set('_serialize', ['quizzes']);
    }
}
