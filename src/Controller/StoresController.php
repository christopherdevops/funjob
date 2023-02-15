<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Stores Controller
 */
class StoresController extends AppController
{
    public function initialize(): void
    {
        $this->modelClass = false;

        parent::initialize();
        $this->loadModel('StoreProducts');

        $this->Auth->allow(['index', 'archive', 'view', 'search']);
        $this->viewBuilder()->setLayout('store');

        // $this->loadComponent('Csrf');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        // $q = $this->StoreProducts
        //     ->find('product')
        //     ->find('visible')
        //         ->select(['id', 'name', 'descr', 'amount'])
        //         ->contain([
        //             'Pictures' => function($q) {
        //                 $q->select(['product_id', 'image', 'dir']);
        //                 $q->group(['product_id']);
        //                 return $q;
        //             }
        //         ])
        //         ->orderDesc('id');

        // $products = $this->paginate($q, ['limit' => 30]);

        $categories = $this->StoreProducts->Categories->find()
            ->select(['id', 'name'])
            ->where(['in_homepage' => 1])
            ->order(['RAND()'])
            ->limit(12)
            ->all();

        $this->set(compact('products', 'categories'));
        $this->set('_serialize', ['products']);
    }

    /**
     * Archive method
     *
     * @return \Cake\Network\Response|null
     */
    public function archive($category_id)
    {
        $StoreCategories = $this->StoreProducts->Categories;
        $category        = $StoreCategories->get($category_id);
        $categoryPath    = $StoreCategories->find('path', ['for' => $category->id]);

        // Figli
        $categoryChild   = $StoreCategories->find('children', ['for' => $category_id]);
        $categorie_ids   = $categoryChild->extract('id')->toList();
        $categorie_ids[] = $category_id;

        // Sotto-categorie: mostra bottoni
        $categorySubCat  = $StoreCategories->find()->where(['parent_id' => $category_id])->all();

        $q = $this->StoreProducts
            ->find('product')
                ->find('visible')
                ->find('notDeleted')
            ->find('archive', [
                'category_id' => $categorie_ids
            ])
        ->contain(['Pictures']);

        $products = $this->paginate($q, ['limit' => 30]);

        $this->set(compact('products', 'categories', 'category', 'categoryPath', 'categoryChild',  'categorySubCat'));
        $this->set('_serialize', compact('products', 'category'));
    }

    /**
     * View method
     *
     * @param string|null $id Store id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $product = $this->StoreProducts->get($id, [
            'contain' => [
                'Categories',
                'ParentProducts',
                'Pictures'
                //'ParentProduct.Categories'
            ]
        ]);

        // Blocca visualizzazione prodotto se è nascosto e l'utente non è un admin
        if (!$product->is_visible && $this->Auth->user('role') != 'admin') {
            $this->Flash->error(__('Prodotto non esistente'));
            return $this->redirect($this->referer(['_name' => 'store:index']));
        }

        if ($product->is_deleted) {
            $this->response->statusCode(404);
            $this->Flash->error(__('Prodotto inesistente'));
            return $this->redirect($this->referer());
        }

        // Determina crediti utenti
        $credits = false;
        if ($this->Auth->user('id')) {
            $this->loadModel('Users');
            $credits = $this->Users->Credits->find()->where(['user_id' => $this->Auth->user('id')])->first();
        }

        if ($product->has('parent_product')) {
            return $this->redirect([
                '_name' => 'store:product:view',
                'id'    => $product->parent_product->id,
                'slug'  => $product->parent_product->slug
            ]);
        }

        // Filtra immagini non esistenti
        $product->pictures = ( new \Cake\Collection\Collection((array) $product->pictures) )->filter(function($picture) {
            $src = ROOT .DS. $picture->dir .DS. $picture->image;
            return file_exists($src);
        })->toArray();

        $subproducts = $this->StoreProducts->find('subProducts', ['product_id' => $id]);

        // Verifica che l'utente abbia tutti i campi richiesti per lo store
        $requireFields     = $this->Auth->user('Auth.User') ? false : null;
        $requireFieldsList = [];
        $User              = null;

        if ($this->request->getSession()->check('Auth.User')) {
            $User = $this->Users->get($this->Auth->user('id'));
            if (in_array($User->type, ['admin', 'user'])) {
                $UserFields = TableRegistry::get('UserFields');
            } else {
                $UserFields = TableRegistry::get('CompanyFields');
            }

            // Aggiungo account_infos a $User
            $account_infos = $UserFields->find()->where(['user_id' => $User->id])->first();
            $User->set('account_info', $account_infos);

            // Validazione
            $User = $this->Users->newEntity($User->toArray(), [
                'validate' => 'storeRequirements',
                'associated' => [
                    'AccountInfos' => ['validate' => 'storeRequirements']
                ]
            ]);

            $requireFields     = empty($User->errors());
            $requireFieldsList = $User->errors();
        }
        $this->set(compact('requireFields', 'requireFieldsList'));


        $this->set(compact('product', 'subproducts', 'credits'));
        $this->set('_serialize', ['product']);
    }

    public function buy($productId = null)
    {
        $this->request->allowMethod('POST');
        $this->loadModel('Users');

        if (!$productId) {
            if (!$this->request->data('product_id')) {
                throw new \Cake\Network\Exception\BadRequestException();
            }

            $productId = $this->request->data('product_id');
        }

        $product = $this->StoreProducts->get($productId);

        $eventData = [
            'Product' => $product
        ];

        $User = $this->Users->get($this->Auth->user('id'), [
            'fields' => ['id', 'username', 'first_name', 'last_name', 'email']
        ]);

        try {
            $this->eventManager()->dispatch(new Event('Controller.User.Store.Purchase', $User, $eventData));

            $this->Flash->success(
                __('Ordine effettuato. Verrai contattato al più presto su {0}', '<strong>'. $User->email. '</strong>'),
                ['escape' => false]
            );
        } catch(Exception $e) {
            $this->Flash->failure(__('Ordine NON effettuato. Se pensi sia un problema contattaci'));
        }

        return $this->redirect($this->referer('/'));
    }

    /**
     * Ricerca prodotti
     */
    public function search()
    {
        $this->loadComponent('Paginator');

        $q = $this->StoreProducts->find('visible');
        $q->find('product');
        $q->find('notDeleted');

        if (!$this->request->getQuery('name')) {
            $this->Flash->error(__('Parametro di ricerca obbligatorio'));
            return $this->redirect($this->referer());
        }

        if ($this->request->getQuery('name')) {
            $term = $this->request->getQuery('name');
            $term = trim($term);

            $q->find('search', [
                'term' => $this->request->getQuery('name')
            ]);
        }

        $q->select(['id', 'name', 'descr'])
            ->contain([
                'Pictures' => function($q) {
                    $q->select(['product_id', 'image', 'dir']);
                    $q->group(['product_id']);
                    return $q;
                }
            ])
            ->orderDesc('id');

        $products = $this->Paginator->paginate($q, ['limit' => 15]);
        $isSearch = $this->request->getQuery('name') ? true : false;

        $this->set(compact('products', 'isSearch'));
        $this->set('_serialize', ['products']);
    }

}
