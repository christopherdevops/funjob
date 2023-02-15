<?php
namespace App\Controller\User;

use App\Controller\AppController;

/**
 * UserIgnoreLists Controller
 *
 * @property \App\Model\Table\UserIgnoreListsTable $UserIgnoreLists
 */
class UserIgnoreListsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->Auth->deny(['index', 'add', 'delete']);
    }

    public function getAdvertising()
    {
        return [];
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['IgnoredUsers']
        ];
        $userIgnoreLists = $this->paginate($this->UserIgnoreLists);

        $this->set(compact('userIgnoreLists'));
        $this->set('_serialize', ['userIgnoreLists']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);

        $userIgnoreList = $this->UserIgnoreLists->newEntity();
        if ($this->request->is('post')) {
            $userIgnoreList = $this->UserIgnoreLists->patchEntity($userIgnoreList, $this->request->getData());
            if ($this->UserIgnoreLists->save($userIgnoreList)) {
                $this->Flash->success(__('Hai aggiunto questo utente nella lista ignorati'));
                return $this->redirect($this->referer(['action' => 'index']));
            }

            $errors = $userIgnoreList->errors('ignore_user_id');
            if (in_array('_isUnique', array_flip($errors))) {
                $this->Flash->error($errors['_isUnique']);
            } else {
                $this->Flash->error(__('Impossibile ignorare utente'));
            }

            return $this->redirect($this->referer(['action' => 'index']));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User Ignore List id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userIgnoreList = $this->UserIgnoreLists->get($id);
        if ($this->UserIgnoreLists->delete($userIgnoreList)) {
            $this->Flash->success(__('The user ignore list has been deleted.'));
        } else {
            $this->Flash->error(__('The user ignore list could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
