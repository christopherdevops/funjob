<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Form\AdminOrderMessageForm;
use App\Form\StoreOrderContactForm;

use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;

/**
 * StoreOrders Controller
 *
 * @property \App\Model\Table\StoreOrdersTable $StoreOrders
 */
class StoreOrdersController extends AppController
{
    use MailerAwareTrait;

    public function initialize(): void
    {
        parent::initialize();

        //$this->viewBuilder()->enableAutoLayout(false);
        $this->viewBuilder()->setTemplatePath('Admin/Store/StoreOrders');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $q = $this->StoreOrders->find();
        $q->contain([
            'Users'    => function($q) {
                return $q->select(['id', 'username']);
            },
            'Products' => function($q) { return $q->select(['id', 'name']); },
        ]);

        if ($this->request->getQuery('status')) {
            if (in_array($this->request->getQuery('status'), ['pending', 'completed', 'rejected'])) {
                $q->where(['status' => $this->request->getQuery('status')]);
            }
        } else {
            $q->where(function($exp, $q) {
                return $exp->notEq('status', 'rejected');
            });
        }

        // Default sorter
        if (!$this->request->getQuery('sort')) {
            $q->orderDesc('StoreOrders.id');
        }

        $orders = $this->paginate($q, ['limit' => 30]);

        $this->set(compact('orders'));
        $this->set('_serialize', ['orders']);
    }

    /**
     * View method
     *
     * @param string|null $id Store Order id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $message = new AdminOrderMessageForm();
        $order = $this->StoreOrders->get($id, [
            'contain' => [
                'Users',
                'Users.AccountInfos',
                'Products'
            ]
        ]);

        if (in_array($order->user->type, ['user', 'admin'])) {
            $AccountInfos = TableRegistry::get('UserFields');
        } else {
            $AccountInfos = TableRegistry::get('CompanyFields');
        }

        $order->user->account_info = $AccountInfos->findByUserId($order->user->id)->first();

        if ($this->request->is('post')) {
            if ($message->execute($this->request->getData())) {
                $this->Flash->success(__('Aggiornamento inviato a utente'));
                return $this->redirect($this->referer('/'));
            }
        }

        $this->set(compact('order', 'message'));
        $this->set('_serialize', ['order']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Store Order id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['post', 'put', 'patch']);

        $storeOrder = $this->StoreOrders->get($id, [
            'contain' => [
                'Products',
                'Users'
            ]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $fieldList  = ['status', 'note', 'modified'];
            $storeOrder = $this->StoreOrders->patchEntity($storeOrder, $this->request->getData(), compact('fieldList'));

            if ($this->StoreOrders->save($storeOrder)) {
                $this->getMailer('Order')->send('orderStateChangedNotification', [$storeOrder]);

                $this->Flash->success(__d('backend', 'Ordine aggiornato (inviata email a utente)'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__d('backend', 'Impossibile aggiornare ordine'));
        }

        //$users    = $this->StoreOrders->Users->find('list', ['limit' => 200]);
        //$products = $this->StoreOrders->Products->find('list', ['limit' => 200]);

        $this->set(compact('storeOrder', 'users', 'products'));
        $this->set('_serialize', ['storeOrder']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Store Order id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
//     public function delete($id = null)
//     {
//         $this->request->allowMethod(['post', 'delete']);
//         $storeOrder = $this->StoreOrders->get($id);
//         if ($this->StoreOrders->delete($storeOrder)) {
//             $this->Flash->success(__('The store order has been deleted.'));
//         } else {
//             $this->Flash->error(__('The store order could not be deleted. Please, try again.'));
//         }

//         return $this->redirect(['action' => 'index']);
//     }
}
