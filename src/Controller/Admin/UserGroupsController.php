<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

use Cake\Cache\Cache;

/**
 * UserGroups Controller
 *
 * @property \App\Model\Table\UserGroupsTable $UserGroups
 *
 * @method \App\Model\Entity\UserGroup[] paginate($object = null, array $settings = [])
 */
class UserGroupsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $q = $this->UserGroups->find();
        $q->contain(['Owners']);

        //$q->orderDesc('UserGroups.id');

        $entities = $this->paginate($q);

        $this->set(compact('entities'));
        $this->set('_serialize', ['entities']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User Group id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userGroup = $this->UserGroups->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $userGroup = $this->UserGroups->patchEntity($userGroup, $this->request->getData());
            if ($this->UserGroups->save($userGroup)) {
                $this->Flash->success(__('Aggiornamento completato.'));
                return $this->redirect($this->referer());
            }
        }

        $this->set(compact('userGroup'));
        $this->set('_serialize', ['userGroup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User Group id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userGroup = $this->UserGroups->get($id);

        if ($this->UserGroups->delete($userGroup)) {
            $this->Flash->success(__('Eliminato con successo'));
        } else {
            $this->Flash->error(__('Eliminazione non riuscita'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function clearCache()
    {
        Cache::delete('home_latests_groups', 'home_latests_groups');
        $this->Flash->success(__('Cache eliminata'));

        return $this->redirect($this->referer());
    }

}
