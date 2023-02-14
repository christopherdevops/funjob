<?php
namespace App\Middleware;

use Cake\Routing\Router;
use Cake\Utility\Text;

use Cake\Core\Configure;
use Cake\Log\Log;

//use Zend\Diactoros\Response\RedirectResponse;

/**
 * Applicazione in manutenzione
 */
class HttpBasicAuthorizationMiddleware
{

    protected $__defaults = [
        'realm' => 'realm',
        'pwd'   => 'foo'
    ];

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
        $askPassword = Configure::read('Authorization.enabled');
        if (!$askPassword) {
            return $next($request, $response);
        }

        // PHP_AUTH_PW on PHP/FASTCGI
        if (!isset($_SERVER['PHP_AUTH_PW'])) {
            $_SERVER['PHP_AUTH_PW'] = '';
        }

        foreach (['HTTP_AUTHORIZATION', 'REDIRECT_HTTP_AUTHORIZATION', 'REDIRECT_REDIRECT_HTTP_AUTHORIZATION', 'REDIRECT_REMOTE_USER'] as $key) {
            if (isset($_SERVER[$key])) {
                Log::debug(sprintf('HTTP header authorization: %s => %s', $key, $_SERVER[$key]));
                $base64 = substr($_SERVER[$key], 6);

                if (!empty($base64)) {
                    $credentials     = base64_decode($base64);
                    list($usr, $pwd) = explode(':', $credentials);
                    if (!empty($pwd)) {
                        $_SERVER['PHP_AUTH_PW'] = $pwd;
                        break;
                    }
                }
            }
        }

        if (!empty($_SERVER['PHP_AUTH_PW'])) {
            if (strcasecmp($_SERVER['PHP_AUTH_PW'], $this->__options['pwd']) === 0) {
                Log::debug(sprintf('PHP_AUTH_PW valid for %s', $request->getUri()));
                return $next($request, $response);
            }
        }

        Log::debug(sprintf('HTTP_AUTHORIZATION_BASIC required for %s', $request->getUri()));

        $realm    = $this->__options['realm'];
        $response = $response->withStatus('401')->withHeader('WWW-Authenticate', 'Basic realm="' .$realm.'"')->withStringBody('FORBIDDEN!');
        return $response;

        //return $next($request, $response);
    }

}
