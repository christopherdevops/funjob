<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Core\Configure;
use App\Model\Entity\StoreOrder;


/**
 * Order mailer.
 */
class OrderMailer extends Mailer
{

    /**
     * Mailer's name.
     *
     * @var string
     */
    static public $name = 'Order';

    const prefix = '[funjob.it]';

    public function adminNotify(StoreOrder $order)
    {
        $this->setProfile('default');
        $this->setTo(Configure::read('store_email'), 'FunJob.it admin');
        $this->setSubject(__('[funjob.it] Ordine da {0} (N° {1})', $order->user->username, $order->id));

        $this->setEmailFormat('html')
            ->viewBuilder()
            ->setLayout('admin')
            ->setTemplate('Store/Admin/new_order')
            ->setVars(compact('order'));
    }

    public function orderUpdateNotification($OrderMessage)
    {
        $this->setProfile('default');
        $this->setTo($OrderMessage['to']);
        $this->setSubject($OrderMessage['subject']);

        $this->setEmailFormat('html')
        ->viewBuilder()
        //->setLayout('admin')
        ->setTemplate('Store/Admin/order_update')
        ->setVars([
            'message' => $OrderMessage
        ]);
    }

    public function orderStateChangedNotification(StoreOrder $Order)
    {
        $this->setProfile('default');

        $this->setTo($Order->user->email);
        $this->setFrom(Configure::read('store_email'), 'FunJob.it store');
        $this->setBcc($Order->user->email);

        //$this->setReplyTo($Order->user->email);

        $this->setSubject(__('[funjob.it] Ordine #{id} aggiornato', ['id' => $Order->id]));

        $this
        ->setEmailFormat('html')
        ->viewBuilder()
        //->setLayout('admin')
        ->setTemplate('Store/Admin/order_state_updated')
        ->setVars([
            'order' => $Order
        ]);
    }


    /**
     * Richiesta di contatto da parte del cliente
     * @param  StoreOrder $Order
     */
    public function orderContactNotification(StoreOrder $Order, $requestData = [])
    {
        $this->setProfile('default');
        $this->setTo($Order->user->email);
        $this->setCc($Order->user->email);

        $this->setSubject(__('[funjob.it] {subject} (ordine: #{id})', [
            'id'      => $Order->id,
            'subject' => $requestData['subject']
        ]));

        $this->setEmailFormat('html')
        ->viewBuilder()
        //->setLayout('admin')
        ->setTemplate('Store/order_contact')
        ->setVars([
            'Order'       => $Order,
            'requestData' => $requestData
        ]);
    }

}
