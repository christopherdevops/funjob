<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * QuizCategories Controller
 *
 * @property \App\Model\Table\QuizCategoriesTable $QuizCategories
 */
class CompanyCategoriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $q = $this->CompanyCategories->find();
        $q->find('threaded', []);

        $categories = $this->paginate($q);

        $this->set(compact('categories'));
        $this->set('_serialize', ['categories']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $Category = $this->CompanyCategories->newEntity();
        if ($this->request->is('post')) {
            $Category = $this->CompanyCategories->patchEntity($Category, $this->request->getData());
            if ($this->CompanyCategories->save($Category)) {
                $this->Flash->success(__d('backend', 'Categoria creata'));
                return $this->redirect(['action' => 'edit', $Category->id]);
            }
        }

        $parents = $this->CompanyCategories->Parents->find('treeList', ['spacer' => '-', 'limit' => 200]);
        $this->set(compact('Category', 'parents'));
        $this->set('_serialize', ['Category']);
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
        $Category = $this->CompanyCategories->get($id, [
             'contain' => [],
             'finder'  => 'translations',
             'locales' => ['it', 'en', 'es', 'fr', 'ru']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $Category = $this->CompanyCategories->patchEntity($Category, $this->request->data, [
                'translations' => true
            ]);

            if ($this->CompanyCategories->save($Category)) {
                $this->Flash->success(__d('backend', 'Categoria aggiornata'));
                return $this->redirect(['action' => 'edit', $id]);
            } else {
                $this->Flash->error(__d('backend', 'Aggiornamento fallito; tenta nuovamente'));
            }
        }

        $parents = $this->CompanyCategories->Parents
            ->find('treeList', ['spacer' => '-', 'limit' => 200])
            ->where(function($exp, $q) use ($id) {
                return $exp->notEq('id', $id);
            });

        $this->set(compact('Category', 'parents'));
        $this->set('_serialize', ['Category']);
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

        $Category = $this->CompanyCategories->get($id);
        if ($this->CompanyCategories->delete($Category)) {
            $this->Flash->success(__d('backend', 'Categoria eliminata'));
        } else {
            $this->Flash->error(__d('backend', 'Impossibile eliminare categoria'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
