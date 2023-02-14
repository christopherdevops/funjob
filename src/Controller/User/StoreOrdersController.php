<?php
namespace App\Controller\User;

use App\Controller\AppController;
use App\Form\StoreOrderContactForm;

use Cake\Mailer\MailerAwareTrait;


/**
 * StoreOrders Controller
 *
 * @property \App\Model\Table\StoreOrdersTable $StoreOrders
 *
 * @method \App\Model\Entity\StoreOrder[] paginate($object = null, array $settings = [])
 */
class StoreOrdersController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->Auth->deny(['index', 'view']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->loadComponent('Paginator');

        $q = $this->StoreOrders->find();
        $q->where(['StoreOrders.user_id' => $this->Auth->user('id')]);
        $q->contain(['Products']);
        $q->orderDesc('StoreOrders.id');

        $status = $this->request->getQuery('status', 'all');

        if (in_array($status, ['pending', 'rejected', 'completed'])) {
            $q->bind(':status', $status);
            $q->where(['StoreOrders.status = :status']);
        }

        $orders = $this->Paginator->paginate($q);

        $this->set(compact('orders'));
        $this->set('_serialize', ['orders']);
    }

    /**
     * View method
     *
     * @param string|null $id Store Order id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function _view($id = null)
    {
        $storeOrder = $this->StoreOrders->get($id, [
            'contain' => ['Users', 'Products']
        ]);

        $this->set('storeOrder', $storeOrder);
        $this->set('_serialize', ['storeOrder']);
    }


    public function contact()
    {
        $this->request->allowMethod(['PUT']);

        $Form = new StoreOrderContactForm();
        if (!$Form->execute($this->request->getData())) {
            return $this->redirect($this->referer());
        }

        $StoreOrder = $this->StoreOrders->get($this->request->getData('order_id'), [
            'contain' => [
                'Users',
                'Products'
            ]
        ]);

        $sent = $this->getMailer('Order')->send('orderContactNotification', [$StoreOrder, $this->request->getData()]);
        if ($sent) {
            $this->Flash->success(__('Richiesta inoltrata, riceverai il prima possibile una risposta'));
        }

        return $this->redirect($this->referer());
    }
}
