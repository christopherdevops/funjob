<?php
namespace App\Controller;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\ORM\Query;

use App\Controller\AppController;
use App\Form\BigBrainContactForm;

use App\Model\Entity\CompanyCategory as Category;

/**
 * Bigbrains Controller
 *
 * @property \App\Model\Table\BigbrainsTable $Bigbrains
 */
class CompanyCategoriesBrowsersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow();

        $this->loadComponent('Paginator');
        $this->Categories = TableRegistry::get('CompanyCategories');
        $this->Companies  = TableRegistry::get('Companies');
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

        $quizzes       = new \Cake\Collection\Collection([]);
        $Category      = new \Cake\Collection\Collection([]);

        $qCategories   = $this->Categories->find();
        $qResults      = $this->Companies->find('isActive')->find('company');

        $useFilters    = false; // se vengono utilizzati filtri
        $hasResults    = false; // se vengono ricercati quizzes
        $SubCategories = 0; // Se il nodo di categoria corrente ha sotto nodi (NON PIU' UTILIZZATO)

        // Categoria fittizia
        $CategoryCurrent = new Category(['name' => __('Tutte le categorie'), 'id' => null]);

        // Tutte le categorie
        if (empty($node)) {
            $qCategories->where(['level' => 0]);
            $qCategories->where(function($exp, $q) {
                return $exp->isNull('parent_id');
            });
        } else {
            $Category = $this->Categories->find('path', ['for' => $node])->toArray();
            $CategoryCurrent = end($Category);

            //$SubCategories = $this->Categories->find()->where(['parent_id' => $node])->count();
            $qCategories->where(['parent_id' => (int) $node]);
        }

        $useFilters = true;
        $results    = $this->_getResults($qResults);
        $hasResults = $results->count();
        $categories = $this->Paginator->paginate($qCategories, ['limit' => 999]);

        $this->set(compact(
            'categories', 'Category', 'SubCategories', 'results',
            'isRoot', 'useFilters', 'hasResults',

            'CategoryCurrent'
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
                if (is_array($value)) {
                    foreach ($value as $_key => $_value) {
                        $url .= '&' .$key. '[' .$_key. ']='. $value [ $_key ] = $_value;
                    }
                } else {
                    $url .= '&' . $key .'='. $value;
                }
            }
            return $this->redirect($url);
        }
    }

    private function _getResults(Query $q)
    {
        $pass = $this->request->getParam('pass');
        $node = null;

        // Filtro su categoria
        if (isset($pass[0]) && is_numeric($pass[0])) {
            $node = (int) $pass[0];
            $childrens = $this->Categories->find('children', ['for' => $node])
                ->select(['id'])
                ->hydrate(false)
                ->extract('id')->toArray();

            $childrens[] = $node;

            // Quizzes solo per ultimo nodo del ramo (ottimizzazione)
            // In fase di creazione quiz è possibile selezionare solo ultimi nodi
            //$q->find('byCategory', ['category_id' => $node]);
            $q->find('filterByCategory', ['category_ids' => $childrens]);
        }

        if ($this->request->getQuery('term')) {
            $q->find('searchCompanyByName', ['name' => $this->request->getQuery('term')]);
        }

        if ($this->request->getQuery('city')) {
            $city_ids = array_keys($this->request->getQuery('city'));
            $q->find('filterByCity', ['city_id' => $city_ids]);
        }

        $q->group('Companies.id');

        return $this->Paginator->paginate($q);
    }

}
