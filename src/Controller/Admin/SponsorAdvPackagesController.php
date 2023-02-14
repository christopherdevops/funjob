<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * SponsorAdvPackages Controller
 *
 * @property \App\Model\Table\SponsorAdvPackagesTable $SponsorAdvPackages
 *
 * @method \App\Model\Entity\SponsorAdvPackage[] paginate($object = null, array $settings = [])
 */
class SponsorAdvPackagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $type = $this->request->getQuery('type', 'banner');
        $q    = $this->SponsorAdvPackages->find();

        $q->where(['type' => $type], ['type' => 'string']);
        $q->order(['SponsorAdvPackages.price']);

        $packages = $this->paginate($q);

        $this->set(compact('packages'));
        $this->set('_serialize', ['packages']);
    }

    /**
     * View method
     *
     * @param string|null $id Sponsor Adv Package id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $SponsorAdvPackage = $this->SponsorAdvPackages->get($id, [
            'contain' => []
        ]);

        $this->set('SponsorAdvPackage', $sponsorAdvPackage);
        $this->set('_serialize', ['SponsorAdvPackage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $SponsorAdvPackage = $this->SponsorAdvPackages->newEntity();
        if ($this->request->is('post')) {
            $SponsorAdvPackage = $this->SponsorAdvPackages->patchEntity($SponsorAdvPackage, $this->request->getData());
            if ($this->SponsorAdvPackages->save($SponsorAdvPackage)) {
                $this->Flash->success(__('The sponsor adv package has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sponsor adv package could not be saved. Please, try again.'));
        }
        $this->set(compact('SponsorAdvPackage'));
        $this->set('_serialize', ['SponsorAdvPackage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sponsor Adv Package id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $SponsorAdvPackage = $this->SponsorAdvPackages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $SponsorAdvPackage = $this->SponsorAdvPackages->patchEntity($SponsorAdvPackage, $this->request->getData());
            if ($this->SponsorAdvPackages->save($SponsorAdvPackage)) {
                $this->Flash->success(__('The sponsor adv package has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sponsor adv package could not be saved. Please, try again.'));
        }
        $this->set(compact('SponsorAdvPackage'));
        $this->set('_serialize', ['SponsorAdvPackage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sponsor Adv Package id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sponsorAdvPackage = $this->SponsorAdvPackages->get($id);
        if ($this->SponsorAdvPackages->delete($sponsorAdvPackage)) {
            $this->Flash->success(__('The sponsor adv package has been deleted.'));
        } else {
            $this->Flash->error(__('The sponsor adv package could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
