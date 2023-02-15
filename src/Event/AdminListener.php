<?php
namespace App\Event;

use Cake\Log\Log;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;

use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;


class AdminListener implements EventListenerInterface {
    use MailerAwareTrait;

    /**
     * Default config for this object.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'mailer' => 'User',
    ];

    public function implementedEvents(): array {
        return [
            //'Controller.User.Store.Purchase' => ['priority' => 99, 'callable' => 'onStorePurchase']
            //'Controller.User.Store.NewOrder' => ['priority' => 99, 'callable' => 'onStorePurchase']
        ];
    }

    public function onStorePurchase($event, $order)
    {
        // Utilizza App\Mailer\OrderMailer.php
        //$this->getMailer('Order')->send('adminNotify', [$order]);
    }
}
