<?php
use Cake\Core\Configure;

Class RouteValidator {
    const UUID = '[A-Za-z0-9\-]+';
    const SLUG = '[0-9A-Za-z_\-]+';
    const ID   = '\d+';


    const QUIZ_STEPS  = '(1|2|3|4|5|6|7|8|9|10)';
    const QUIZ_LEVELS = '(1|2|3|4|5|6|7|8|9)';

}

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);


Router::scope('/', function (RouteBuilder $routes) {
    $routes->extensions(['json']);

    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Homepages', 'action' => 'home'], ['_name' => 'home']);

    if (Configure::read('Maintenance.enabled')) {
        $routes->connect('/maintenance', ['controller' => 'Homepages', 'action' => 'maintenance'], ['_name' => 'maintenance']);
    }

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

// Sito informativo
Router::scope('/funjob', ['_namePrefix' => 'funjob:', 'prefix' => false], function(RouteBuilder $routes) {
    $routes->connect('/funjob', ['controller' => 'Pages', 'action' => 'display', 0 => 'funjob'], ['_name' => 'whois']);

    $routes->connect(
        '/profiles',
        ['controller' => 'FunJobPages', 'action' => 'display', 0 => 'index'],
        ['_name' => 'profiles']
    );
    $routes->connect(
        '/profiles/users',
        ['controller' => 'FunJobPages', 'action' => 'display', 0 => 'profiles', 1 => 'user'],
        ['_name' => 'profiles:user']
    );
    $routes->connect(
        '/profiles/companies',
        ['controller' => 'FunJobPages', 'action' => 'display', 0 => 'profiles', 1 => 'company'],
        ['_name' => 'profiles:company']
    );
    $routes->connect(
        '/profiles/sponsors',
        ['controller' => 'FunJobPages', 'action' => 'display', 0 => 'profiles', 1 => 'sponsor'],
        ['_name' => 'profiles:sponsor']
    );


    $routes->connect('/', ['controller' => 'FunJobPages', 'action' => 'display', 0 => 'index'], ['_name' => 'info']);
    $routes->connect('/pages/*', ['controller' => 'FunJobPages', 'action' => 'display']);
    $routes->connect('/terms', ['prefix' => false, 'controller' => 'Pages', 'action' => 'display', 0 => 'terms_and_conditions'], ['_name' => 'terms']);
    $routes->connect('/cookies', ['prefix' => false, 'controller' => 'Pages', 'action' => 'display', 0 => 'cookie_policy'], ['_name' => 'cookies']);

    $routes->fallbacks(DashedRoute::class);
});

// Pubblicità
// Se si utilizza nell'URL una parola come adv viene automaticamente bloccato da adblock (o affini)
Router::scope('/static/assets', ['_namePrefix' => 'adv:'], function($routes) {
    $routes->extensions(['json']);

    $routes->connect('/list', ['controller' => 'SponsorAdvs', 'action' => 'index'], ['_name' => 'active']);

    // Preleva pubblicità corrente e decrementa impressions
    $routes->connect('/get', ['controller' => 'SponsorAdvs', 'action' => 'index'], ['_name' => 'get']);

    $routes->connect(
        '/:uuid',
        ['controller' => 'SponsorAdvs', 'action' => 'image'],
        [
            //'filename' => '[0-9]+',
            'uuid'       => RouteValidator::UUID,
            'pass'     => ['uuid'],
            '_name'    => 'image'
    ]);
    $routes->connect(
        '/goto/:uuid',
        ['controller' => 'SponsorAdvs', 'action' => 'track'],
        [
            '_name' => 'track',
            'uuid'  => '[A-Za-z0-9\-]+',
            'pass'  => ['uuid']
        ]
    );

    $routes->fallbacks(DashedRoute::class);
});

// Quiz
Router::scope('/quiz', ['_namePrefix' => 'quiz:'], function (RouteBuilder $routes) {
    $routes->connect('/', ['controller' => 'Quizzes', 'action' => 'index'], ['_name' => 'index']);
    $routes->connect('/popular', ['controller' => 'Quizzes', 'action' => 'popular'], ['_name' => 'popular']);
    $routes->connect('/create', ['prefix' => null, 'controller' => 'Quizzes', 'action' => 'add'], ['_name' => 'create']);

    // Informazioni quiz (start sessione di gioco)
    $routes->connect(
        '/:id-:title',
        ['controller' => 'Quizzes', 'action' => 'view'],
        [
            '_name'  => 'view',
            'pass'   => ['id'],
            'id'     => RouteValidator::ID,
            'title'  => RouteValidator::UUID,
        ]
    );
    // Gioco
    $routes->connect(
        '/:id-:title/level-:level/:step',
        ['controller' => 'Quizzes', 'action' => 'play'],
        [
            'id'    => RouteValidator::ID,
            'title' => RouteValidator::SLUG,
            'step'  => RouteValidator::QUIZ_STEPS,
            'level' => RouteValidator::QUIZ_LEVELS,

            '_name' => 'play',
            'pass'  => ['id']
        ]
    );

    // Risposta gioco
    $routes->connect(
        '/:id-:title/level-:level/:step/reply',
        ['controller' => 'Quizzes', 'action' => 'reply'],
        [
            'id'    => RouteValidator::ID,
            'title' => RouteValidator::SLUG,
            'step'  => RouteValidator::QUIZ_STEPS,
            'level' => RouteValidator::QUIZ_LEVELS,

            '_name' => 'reply',
            'pass'  => ['id']
        ]
    );

    // Punteggio sessione dopo giocata
    $routes->connect(
        '/:id-:title/score',
        ['controller' => 'Quizzes', 'action' => 'score'],
        [
            'id'    => RouteValidator::ID,
            'title' => RouteValidator::SLUG,

            '_name' => 'score',
            'pass'  => ['id']
        ]
    );


    $routes->scope('/report', function($routes) {

        // Log di sessione di gioco (in profilo utente)
        $routes->connect(
            '/log/:id',
            ['controller' => 'QuizSessionLevels', 'action' => 'view'],
            [
                '_name' => 'replies',
                'id'    => RouteValidator::ID,
                'pass'  => ['id']
            ]
        );

        $routes->connect(
            '/detail/:id',
            ['controller' => 'QuizSessions', 'action' => 'view'],
            [
                '_name' => 'report',
                'id'    => RouteValidator::ID,
                'pass'  => ['id']
            ]
        );

    });

    $routes->scope(
        '/categories',
        ['_namePrefix' => 'categories:', 'controller' => 'QuizCategoryBrowsers'],
        function($routes) {
            // Archivio
            $routes->connect(
                '/',
                ['plugin' => false, 'action' => 'index'],
                [
                    '_name'  => 'archive',
                    'pass'  => []
                ]
            );
            // Archivio sottocategoria
            $routes->connect(
                '/:id--:title',
                ['plugin' => false, 'action' => 'index'],
                [
                    '_name'  => 'archive-slug',
                    'id'    => RouteValidator::ID,
                    'title' => RouteValidator::SLUG,
                    'pass'  => ['id']
                ]
            );

        }
    );


    // $routes->connect(
    //     '/randomize',
    //     ['plugin' => false, 'prefix' => null, 'controller' => 'quizzes', 'action' => 'rand'],
    //     [
    //         '_name' => 'rand',
    //     ]
    // );

    // Editor
    $routes->connect(
        '/edit/:id',
        ['prefix' => null, 'controller' => 'quizzes', 'action' => 'edit'],
        ['_name' => 'edit', 'id' => '\d+', 'pass' => ['id']]
    );
    $routes->connect(
        '/delete/:id',
        ['prefix' => null, 'controller' => 'quizzes', 'action' => 'delete'],
        ['_name' => 'delete', 'id' => '\d+', 'pass' => ['id']]
    );

    $routes->fallbacks(DashedRoute::class);
});

Router::scope(
    '/quiz/ranking/',
    ['_namePrefix' => 'quiz:ranking:', 'plugin' => null, 'prefix' => 'user', 'controller' => 'QuizUserRankings'],
    function ($routes) {
        $routes->connect('/add', ['action' => 'add'], ['_name' => 'add']);
        $routes->connect('/edit/:id', ['action' => 'edit'], ['_name' => 'edit', 'id' => RouteValidator::ID, 'pass' => ['id']]);
    }
);

// QuizCategory
Router::scope('/quiz/categories', ['_namePrefix' => 'quiz-categories:'], function(RouteBuilder $routes) {
    $routes->connect('/', ['prefix' => null, 'controller' => 'QuizCategories', 'action' => 'index'], ['_name' => 'search']);
    $routes->connect(
        '/:title--:id',
        ['prefix' => null, 'controller' => 'Quizzes', 'action' => 'browse'],
        [
            '_name' => 'browse',
            'id'    => RouteValidator::ID,
            'title' => RouteValidator::SLUG,
            'pass'  => ['id']
        ]
    );

    $routes->fallbacks(DashedRoute::class);
});

// CV
Router::scope('/cv/', ['_namePrefix' => 'cv:'], function(RouteBuilder $routes) {
    $routes->connect(
        '/:uuid/view/:user_id',
        ['controller' => 'CurriculumVitaes', 'action' => 'view'],
        [
            '_name'   => 'view',
            'uuid'    => RouteValidator::UUID,
            'user_id' => RouteValidator::ID,
            'pass'    => ['uuid', 'user_id']
        ]
    );

    $routes->connect(
        '/:uuid/request',
        ['prefix' => null, 'plugin' => null, 'controller' => 'CvAuthorizations', 'action' => 'add'],
        [
            '_name' => 'request',
            'uuid'  => RouteValidator::UUID,
            'pass'  => ['uuid']
        ]
    );

    $routes->connect(
        '/delete',
        ['prefix' => null, 'plugin' => null, 'controller' => 'CurriculumVitaes', 'action' => 'delete'],
        ['_name' => 'delete']
    );

    $routes->scope('/authorizations', function($routes) {
        $routes->connect(
            '/',
            ['prefix' => null, 'plugin' => null, 'controller' => 'CvAuthorizations', 'action' => 'index'],
            [
                '_name' => 'authorizations:archive',
                'pass'  => []
            ]
        );
        $routes->connect(
            '/filter/:state',
            ['prefix' => null, 'plugin' => null, 'controller' => 'CvAuthorizations', 'action' => 'filter'],
            [
                '_name' => 'authorizations:filter',
                'state' => '(pending|allowed|denied)',
                'pass'  => ['state']
            ]
        );
        $routes->connect(
            '/update/:id',
            ['prefix' => null, 'plugin' => null, 'controller' => 'CvAuthorizations', 'action' => 'edit'],
            [
                '_name' => 'authorizations:update',
                'id'    => RouteValidator::ID,
                'pass'  => ['id'],
                '_ext'  => 'json'
            ]
        );
    });

});

// Me
// Prefisso utilizzato per actions che hanno a che fare con l'account dell'utente loggato
// PS: Utilizza MeScopeMiddleware
Router::scope('/me', ['_namePrefix' => 'me:'], function(RouteBuilder $routes) {
    $routes->connect('/', ['prefix' => null, 'controller' => 'users', 'action' => 'view'], ['_name' => 'profile']);
    $routes->connect('/dashboard', ['prefix' => null, 'controller' => 'UserDashboards', 'action' => 'index'], ['_name' => 'dashboard']);
    $routes->connect('/disable-account', ['prefix' => null, 'controller' => 'Users', 'action' => 'disable'], ['_name' => 'disable']);

    // Impostazioni (redirecta a /settings/:prefix)
    // Perchè ogni tipologia di Utente (user/company) ha impostazioni differente
    $routes->connect(
        '/settings/',
        ['controller' => 'users', 'action' => 'settings'],
        ['_name' => 'settings']
    );
    $routes->connect(
        '/settings/:prefix',
        ['controller' => 'users', 'action' => 'settings'],
        ['_name' => 'settings:prefixed']
    );

    $routes->scope('/quizzes', function (RouteBuilder $routes) {
        $routes->connect('/', ['prefix' => 'user', 'controller' => 'users', 'action' => 'quizzes'], ['_name' => 'quizzes']);

        // Quiz svolti (pubblico)
        //$routes->connect('/completed', ['prefix' => null, 'controller' => 'users', 'action' => 'quizCompleted'], ['_name' => 'quizzes:completed']);

        // Quiz svolti parte backend (pubblico)
        $routes->connect('/completed', ['prefix' => 'user', 'controller' => 'Quizzes', 'action' => 'played'], ['_name' => 'quizzes:completed']);
        $routes->connect('/created', ['prefix' => null, 'controller' => 'users', 'action' => 'quizCreated'], ['_name' => 'quizzes:created']);
    });

    //$routes->connect('/credits', ['prefix' => 'user', 'controller' => 'users', 'action' => 'credits'], ['_name' => 'credits']);
    $routes->connect('/orders', ['prefix' => 'user', 'controller' => 'StoreOrders', 'action' => 'index'], ['_name' => 'orders']);

    $routes->fallbacks(DashedRoute::class);
});

// User
Router::prefix('user', ['_namePrefix' => 'user:'], function (RouteBuilder $routes) {

    $routeContext = ['_namePrefix' => 'profile:', 'prefix' => null, 'controller' => 'users'];
    $routes->scope('/:id-:username/', $routeContext, function ($routes) {
        $routeParams  = [
            'pass'     => ['id'],
            'id'       => RouteValidator::ID,
            'username' => RouteValidator::SLUG
            //'fullname' => RouteValidator::SLUG
        ];

        $routes->connect('/', ['action' => 'view'], $routeParams + ['_name' => 'home']);
        $routes->connect('/friends', ['action' => 'friends'], $routeParams + ['_name' => 'friends']);
        $routes->connect('/groups', ['action' => 'groups'], $routeParams + ['_name' => 'groups']);
    });

    // Profilo pubblico utente
    $routes->connect(
        '/:id-:username',
        ['prefix' => null, 'controller' => 'users', 'action' => 'view'],
        [
        '_name'    => 'profile',
        'id'       => '\d+',
        'username' =>  RouteValidator::SLUG,
        'pass'     => ['id']
        ]
    );

    $routes->prefix('quizzes/', function (RouteBuilder $routes) {
        $routes->connect('/', ['prefix' => null, 'controller' => 'Quizzes', 'action' => 'index'], ['_name' => 'index']);

        // Quiz completati
        $routes->connect(
            'completed/:user_id',
            ['prefix' => null, 'controller' => 'users', 'action' => 'quizCompleted'],
            ['_name' => 'quizzes:completed', 'pass' => ['user_id'], 'user_id' => RouteValidator::ID]
        );

        // Quiz completati
        $routes->connect(
            'created/:user_id/:type',
            ['prefix' => null, 'controller' => 'users', 'action' => 'quizCreated', 'type' => 'default'],
            ['_name' => 'quizzes:created', 'pass' => ['user_id'], 'user_id' => '\d+']
        );
    });

    $routes->connect(
        '/search',
        ['plugin' => false, 'controller' => 'users', 'action' => 'search'],
        ['_name' => 'search']
    );

    $routes->fallbacks(DashedRoute::class);
});

Router::scope('/companies', ['_namePrefix' => 'companies:', 'prefix' => null,  'controller' => 'Companies'], function($routes) {
    $routes->connect('/', ['action' => 'index'], ['_name' => 'index']);
    $routes->connect(
        '/:id--:username',
        ['action' => 'view'],
        ['_name' => 'profile', 'id' => RouteValidator::ID, 'username' => RouteValidator::SLUG, 'pass' => ['id']]
    );

    $routes->scope(
        '/browse',
        ['_namePrefix' => 'categories:', 'controller' => 'CompanyCategoriesBrowsers'],
        function($routes) {
            // Archivio
            $routes->connect(
                '/',
                ['plugin' => false, 'action' => 'index'],
                [
                    '_name'  => 'archive',
                    'pass'  => []
                ]
            );
            // Archivio sottocategoria
            $routes->connect(
                '/:id--:title',
                ['plugin' => false, 'action' => 'index'],
                [
                    '_name'  => 'archive-slug',
                    'id'    => RouteValidator::ID,
                    'title' => RouteValidator::SLUG,
                    'pass'  => ['id']
                ]
            );

        }
    );
});

// Admin backend
Router::prefix('admin', function (RouteBuilder $routes) {

    $routes->connect('/quiz-categories/', ['controller' => 'quiz-categories', 'action' => 'index'], ['_name' => 'admin/quiz-categories/index']);
    $routes->connect('/quiz-categories/add', ['controller' => 'quiz-categories', 'action' => 'add'], ['_name' => 'admin/quiz-categories/add']);
    $routes->connect('/quiz-categories/edit/:id', ['controller' => 'quiz-categories', 'action' => 'edit'], ['pass' => ['id']], ['_name' => 'admin/quiz-categories/edit']);

    $routes->fallbacks(DashedRoute::class);
});

// User (company)
// Prefisso utilizzato da gli users con role (company)
Router::prefix('company', function ($routes) {
    $routes->extensions(['json']);
    $routes->fallbacks(DashedRoute::class);
});

// User (sponsor)
// Prefisso utilizzato da gli users con role (sponsor)
Router::prefix('sponsor', function ($routes) {
    $routes->fallbacks(DashedRoute::class);
});

// Autenticazione
Router::scope('/auth', ['_namePrefix' => 'auth:'], function(RouteBuilder $routes) {
    $routes->connect('/register', ['prefix' => null, 'controller' => 'users', 'action' => 'register'], ['_name' => 'register']);
    $routes->connect('/login', ['prefix' => null, 'controller' => 'users', 'action' => 'login'], ['_name' => 'login']);
    $routes->connect('/logout', ['prefix' => null, 'controller' => 'users', 'action' => 'logout'], ['_name' => 'logout']);

    $routes->connect('/endpoint/register', ['plugin' => null, 'controller' => 'HybridAuth', 'action' => 'register'], ['_name' => 'register:hybrid']);
    $routes->fallbacks(DashedRoute::class);
});

// Leaderboard
Router::scope('/leaderboard', ['_namePrefix' => 'leaderboard:'], function(RouteBuilder $routes) {
    $routes->connect('/', ['prefix' => null, 'controller' => 'leaderboards', 'action' => 'index'], ['_name' => 'index']);
    $routes->fallbacks(DashedRoute::class);
});

// Private message system
Router::scope('/messages', ['_namePrefix' => 'message:'], function (RouteBuilder $routes) {
    $routes->connect(
        '/',
        ['plugin' => false, 'prefix' => 'user', 'controller' => 'UserMessages', 'action' => 'index'],
        ['_name' => 'archive']
    );
    $routes->connect(
        '/conversation/:uuid',
        ['plugin' => false, 'prefix' => 'user', 'controller' => 'UserMessages', 'action' => 'view'],
        ['_name' => 'view', 'uuid' => RouteValidator::UUID, 'pass' => ['uuid']]
    );

    $routes->connect(
        '/compose',
        ['plugin' => false, 'prefix' => 'user', 'controller' => 'UserMessages', 'action' => 'add', 'username' => null],
        ['_name' => 'compose', 'username' => '(^$|' . RouteValidator::SLUG .')', 'pass' => ['username']]
    );
    $routes->connect(
        '/compose/:username',
        ['plugin' => false, 'prefix' => 'user', 'controller' => 'UserMessages', 'action' => 'add', 'username' => null],
        ['_name' => 'compose:username', 'username' => RouteValidator::SLUG, 'pass' => ['username']]
    );
});

// Gruppi di utenti
Router::scope('/groups', ['_namePrefix' => 'groups:', 'prefix' => null, 'controller' => 'UserGroups'], function(RouteBuilder $routes) {
    $routes->connect('/', ['action' => 'index'], ['_name' => 'archive']);
    $routes->connect('/create', ['action' => 'add'], ['_name' => 'create']);
    $routes->connect('/join', ['action' => 'join'], ['_name' => 'join']);
    $routes->connect('/leave', ['action' => 'leave'], ['_name' => 'leave']);
});

Router::scope('/my-groups', ['_namePrefix' => 'mygroups:', 'prefix' => 'user'], function(RouteBuilder $routes) {
    $routes->connect('/joined', ['controller' => 'Users', 'action' => 'groups', 'joined'], ['_name' => 'archive']);
    $routes->connect('/joined', ['controller' => 'Users', 'action' => 'groups', 'joined'], ['_name' => 'archive:joined']);
    $routes->connect('/created', ['controller' => 'Users', 'action' => 'groups', 'created'], ['_name' => 'archive:created']);
});

// Gruppi di utenti:  /groups/:id/:slug
Router::scope('/groups/:id/:slug', ['_namePrefix' => 'groups:', 'controller' => 'UserGroups'], function($routes) {
    $routeSettings = ['id' => RouteValidator::ID, 'slug' => RouteValidator::SLUG, 'pass' => ['id']];

    $routes->connect('/', ['action' => 'view'], $routeSettings + ['_name' => 'view']);
    $routes->connect('/members', ['action' => 'members'], $routeSettings + ['_name' => 'members']);
    $routes->connect('/edit', ['action' => 'edit'], $routeSettings + ['_name' => 'edit']);
    $routes->connect('/delete', ['action' => 'delete'], ['_name' => 'delete']);
});

Router::scope('/big-brains', ['_namePrefix' => 'bigbrains:'], function($routes) {
    $routes->connect(
        '/',
        ['plugin' => null, 'prefix' => null, 'controller' => 'BigBrains', 'action' => 'index'],
        ['_name' => 'index']
    );

    $routes->connect(
        '/contact',
        ['plugin' => null, 'prefix' => null, 'controller' => 'BigBrains', 'action' => 'add'],
        ['_name' => 'contact']
    );

    $routes->fallbacks(DashedRoute::class);
});

/**
 * STORES
 */
Router::scope(
    '/store',
    ['_namePrefix' => 'store:', 'plugin' => null, 'prefix' => null],
    function ($routes) {

        // Backend
        $routes->prefix('admin', ['_namePrefix' => 'admin:', 'prefix' => 'admin'], function($routes) {
            $routes->scope('/product', ['_namePrefix' => 'product:', 'prefix' => 'admin'], function($routes) {
                $routes->connect('/', ['controller' => 'StoreProducts', 'action' => 'index'], ['_name' => 'index']);
                $routes->connect('/add', ['controller' => 'StoreProducts', 'action' => 'add'], ['_name' => 'add']);
                $routes->connect('/edit/:id', ['controller' => 'StoreProducts', 'action' => 'edit'], ['_name' => 'edit', 'id' => RouteValidator::ID, 'pass' => ['id']]);
            });

            $routes->scope('/order', ['_namePrefix' => 'order:', 'controller' => 'StoreOrders'], function($routes) {
                $routes->connect('/', ['action' => 'index'], ['_name' => 'index']);
                $routes->connect('/:id', ['action' => 'view'], ['_name' => 'view', 'id' => RouteValidator::ID, 'pass' => ['id']]);
            });
        });

        // Frontend
        $routes->connect('/', ['controller' => 'Stores', 'action' => 'index', 'prefix' => false], ['_name' => 'index']);
        $routes->connect(
            '/archive/:id--:slug',
            ['controller' => 'Stores', 'action' => 'archive', 'prefix' => false],
            ['_name' => 'archive', 'id' => RouteValidator::ID, 'slug' => RouteValidator::SLUG, 'pass' => ['id']]
        );

        $routes->scope('/product', ['_namePrefix' => 'product:', 'controller' => 'Stores'], function($routes) {
            $routes->connect('/buy', ['action' => 'buy'], ['_name' => 'buy_form']);

            $routesValidator = ['slug' => RouteValidator::SLUG, 'id' => RouteValidator::ID, 'pass' => ['id']];
            $routes->connect('/:id--:slug', ['action' => 'view'], ['_name' => 'view'] + $routesValidator);
            $routes->connect('/:id--:slug/buy', ['action' => 'buy'], ['_name' => 'buy'] + $routesValidator);
        });

        $routes->fallbacks(DashedRoute::class);
});


Router::scope('/account', ['_namePrefix' => 'account:', 'controller' => 'UserRegistrations'], function(RouteBuilder $routes) {
    $routes->connect('/incomplete', ['action' => 'requirements'], ['_name' => 'requirements']);

    $routes->connect('/confirm/resend', ['action' => 'confirmation_resend'], ['_name' => 'confirmation_resend']);
    $routes->connect('/confirm/:uuid', ['action' => 'confirmation'], ['_name' => 'confirmation', 'uuid' => RouteValidator::UUID, 'pass' => ['uuid']]);
    $routes->connect('/recovery', ['action' => 'recovery'], ['_name' => 'recovery']);
    $routes->connect('/recovery/:uuid', ['action' => 'reset'], ['_name' => 'reset', 'uuid' => RouteValidator::UUID, 'pass' => ['uuid']]);

});

Router::scope('/backend', ['_namePrefix' => 'backend:'], function($routes) {
    $routes->connect('/', [], ['_name' => 'dashboard']);
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
