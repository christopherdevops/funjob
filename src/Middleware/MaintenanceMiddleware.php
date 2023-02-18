<?php
namespace App\Middleware;

use Cake\Routing\Router;
use Cake\Utility\Text;
use Cake\Core\Configure;

//use Laminas\Diactoros\Response\RedirectResponse;

/**
 * Applicazione in manutenzione
 */
class MaintenanceMiddleware
{

    protected $__defaults = [
        'message'  => 'Maintenance',
        'location' => 'Pages/maintenance'
    ];

    protected $__authSessionPath = 'Auth';

    /**
     * Constructor.
     *
     * @param array $options The options to use
     */
    public function __construct(array $options = [])
    {
        $this->__options = array_merge($this->__defaults, $options);
    }

    public function __invoke($request, $response, $next)
    {
        $isProtected = Configure::read('Maintenance.enabled');
        if (!$isProtected) {
            return $next($request, $response);
        }

        $url = $request->getUri()->getPath();
        if ($url == Router::url(['_name' => 'maintenance'])) {
            return $next($request, $response);
        }

        if ($request->is('post') && $url == Router::url(['_name' => 'auth:login'])) {
            return $next($request, $response);
        }

        if (self::isAuthorized($request)) {
            return $next($request, $response);
        }

        $response->location(Router::url($this->__options['location']));
        return $next($request, $response);
    }

    public function isAuthorized($request) {
        return $request->getSession()->check( $this->__authSessionPath . '.User.type') == 'admin';
    }

}
