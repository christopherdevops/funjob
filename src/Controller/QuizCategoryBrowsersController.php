<?php
namespace App\Controller;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

use App\Controller\AppController;
use App\Form\BigBrainContactForm;

/**
 * Bigbrains Controller
 *
 * @property \App\Model\Table\BigbrainsTable $Bigbrains
 */
class QuizCategoryBrowsersController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->Auth->allow();

        $this->loadComponent('Paginator');
        $this->Categories = TableRegistry::get('QuizCategories');
        $this->Quizzes = TableRegistry::get('Quizzes');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($node = null)
    {
        $isRoot = $node === null;
        $this->_post2get();

        $results       = new \Cake\Collection\Collection([]);
        $Category      = new \Cake\Collection\Collection([]);

        $qCategories   = $this->Categories->find();
        $qResults      = $this->Quizzes->find('published');

        $useFilters    = false; // se vengono utilizzati filtri
        $hasResults    = false; // se vengono ricercati quizzes
        $SubCategories = 0; // Se il nodo di categoria corrente ha sotto nodi (NON PIU' UTILIZZATO)

        if (empty($node)) {
            $qCategories->where(['level' => 0]);
            $qCategories->where(function($exp, $q) {
                return $exp->isNull('parent_id');
            });
        } else {
            $Category = $this->Categories->find('path', ['for' => $node])->toArray();
            //$SubCategories = $this->Categories->find()->where(['parent_id' => $node])->count();
            $qCategories->where(['parent_id' => (int) $node]);

            // Quizzes solo per ultimo nodo del ramo (ottimizzazione)
            // In fase di creazione quiz è possibile selezionare solo ultimi nodi
            //if ($SubCategories === 0) {
                $results    = $this->_quizzesByCategory($qResults);
                $useFilters = $this->request->getQuery('do') == 'search';
                $hasResults = $results->count();
            //}
        }

        $categories = $this->Paginator->paginate($qCategories, ['limit' => 999]);

        $this->set(compact(
            'categories', 'Category', 'SubCategories', 'results',
            'isRoot', 'useFilters', 'hasResults'
        ));
    }

    /**
     * Redirect POST -> GET
     */
    private function _post2get()
    {
        if ($this->request->is('post')) {
            $url = $this->request->here. '?';

            foreach ($this->request->getData() as $key => $value) {
                $url .= '&' . $key .'='. $value;
            }
            return $this->redirect($url);
        }
    }

    private function _quizzesByCategory(\Cake\ORM\Query $q)
    {
        $pass = $this->request->getParam('pass');
        $node = $pass[0];

        $childrens = $this->Categories->find('children', ['for' => $node])
            ->select(['id'])
            ->hydrate(false)
            ->extract('id')->toArray();

        $childrens[] = $node;

        // Quizzes solo per ultimo nodo del ramo (ottimizzazione)
        // In fase di creazione quiz è possibile selezionare solo ultimi nodi
        //$q->find('byCategory', ['category_id' => $node]);
        $q->find('byCategory', ['category_id' => $childrens]);
        $q->group('Quizzes.id');

        if ($this->request->getQuery('term')) {
            $q->find('searchByTerm', ['term' => $this->request->getQuery('term'), 'tags' => true]);
        }

        if ($this->request->getQuery('type')) {
            $type = $this->request->getQuery('type');

            if ($type == 'bigbrain') {
                $q->matching('Author', function($q) {
                    $q->where(['is_bigbrain' => true]);
                    return $q;
                });
            } else {
                $q->bind(':quiztype', $type);
                $q->where(['Quizzes.type = :quiztype']);
            }
        }


        $q->contain([
            'Author' => function($q) {
                return $q->select(['id', 'username', 'avatar', 'is_bigbrain']);
            }
        ]);

        return $this->Paginator->paginate($q);
    }

}
