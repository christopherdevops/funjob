<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UserGroups Controller
 *
 * @property \App\Model\Table\UserGroupsTable $UserGroups
 */
class UserGroupsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        // $this->loadComponent('Csrf');

        $this->Auth->allow(['index', 'view', 'search', 'members']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $q = $this->UserGroups->find('latests');
        //$q->find('membersCount');

        $userGroups = $this->paginate($q, ['limit' => 30]);

        $this->set(compact('userGroups'));
        $this->set('_serialize', ['userGroups']);
    }

    /**
     * View method
     *
     * @param string|null $id User Group id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userGroup = $this->UserGroups->get($id, [
            'contain' => [
                'Administrators',

                // 'Administrators.Users' => function($q) {
                //     return $q->select(['id', 'username']);
                // },

                'Members' => function($q) {
                    $q->select(['id', 'username', 'first_name', 'last_name', 'avatar']);
                    $q->limit(5);
                    $q->orderAsc('Members.id');
                    return $q;
                }
            ]
        ]);

        $joined = false;
        if ($this->Auth->user('id')) {
            $joined = $this->UserGroups->UserGroupMembers->find('byUserAndGroup', [
                'user_id'  => $this->Auth->user('id'),
                'group_id' => $id
            ])->first();
        }

        $this->set(compact('userGroup', 'joined'));
        $this->set('_serialize', ['userGroup', 'joined']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userGroup = $this->UserGroups->newEntity();

        if ($this->request->is('post')) {
            // Inject data
            $this->request->data('user_id', $this->Auth->user('id'));
            $this->request->data('user_group_members.0', [
                'user_id' => $this->Auth->user('id'),
                'role'    => 'owner'
            ]);

            $userGroup = $this->UserGroups->patchEntity($userGroup, $this->request->getData(), [
                'associated' => ['UserGroupMembers']
            ]);

            if ($this->UserGroups->save($userGroup)) {
                $this->Flash->success(__('Gruppo creato: ora puoi assegnare una foto o delle parole chiavi'));
                return $this->redirect(['action' => 'edit', $userGroup->id]);
            }

            $this->Flash->error(__('Impossibile procedere, verifica che i campi siano corretti.'));
        }

        $scopes = [];
        foreach (\Cake\Core\Configure::read('usergroup.scopes') as $item) {

            if (empty($item['group'])) {
                $scopes[ $item['value'] ] = $item['text'];
                continue;
            }

            $scopes[ $item['group'] ][ $item['value'] ] = $item['text'];
        }

        $this->set(compact('userGroup', 'scopes'));
        $this->set('_serialize', ['userGroup']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User Group id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userGroup = $this->UserGroups->get($id, [
            'contain' => [
                'Owners'
            ]
        ]);

        if ($this->Auth->user('type') != 'admin' && $userGroup->owner->id !== $this->Auth->user('id')) {
            throw new \Cake\Network\Exception\ForbiddenException();
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $userGroup = $this->UserGroups->patchEntity($userGroup, $this->request->getData(), [
                'fieldList' => ['descr', 'image', 'keywords']
            ]);

            if ($this->UserGroups->save($userGroup)) {
                $this->Flash->success(__('Gruppo aggiornato'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Impossibile proseguire, verifica che tutti i campi siano privi di errore.'));
        }

        $this->set(compact('userGroup', 'scopes'));
        $this->set('_serialize', ['userGroup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User Group id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userGroup = $this->UserGroups->get($id);

        if ($userGroup->user_id != $this->Auth->user('id')) {
            $this->Flash->error(__('Permesso negato'));
            return $this->redirect($this->referer());
        }

        if ($this->UserGroups->delete($userGroup)) {
            $this->Flash->success(__('Eliminato con successo.'));
        } else {
            $this->Flash->error(__('Eliminzione fallita.'));
        }

        return $this->redirect($this->referer());
    }

    /**
     * Entra nel gruppo
     */
    public function join()
    {
        $this->request->allowMethod(['post']);

        $entityData = [
            'user_id'  => $this->Auth->user('id'),
            'group_id' => $this->request->getData('id')
        ];

        $entity = $this->UserGroups->UserGroupMembers->find('ByUserAndGroup', $entityData)->first();

        if (!$entity) {
            $entity = $this->UserGroups->UserGroupMembers->newEntity($entityData);
            $this->UserGroups->UserGroupMembers->saveOrFail($entity);
            $this->Flash->success(__('Sei entrato nel gruppo'));
        }

        return $this->redirect($this->referer(['_name' => 'groups:archive']));
    }

    /**
     * Esce dal gruppo
     */
    public function leave()
    {
        $this->request->allowMethod(['post']);

        $q = $this->UserGroups->UserGroupMembers->find('byUserAndGroup', [
            'user_id'  => $this->Auth->user('id'),
            'group_id' => $this->request->getData('id')
        ]);

        $entity = $q->first();

        if ($entity) {
            $this->UserGroups->UserGroupMembers->deleteOrFail($entity);
            $this->Flash->success(__('Sei uscito dal gruppo'));
        }

        return $this->redirect($this->referer(['_name' => 'groups:archive']));
    }

    /**
     * Ricerca gruppi tramite parola chiave
     *
     * @return \Cake\Network\Response
     */
    public function search()
    {
        $q = $this->UserGroups->find();

        try {
            $q->find('search', [
                'name' => $this->request->getQuery('name')
            ]);
        } catch (\RuntimeException $e) {
            return $this->redirect(['action' => 'index']);
        }

        $userGroups = $this->paginate($q, ['limit' => 30]);
        $isSearch   = true;

        $this->set(compact('userGroups', 'isSearch'));

        $this->render('index');
    }

    public function members($id)
    {
        $userGroup = $this->UserGroups->get($id, [
            'contain' => [
                'Members' => function($q) {
                    //$q->find('isActive');
                    $q->select(['id', 'username', 'first_name', 'last_name', 'title', 'avatar']);
                    return $q;
                }
            ]
        ]);

        $this->set(compact('userGroup'));
        $this->set('_serialize', ['userGroup']);
    }

}
