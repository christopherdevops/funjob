<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

use Cake\I18n\Time;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Cache\Cache;

class UsersController extends AppController
{

    public function beforeFilter(Event $event)
    {
        if (in_array($this->request->action, ['quizzes', 'groups'])) {
            // Imposta variabile vista $User per poter accedere all'utente che si stà visualizzando
            $User = $this->Users->get((int) $this->request->getParam('pass.0'));
            $this->set('UserCurrent', $User);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $q = $this->Users->find();
        $q->orderDesc('id');

        if ($this->request->is('post')) {
            return $this->redirect(['?' => $this->request->getData()]);
        }

        // Filtro: nome e cognome o ragione sociale
        if ($this->request->getQuery('fullname')) {
            $term = $this->request->getQuery('fullname');
            if (strpos($term, '*') === false) { $term = $term. '*'; }

            $q->bind(':fullname', $term, 'string');
            $q->where(['MATCH(first_name, last_name) AGAINST(:fullname IN BOOLEAN MODE)'], ['fullname' => 'string']);
        } elseif ($this->request->getQuery('name')) {
            $term = $this->request->getQuery('name');
            if (strpos($term, '*') === false) { $term = $term. '*'; }

            $q->bind(':company_name', $term, 'string');
            $q->where(['MATCH(name) AGAINST(:company_name IN BOOLEAN MODE)'], ['name' => 'string']);
        }

        if ($this->request->getQuery('email')) {
            $email = filter_var($this->request->getQuery('email'));
            $q->where(['email' => $email], ['email' => 'string']);
        }

        if ($this->request->getQuery('type')) {
            $q->where(['type' => $this->request->getQuery('type')]);
        }

        if ($this->request->getQuery('username')) {
            $q->where(function($exp, $q) {
                return $exp->like('username', '%'. $this->request->getQuery('username') .'%');
            });
        }

        $users = $this->paginate($q, ['limit' => 50]);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $User = $this->Users->get($id, [
            'contain' => [
                'AccountInfos',
                'Credits'
            ]
        ]);

        $this->set(compact('User', 'quizzes'));
    }

    /**
     * Restituisce conteggio quizzes creati dall'utente
     *
     * @param  integer $user_id
     */
    public function quizzes($user_id) {

        // POST 2 GET
        if ($this->request->is('post')) {
            $pass      = $this->request->getParam('pass');
            $pass['?'] = $this->request->getData();
            return $this->redirect($pass);
        }

        $mapper = function ($quiz, $key, $mapReduce) {
            $status = null;

            if ($quiz['is_disabled']) {
                $status = 'disabled';
            } else {
                $status = $quiz['status'];
            }

            $mapReduce->emitIntermediate($quiz['id'], $status);
        };

        $reducer = function ($quizzes, $status, $mapReduce) {
            $mapReduce->emit(sizeof($quizzes), $status);
        };

        $q = $this->Users->Quizzes->find();
        $q->find('byAuthor',  ['user_id' => $user_id]);
        $q->find('withQuestionsCounter');

        if ($this->request->getQuery('status')) {
            $q->find('byStatus', ['status' => $this->request->getQuery('status')]);
        }

        if ($this->request->getQuery('type')) {
            $q->find('byType', ['type' => $this->request->getQuery('type')]);
        }

        if ($this->request->getQuery('term')) {
            $q->find('searchByTerm', [
                'term' => $this->request->getQuery('term'),
                'tags' => true
            ]);
        }


        $q->contain([
            'Categories'
        ]);
        $q->orderDesc('Quizzes.id');

        $q->select($this->Users->Quizzes);
        $quizzes  = $this->paginate($q, ['limit' => 40]);


        // Quizzes counter
        $qStatuses = $this->Users->Quizzes->find();
        $qStatuses->find('byAuthor',  ['user_id' => $user_id]);
        $qStatuses->select(['id', 'title', 'status', 'is_disabled']);
        $statuses = $qStatuses->hydrate(false)->mapReduce($mapper, $reducer)->all();

        $this->set(compact('statuses', 'quizzes'));
        $this->set('_serialize', ['status', 'quizzes']);
    }

    public function  groups($user_id)
    {
        // POST 2 GET
        if ($this->request->is('post')) {
            $pass      = $this->request->getParam('pass');
            $pass['?'] = $this->request->getData();
            return $this->redirect($pass);
        }

        $this->loadModel('UserGroups');
        $this->loadComponent('Paginator');

        $q = $this->UserGroups->find();
        $q->where(['user_id' => $user_id], ['user_id' => 'integer']);
        $q->orderDesc('UserGroups.id');

        if ($this->request->getQuery('term')) {
            $q->find('search', ['name' => $this->request->getQuery('term')]);
        }

        $entities = $this->Paginator->paginate($q);
        $this->set(compact('entities'));
    }

    /**
     * Esporta utenti attivi in CSV
     */
    public function export($type = 'user') {
        $this->viewBuilder()->className('CsvView.Csv');

        switch($type) {
            case 'company':
                $this->loadModel('Companies');
                $q = $this->Companies->find();
                $q->find('withAccountInfo');

                $q->select(['id', 'username', 'name', 'email', 'AccountInfos.address', 'AccountInfos.city', 'AccountInfos.url']);
                $_header  = ['Id', 'Username', 'Ragione Sociale', 'E-mail', 'Telefono', 'Indirizzo', 'Città', 'Sito web'];
                $_extract = [
                    'id', 'username', 'name', 'email',
                    function($row) { return isset($row['account_info']['phone']) ? $row['account_info']['phone'] : ''; },
                    function($row) { return $row['account_info']['address']; },
                    function($row) { return $row['account_info']['city']; },
                    function($row) { return $row['account_info']['url']; }
                ];
            break;

            default:
                $this->loadModel('Users');

                $q = $this->Users->find();
                $q->find('withAccountInfo');

                $q->select([
                    'id', 'username', 'first_name', 'last_name', 'email',
                    'AccountInfos.phone', 'AccountInfos.address', 'AccountInfos.live_city', 'AccountInfos.sex', 'AccountInfos.birthday', 'AccountInfos.phone'
                ]);
                $_header  = ['Id', 'Username', 'Nome', 'Cognome', 'E-mail', 'Telefono', 'Indirizzo', 'Città', 'Sesso', 'Data di nascita'];
                $_extract = [
                    'id', 'username', 'first_name', 'last_name', 'email',
                    function($row) { return $row['account_info']['phone']; },
                    function($row) { return $row['account_info']['address']; },
                    function($row) { return $row['account_info']['live_city']; },
                    function($row) { return $row['account_info']['sex']; },
                    function($row) { return $row['account_info']['phone']; }
                ];
        }

        $users      = $q->hydrate(false)->all();
        $_serialize = ['users'];
        $_delimiter = ';';

        $this->set(compact('users', '_header', '_delimiter', '_extract', '_serialize'));
    }


    /**
     * Flag "can_logon" necessaria per effettaure il login
     *
     * @param  integer $user_id
     */
    public function canlogon($user_id)
    {
        $this->request->allowMethod('PUT');

        $User = $this->Users->get($user_id);
        $User = $this->Users->patchEntity($User, $this->request->getData(), [
            'fieldList' => ['can_logon', 'is_disabled', 'type'],
            'validate'  => 'canLogonFlag'
        ]);

        if ($this->Users->save($User)) {
            if ($User->can_logon) {
                $this->Flash->success(__d('backend', '{username} può accedere a funjob con ruolo di {role}', [
                    'username' => $User->username,
                    'role'     => $User->type
                ]));
            } else {
                $this->Flash->success(__d('backend', '{username} non potrà più accedere a funjob', ['username' => $User->username]));
            }
        } else {
            $this->Flash->error(__d('backend', 'Impossibile aggiornare utente'));
        }

        return $this->redirect($this->referer());
    }

    /**
     * Assegna flag is_bigbrain
     *
     * @param int $id [description]
     */
    public function bigbrain($user_id)
    {
        $this->request->allowMethod('PUT');

        $User = $this->Users->get($user_id);
        $User = $this->Users->patchEntity($User, $this->request->getData(), [
            'fieldList' => ['is_bigbrain', 'bigbrain_from'],
            'validate'  => 'bigbrainFlag'
        ]);

        if ($User->is_bigbrain) {
            $User->bigbrain_from = Time::now();
        } else {
            $User->bigbrain_from = null;
        }

        if ($this->Users->save($User)) {
            if ($User->is_bigbrain) {
                $this->Flash->success(__d('backend', '{username} è ora un bigbrain', ['username' => $User->username]));
            } else {
                $this->Flash->success(__d('backend', '{username} non è più un bigbrain', ['username' => $User->username]));
            }
        } else {
            $this->Flash->error(__d('backend', 'Impossibile aggiornare bigbrain'));
        }

        return $this->redirect($this->referer());
    }

    public function clearCache()
    {
        Cache::delete('home_latests_users', 'home_latests_users');
        Cache::delete('home_latests_companies', 'home_latests_companies');

        $this->Flash->success(__('Cache homepage (utenti, aziende) eliminata'));
        return $this->redirect($this->referer());
    }

}
