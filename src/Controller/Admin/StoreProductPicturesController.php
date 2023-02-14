<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * StoreProductPictures Controller
 *
 * @property \App\Model\Table\StoreProductPicturesTable $StoreProductPictures
 */
class StoreProductPicturesController extends AppController
{

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ProductPicture = $this->StoreProductPictures->newEntity();

        // Forzo "product_id" poichè è ignorato dal guard dell'entity
        $ProductPicture->set(['product_id' => $this->request->getData('product_id')], ['guard' => false]);

        if ($this->request->is('post')) {
            $ProductPicture = $this->StoreProductPictures->patchEntity($ProductPicture, $this->request->getData());
            if ($this->StoreProductPictures->save($ProductPicture)) {
                $this->Flash->success(__('Foto caricata'));
            } else {
                $this->Flash->error(__('Impossibile caricare foto'));
            }

            return $this->redirect([
                'admin'      => true,
                'controller' => 'StoreProducts',
                'action'     => 'edit',
                0            => $this->request->getData('product_id'),
                '#'          => 'pictures'
            ]);
        }

        $products = $this->StoreProductPictures->Products->find('list', ['limit' => 200]);
        $this->set(compact('storeProductPicture', 'products'));
        $this->set('_serialize', ['storeProductPicture']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Store Product Picture id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ProductPicture = $this->StoreProductPictures->get($id);

        if ($this->StoreProductPictures->delete($ProductPicture)) {
            $this->Flash->success(__('Foto eliminata'));
            return $this->redirect([
                'prefix' => 'admin', 'controller' => 'StoreProducts', 'action' => 'edit',
                0 => $ProductPicture->product_id, '#' => 'pictures'
            ]);
        }

        return $this->redirect($this->referer('/'));
    }
}
