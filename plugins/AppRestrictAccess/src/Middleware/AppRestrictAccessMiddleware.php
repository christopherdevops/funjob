<?php
namespace AppRestrictAccess\Middleware;

use Cake\Routing\Router;
use Cake\Utility\Text;

use Cake\Core\Configure;
use Cake\Log\Log;

use Zend\Diactoros\Response\RedirectResponse;

/**
 * Applicazione in manutenzione
 */
class AppRestrictAccessMiddleware
{

    protected $__defaults = [];

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
        $path       = (string) $request->getUri();
        $redirectTo = Router::url(['_name' => 'access-restrict:authorize'], true);

        // FIX: redirection loop debugkit
        if (strpos($path, '/debug_kit/') !== FALSE) {
            return $next($request, $response);
        }

        if ($request->session()->check('AccessRestriction.authorized')) {
            return $next($request, $response);
        }

        // Previene loop di redirect
        if ($path !== $redirectTo) {
            Log::debug(sprintf('AppRestrictAccessMiddleware::forbidden %s', $path));
            return new RedirectResponse(Router::url(['_name' => 'access-restrict:authorize']));
        }

        return $next($request, $response);
    }

}
