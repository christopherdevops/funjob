<?php
namespace App\Middleware;

use Cake\Routing\Router;
use Cake\Utility\Text;

use Zend\Diactoros\Response\RedirectResponse;

/**
 * Nega l'accesso a determinate URl's se l'utente non è un developer
 */
class DeveloperOnlyMiddleware
{

    protected $__defaults = [
        'callback' => ['\App\Middleware\DeveloperOnlyMiddleware', 'isAuthorized'],
        'url'      => [
            // '/url'   => function($request) {}
            // '/url/*' => ['\App\Lib\Validation', 'isDeveloper']
        ]
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
        $isProtected = false;
        $url = $request->getUri()->getPath();

        if (empty($this->__options['url'])) {
            return $next($request, $response);
        }

        foreach ($this->__options['url'] as $_url => $callback) {
            if (strpos($_url, '*') !== false) {
                $_url    = str_replace(['*'], ['.*'], $_url);
                $pattern = '/' .str_replace('/', '\/', $_url). '/i';
                preg_match($pattern , $url, $matches);

                if ($matches) {
                    $isProtected = true;
                    break;
                }
            } else {
                $pattern = $url;
                if ($pattern == $_url) {
                    $isProtected = true;
                    break;
                }
            }
        }

        if ($isProtected) {
            $useCallback = $this->__options['callback']; // Global callback

            if (!empty($callback)) {
                $useCallback = $callback; // url callback
            }

            if (is_callable($useCallback)) {
                if (!call_user_func_array($useCallback, [$request])) {
                    return new RedirectResponse('/', 403);
                }
            } else {
                trigger_error(__('OnlyDeveloperMiddleware callback not callable'));
            }
        }

        return $next($request, $response);
    }

    static public function isAuthorized($request) {
    }

}
