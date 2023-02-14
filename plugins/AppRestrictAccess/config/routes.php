<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'AppRestrictAccess',
    ['path' => '/forbidden', '_namePrefix' => 'access-restrict:'],
    function (RouteBuilder $routes) {
        $routes->connect(
            '/',
            ['plugin' => 'AppRestrictAccess', 'controller' => 'Authorizations', 'action' => 'authorize'],
            ['_name' => 'authorize']
        );
        $routes->fallbacks(DashedRoute::class);
    }
);
