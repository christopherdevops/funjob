<?php
namespace App\Event;

use Cake\Log\Log;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;

class UserNotificationListener implements EventListenerInterface {

    public function implementedEvents(): array
    {
        return [
            'Controller.User.Store.Purchase' => ['priority' => 99, 'callable' => 'onStorePurchase']
        ];
    }

    public function onStorePurchase()
    {

    }
}
