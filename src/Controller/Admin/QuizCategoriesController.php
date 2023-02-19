<?php
namespace App\Controller\Admin;

use Cake\ORM\TableRegistry;
use Cake\Log\Log;

use App\Controller\Admin\AppController;

/**
 * QuizCategories Controller
 *
 * @property \App\Model\Table\QuizCategoriesTable $QuizCategories
 */
class QuizCategoriesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->Categories = TableRegistry::get('QuizCategories');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $q = $this->QuizCategories->find();
        $q->find('threaded', []);
        $quizCategories = $q->all();

        $this->set(compact('quizCategories'));
        $this->set('_serialize', ['quizCategories']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $Category = $this->Categories->newEmptyEntity();
        if ($this->request->is('post')) {

            if ($this->request->getData('_filter')) {
                return $this->redirect(['?' => $this->request->getData()]);
            }

            $Category = $this->Categories->patchEntity($Category, $this->request->data);
            if ($this->Categories->save($Category)) {
                $this->Flash->success(__d('backend', 'Categoria creata'));
                return $this->redirect(['action' => 'edit', $Category->id]);
            } else {
                $this->Flash->error(__('Categoria non creata.. riprova'));
            }
        }


        if ($this->request->getQuery('children', null)) {
            $parent = (int) $this->request->getQuery('children');
            $paths  = $this->Categories->find('path', ['for' => $parent])->all();
            $nodes  = $this->Categories->find()->where(['parent_id' => $parent])->all();
        } elseif ($this->request->getQuery('term')) {
            $q       = $this->QuizCategories->find();
            $nodes = $q
                ->select(['name', 'id', 'parent_id'])
                ->bind(':term', '%' .$this->request->query('term'). '%')
                ->where(['name LIKE :term'])
                ->limit(100)
            ->all();

            $paths = null;
            foreach ($nodes as $node) {
                $path = $this->Categories->find('path', ['for' => $node->id])->find('list')->toArray();
                $node->name = implode(' > ', $path);
            }
        } else {
            $nodes = $this->Categories->find()->where(['level' => 0])->all();
            $paths = null;
        }

        $this->set(compact('nodes', 'paths'));


        // $parentQuizCategories = $this->QuizCategories->ParentQuizCategories->find('treeList', [
        //     'spacer' => '↳'
        // ]);

        $this->set(compact('Category', 'parentQuizCategories'));
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
        $quizCategory = $this->QuizCategories->get($id, [
             'contain' => [],
             'finder'  => 'translations',
             'locales' => ['it', 'en', 'es', 'fr', 'ru']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $quizCategory = $this->QuizCategories->patchEntity($quizCategory, $this->request->data, [
                'translations' => true
            ]);

            if ($this->QuizCategories->save($quizCategory)) {
                $this->Flash->success(__d('backend', 'Categoria quiz aggiornata'));

                return $this->redirect(['action' => 'edit', $id]);
            } else {
                $this->Flash->error(__('Aggiornamento fallito; tenta nuovamente'));
            }
        }
        $parentQuizCategories = $this->QuizCategories->ParentQuizCategories->find('treeList', [
            'spacer' => '↳'
        ]);
        $this->set(compact('quizCategory', 'parentQuizCategories'));
        $this->set('_serialize', ['quizCategory']);
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

        $Category    = $this->Categories->get((int) $id);
        $descendants = $this->Categories->find('children', ['for' => $Category->id])->all();
        $ids         = [];

        //dd($descendants);

        $deleted = $this->Categories->getConnection()->transactional(
            function () use ($Category, $descendants) {

                // Elimino tutti i figli
                foreach ($descendants as $descendant) {
                    $ids[] = $descendant->id;

                    $this->Categories->deleteOrFail($descendant);
                    // Log::debug(__('Cancellata categoria: {id} discendente di {parent_id}', [
                    //     'id'        => $descendant->id,
                    //     'parent_id' => $Category->id
                    // ]));
                }

                // Elimino categoria
                $ids[] = $Category->id;
                $this->Categories->deleteOrFail($Category);
                // Log::debug(__('Cancellata categoria: {id}', [
                //     'id' => $Category->id
                // ]));

                return true;
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
