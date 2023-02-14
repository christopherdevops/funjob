<?php
use Cake\Event\EventManager;
use Cake\Core\Configure;

use AppRestrictAccess\Middleware\AppRestrictAccessMiddleware;


// Configuration
Configure::write('RestrictionAccess.enable', filter_var(env('RESTRICTION_AUTHORIZATION_ENABLED', false), FILTER_VALIDATE_BOOLEAN));
Configure::write('RestrictionAccess.pwd', env('RESTRICTION_AUTHORIZATION_PWD', 'bar'));

// Middleware
EventManager::instance()->on(
    'Server.buildMiddleware',
    function ($event, $middlewareQueue) {
        if (Configure::read('RestrictionAccess.enable')) {

            $middlewareQueue->add(new AppRestrictAccessMiddleware([
                //'pwd' => Configure::read('RestrictionAccess.pwd'),
            ]));

        }
    }
);
