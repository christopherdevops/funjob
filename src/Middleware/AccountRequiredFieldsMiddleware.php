<?php
namespace App\Middleware;

use Cake\Routing\Router;
use Cake\Utility\Text;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

//use Laminas\Diactoros\Response\RedirectResponse;

/**
 * Verifica che l'account loggato abbia tutti i campi necessari all'account
 * Creato per le registrazioni tramite social network che potrebbero non fornire
 * tutti i dati necessari.
 */
class AccountRequiredFieldsMiddleware
{

    protected $__defaults = [
        'validator' => 'registrationFields', // require UsersTable::validationRequiredFields()

        'location'        => ['_name' => 'account:requirements'], // action dove impostare i campi obbligatori
        'locationSuccess' => ['_name' => 'me:dashboard'],         // action dove redirigere quando si accede a "location" e si hanno tutti i campi

        'except'    => [ // Lista di routes dove non effettuare il controllo dei campi richiesti
            ['_name' => 'auth:logout']
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
        $url = $request->getUri()->getPath();

        if (
            !$request->getSession()->check('Auth.User') ||
             $request->getSession()->read('Auth._skipAccountRequirementsSkip')
        ) {
            return $next($request, $response);
        }

        $isInExceptionRoutes =  false;
        $ignoredRoutes       = $this->__options['except']; //array_merge($this->__options['except'], [0 => $this->__options['location']]);

        foreach ($ignoredRoutes as $route) {

            try {
                $route = Router::url($route);
                if ($url == $route) {
                    $isInExceptionRoutes = true;
                    break;
                }
            } catch(\Exception $e) {
                debug($e->getMessage());
            }
        }

        // URL presente nella whitelist
        if ($isInExceptionRoutes) {
            return $next($request, $response);
        }

        $Users      = TableRegistry::get('Users');
        $User       = $Users->get($request->getSession()->read('Auth.User.id'));
        $User       = $Users->patchEntity($User, $User->toArray(), ['validate' => $this->__options['validator']]);
        $needFields = empty($User->getErrors());

        if ($needFields) {
            $response->location(Router::url($this->__options['location']));
        } else {

            if (!$request->getSession()->check('Auth._skipAccountRequirementsSkip')) {
                $request->getSession()->write('Auth._skipAccountRequirementsSkip', true);
            }

            // Non richiede campi obbligatori e sta accedendo alla pagina per impostarli
            // Non deve essere possibile (anche perchè vedrebbe un form con nessun campo) poichè vengono nascosti se sono OK
            if ($url == Router::url($this->__options['location'])) {
                $response->location(Router::url($this->__options['locationSuccess']));
            }
        }

        return $next($request, $response);
    }

}
