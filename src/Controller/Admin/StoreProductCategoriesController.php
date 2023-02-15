<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * StoreProductCategories Controller
 *
 * @property \App\Model\Table\StoreProductCategoriesTable $StoreProductCategories
 */
class StoreProductCategoriesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        //$this->viewBuilder()->enableAutoLayout(false);
        $this->viewBuilder()->setTemplatePath('Admin/Store/StoreProductCategories');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $q = $this->StoreProductCategories->find();
        $q->find('threaded', []);

        $StoreProductCategories = $this->paginate($q);

        $this->set(compact('StoreProductCategories'));
        $this->set('_serialize', ['StoreProductCategories']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $category = $this->StoreProductCategories->newEntity();
        if ($this->request->is('post')) {
            $category = $this->StoreProductCategories->patchEntity($category, $this->request->getData());
            if ($this->StoreProductCategories->save($category)) {
                $this->Flash->success(__d('backend', 'Categoria prodotti creata'));

                return $this->redirect(['action' => 'edit', $category->id]);
            } else {
                $this->Flash->error(__d('backend', 'Categoria prodotti non creata.. Riprova'));
            }
        }

        $parentCategories = $this->StoreProductCategories->ParentStoreCategories->find('treeList', [
            'spacer' => '↳'
        ]);
        $this->set(compact('category', 'parentCategories'));
        $this->set('_serialize', ['category']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Quiz Category id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $category = $this->StoreProductCategories->get($id, [
             'contain' => [],
             'finder'  => 'translations',
             'locales' => ['it', 'en', 'es', 'fr', 'ru']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $category = $this->StoreProductCategories->patchEntity($category, $this->request->data, [
                'translations' => true
            ]);

            if ($this->StoreProductCategories->save($category)) {
                $this->Flash->success(__d('backend', 'Categoria aggiornata'));

                return $this->redirect(['action' => 'edit', $id]);
            } else {
                $this->Flash->error(__d('backend', 'Aggiornamento fallito; tenta nuovamente'));
            }
        }
        $parentCategories = $this->StoreProductCategories->ParentStoreCategories->find('treeList', ['spacer' => '↳']);
        $this->set(compact('category', 'parentCategories'));
        $this->set('_serialize', ['category']);
    }



    /**
     * Delete method
     *
     * @param string|null $id Quiz Category id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $Category    = $this->StoreProductCategories->get((int) $id);
        $descendants = $this->StoreProductCategories->find('children', ['for' => $Category->id])->all();

        $deleted = $this->StoreProductCategories->getConnection()->transactional(
            function () use ($Category, $descendants) {

                $this->StoreProductCategories->deleteOrFail($Category);
                return true;

                // Elimino tutti i figli
                // foreach ($descendants as $descendant) {
                //     //$TreeNode = $this->StoreProductCategories->get($descendant->id);
                //     debug($descendant->id);

                //     try {
                //         $this->StoreProductCategories->deleteOrFail($descendant);
                //     } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                //         debug($this->StoreProductCategories->get($descendant->id));
                //         dd($e->getEntity());
                //         return false;
                //     }
                // }

                // // Elimino categoria
                // $this->StoreProductCategories->deleteOrFail($Category);
                // return true;
            }
        );

        if ($deleted) {
            $this->Flash->success(__('Categoria (e sotto-categorie) eliminate'));
        } else {
            $this->Flash->error(__('Impossibile cancellare la categoria'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
