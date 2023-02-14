<?php
namespace App\Middleware;

use Cake\Routing\Router;
use Cake\Utility\Text;

use Zend\Diactoros\Response\RedirectResponse;

class MeRouteMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $routePrefix = $request->_matchedRoute;

        // Richiede Auth
        if (strpos($routePrefix, '/me') === 0) {
            if (!$request->session()->check('Auth.User.id')) {
                return new RedirectResponse(
                    Router::url(['_name' => 'auth:login'])
                );
            }
        }

        // Redirect /me (profilo) a /user/profile/n--username
        if ($routePrefix == '/me') {
            $id       = $request->session()->read('Auth.User.id');
            $username = Text::slug($request->session()->read('Auth.User.username'), '-');

            return new RedirectResponse(
                Router::url(['_name' => 'user:profile', 'id' => $id, 'username' => $username])
            );
        }

        return $next($request, $response);
    }
}
