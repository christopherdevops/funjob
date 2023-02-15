<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Route\DashedRoute;

return static function(RouteBuilder $routes) {
    $routes->plugin(
        'AppRestrictAccess',
        ['path' => '/forbidden', '_namePrefix' => 'access-restrict:'],
        function (RouteBuilder $builder) {
            $builder->connect(
                '/',
                ['plugin' => 'AppRestrictAccess', 'controller' => 'Authorizations', 'action' => 'authorize'],
                ['_name' => 'authorize']
            );
            $builder->fallbacks(DashedRoute::class);
        }
    );
};
