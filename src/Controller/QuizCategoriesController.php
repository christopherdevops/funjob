<?php
namespace App\Controller;

use App\Controller\AppController;

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
        $this->Auth->allow();
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $tree = $this->_generateTreeBootstrapTreeView();
        // $tree = $this->QuizCategories->find('path', [
        //     'spacer'    => '- ',
        //     'keyPath'   => 'id',
        //     'valuePath' => 'name',
        //     'for'       => null
        // ]);
        $tree = $this->QuizCategories->find('treeList')->where(['level' => 0])->toArray();

        $this->set(compact('quizCategories', 'tree'));
        $this->set('_serialize', ['quizCategories']);
    }

    /**
     * Ricerca categorie in base al nome
     */
    public function search()
    {
        if (empty($this->request->getQuery('term'))) {
            throw new \App\Exceptions\Http\ForbiddenException();
        }

        $Query   = $this->QuizCategories->find();
        $results = $Query
            ->select(['name', 'id', 'parent_id'])
            ->where(['name LIKE' => '%' .$this->request->getQuery('term'). '%'])
            ->where(['parent_id IS NOT NULL'])
            ->limit(100)
        ->all();

        foreach ($results as $result) {
            $paths = $this->QuizCategories->find('path', ['for' => $result->id])->find('list')->toArray();
            $result->name = implode(' > ', $paths);
        }

        $this->set(compact('results'));
        $this->set('_serialize', ['results']);
    }

    /**
     * Restituisce i figli di una determinata categoria
     *
     * Utilizzata per creare gli options tramite ajax per le selects nella pagina "Gioca"
     *
     * @param  int $parent_id
     * @return array
     */
    public function childrens($parent_id)
    {
        $childrens = $this->QuizCategories
            ->find('children', ['for' => $parent_id])
            ->find('threaded')
            //->where(['parent_id' => $parent_id])
        ;
        $this->set(compact('childrens'));
        $this->set('_serialize', ['childrens']);
    }

    /**
     * Redirige l'utente verso l'archivio selezionato tramite pagina "Gioca"
     */
    public function to()
    {
        $this->autoRender = false;

        if (!$this->request->is('post')) {
            throw new \NotImplementedException();
        }

        $id = false;
        foreach ($this->request->getData('category_id') as $level => $category_id) {
            if (empty($category_id)) {
                break;
            }

            $id = $category_id;
        }

        if (!empty($id)) {
            $QuizCategory = $this->QuizCategories->findById($id)->select(['id', 'name'])->first();

            if (!empty($QuizCategory)) {
                return $this->redirect([
                    '_name' => 'quiz-categories:browse',
                    'id'    => $QuizCategory->id,
                    'title' => \Cake\Utility\Text::slug($QuizCategory->name)
                ]);
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * View method
     *
     * @param string|null $id Quiz Category id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $quizCategory = $this->QuizCategories->get($id, [
            'contain' => ['ParentQuizCategories', 'ChildQuizCategories']
        ]);

        $this->set('quizCategory', $quizCategory);
        $this->set('_serialize', ['quizCategory']);
    }


    /**
     * Crea stuttura "tree" compatibile con plugin Twitter bootstrap-treeview
     *
     * Vedi blocco "quiz-category--tree" su /Views/QuizCategories/index.ctp
     *
     * @return array
     */
    private function _generateTreeBootstrapTreeView()
    {
        $quizCategories = $this->QuizCategories->find('threaded');
        $tree           = [];

        $Iterator = new \RecursiveIteratorIterator(
            new \App\Lib\TreeIterator($quizCategories->toArray()),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($Iterator as $quizCategory)
        {
            $quizCategory->text  = $quizCategory->name;
            $quizCategory->nodes = $quizCategory->children;
            $quizCategory->href  = 'javascript:void(0);';

            if (empty($quizCategory->children)) {
                $quizCategory->href = '/quizzes/browse/' . $quizCategory->id;
                unset($quizCategory->nodes);
            }

            // Rimuove chiavi non utili
            unset($quizCategory->name, $quizCategory->lft, $quizCategory->rght, $quizCategory->parent_id, $quizCategory->children);
        }

        $tree = $quizCategories->toArray();
        return $tree;
    }

}
