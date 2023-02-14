<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Database\Expression\IdentifierExpression;

/**
 * Stores Controller
 *
 * @property \App\Model\Table\StoresTable $Stores
 */
class StoreProductsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        //$this->modelClass = 'StoreProducts';
        $this->viewBuilder()->setTemplatePath('Admin/Store/StoreProducts');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        // POST to GET (ricerca)
        if ($this->request->is('post')) {
            return $this->redirect(['?' => $this->request->getData()]);
        }

        $q = $this->StoreProducts->find('product');
        $q->find('notDeleted');
        $q->find('withCategories');
        $q->select(['id', 'name', 'qty', 'created', 'child_of']);

        if ($this->request->getQuery('term')) {
            $q->find('search', ['term' => $this->request->getQuery('term')]);
        }

        if ($this->request->getQuery('category')) {
            $node  = (int) $this->request->getQuery('category');
            $ids   = $this->StoreProducts->Categories->find('children', ['for' => $node])->extract('id')->toList();
            $ids[] = $this->request->getQuery('category');
            $q->find('byCategory', ['category_id' => $ids]);
        }

        $products   = $this->paginate($q, ['limit' => 50]);
        $categories = $this->StoreProducts->Categories->find('treeList', ['spacer' => ' * ']);

        $this->set(compact('products', 'categories'));
        $this->set('_serialize', ['products']);
    }

    /**
     * View method
     *
     * @param string|null $id Store id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id)
    {
        return $this->redirect(['_name' => 'store:product:view', $id]);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('StoreProductCategories');

        $product = $this->StoreProducts->newEntity();
        if ($this->request->is('post')) {
            $product = $this->StoreProducts->patchEntity($product, $this->request->getData(), [
                'associated' => [
                    'Categories'
                ]
            ]);
            if ($this->StoreProducts->save($product)) {
                $this->Flash->success(__d('backend', 'Prodotto creato'));
                return $this->redirect(['action' => 'edit', 0 => $product->id]);
            }

            $this->Flash->error(__d('backend', 'Verifica eventuali campi con errori e riprova'));
        }

        // $Category = $this->StoreProductCategories->newEntity(['name' => 'Buoni sconto', 'parent_id' => null, 'level' => 1]);
        // $saved = $this->StoreProductCategories->save($Category);
        // dd('--here');

        $categories = $this->StoreProductCategories->find('treeList', [
            'spacer' => '<div class="pull-left" style="margin-left:20px"> <span style="color:transparent">!</span> </div>'
        ]);

        $parentProducts = $this->StoreProducts->find('product')->find('list')->all();

        $this->set(compact('product', 'categories', 'parentProducts'));
        $this->set('_serialize', ['product']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Store id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('StoreProductCategories');

        $product = $this->StoreProducts->get($id, [
            'contain' => [
                'ParentProducts',
                'SubProducts' => function($q) {
                    $q->select(['id', 'qty', 'name', 'child_of']);
                    $q->find('notDeleted');
                    return $q;
                },
                'Categories',
                'Pictures'
            ]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $product = $this->StoreProducts->patchEntity($product, $this->request->getData(), [
                'associated' => [
                    'Categories'
                ]
            ]);

            if ($this->StoreProducts->save($product)) {
                $this->Flash->success(__d('backend', 'Prodotto aggiornato'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__d('backend', 'Verifica la presenza di errori e riprova.'));
        }

        $categories     = $this->StoreProductCategories->find('treeList', ['spacer' => '<i class="fa fa-arrow-right"></i> ']);
        $parentProducts = $this->StoreProducts->find('product')->find('list')->all();
        $Picture        = $this->StoreProducts->Pictures->newEntity();

        $this->set(compact('product', 'categories', 'parentProducts', 'Picture'));
        $this->set('_serialize', ['product']);
    }


    public function minimumAvailability()
    {
        $range = [0, constant('\App\Model\Table\StoreProductsTable::AVAILABILITY_MIN_NOTIFY_FROM')];

        $this->loadComponent('Paginator');
        $q = $this->StoreProducts->find('minimumAvailability');
        $q->contain(['ParentProducts']);

        $products = $this->Paginator->paginate($q);

        $this->set(compact('products', 'range'));
    }


    public function delete($id)
    {
        $Product     = $this->StoreProducts->get($id);
        $SubProducts = $this->StoreProducts
            ->find('subProducts', ['product_id' => $id])
            ->find('notDeleted')
            ->select(['id', 'is_deleted'])
            ->toList();

        $softDeleted = $this->StoreProducts->getConnection()->transactional(function() use ($Product, $SubProducts) {
            foreach ($SubProducts as $SubProduct) {
                $SubProduct->is_deleted = true;
                if (!$this->StoreProducts->save($SubProduct)) {
                    return false;
                }
            }

            $Product->is_deleted = true;
            return $this->StoreProducts->save($Product);
        });

        if ($softDeleted) {
            $this->Flash->success(__('Elemento eliminato'));
        } else {
            $this->Flash->error(__('Impossibile eliminare elemento'));
        }

        return $this->redirect($this->referer());
    }

}
