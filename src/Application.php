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
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
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
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } 

        /*
        * Only try to load DebugKit in development mode
        * Debug Kit should not be installed on a production system
        */
        if (Configure::read('debug')) {
            Configure::write('DebugKit.panels', ['DebugKit.Packages' => false]);
            $this->addPlugin('DebugKit', ['bootstrap' => true]);
        }

        $this->addPlugin('Migrations');
        $this->addPlugin('BootstrapUI');
        $this->addPlugin('Josegonzalez/Upload');

        // Disabilitato:
        // Alcuni CSS (quelli inline css_head--inline, che erano soggetti gi?? a compressione tramite preg_match) non venivano interpretati
        // correttamente
        //$this->addPlugin('WyriHaximus/MinifyHtml', ['bootstrap' => true]);

        $this->addPlugin('Recaptcha', []);
        // $this->addPlugin('ADmad/HybridAuth', ['bootstrap' => true, 'routes' => true]);
        $this->addPlugin('CsvView');
        $this->addPlugin('AppRestrictAccess', ['bootstrap' => true, 'routes' => true]);

    }

    /**
     * Metodo utilizzato dal Middleware OnlyDeveloperMiddleware
     *
     * @param  \Cake\Network\Request  $request
     * @return boolean
     */
    static public function isDeveloper($request)
    {
        if (!$request->getSession()->check('Auth.User.id')) {
            return false;
        }

        $ids = $request->getSession()->read('Auth.User.id');
        return in_array($ids, [1,2,3,4,5,7,8]);
    }

    /**
     * Setup the middleware your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middleware The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware.
     */
    public function middleware($middleware): \Cake\Http\MiddlewareQueue
    {
        $middleware
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Apply routing
            ->add(new RoutingMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            // Cross Site Request Forgery (CSRF) Protection Middleware
            // https://book.cakephp.org/4/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]));

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

    /**
     * Bootstrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Bake');

        // Load more plugins here
    }
}
