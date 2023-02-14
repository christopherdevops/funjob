<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Cake\Core\Configure;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

use App\Middleware\MeRouteMiddleware;
use App\Middleware\DeveloperOnlyMiddleware;
use App\Middleware\HttpBasicAuthorizationMiddleware;
use App\Middleware\MaintenanceMiddleware;
use App\Middleware\AccountRequiredFieldsMiddleware;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication
{

    /**
     * Metodo utilizzato dal Middleware OnlyDeveloperMiddleware
     *
     * @param  \Cake\Network\Request  $request
     * @return boolean
     */
    static public function isDeveloper($request)
    {
        if (!$request->session()->check('Auth.User.id')) {
            return false;
        }

        $ids = $request->session()->read('Auth.User.id');
        return in_array($ids, [1,2,3,4,5,7,8]);
    }

    /**
     * Setup the middleware your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middleware The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware.
     */
    public function middleware($middleware)
    {
        $middleware
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error.exceptionRenderer')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware())

            // Apply routing
            ->add(new RoutingMiddleware());

            // if (Configure::read('Maintenance.enabled')) {
            //     $middleware
            //     ->add(new MaintenanceMiddleware([
            //         'message'   => __('Applicazione in manutenzione'),
            //         'location'  => ['_name' => 'maintenance'],
            //     ]));
            // }

            // $middleware
            //     ->add(new AccountRequiredFieldsMiddleware([]));

            // $middleware
            // ->add(new DeveloperOnlyMiddleware([
            //     // Global callback
            //     'callback' => ['\App\Application', 'isDeveloper'],
            //     'url' => [
            //         //'/store/*' => ['\App\Application', 'isDeveloper']
            //     ]
            // ]))

            $middleware->add(new MeRouteMiddleware());
        return $middleware;
    }
}
