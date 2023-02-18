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
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    /*
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
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
    $routes->setRouteClass(DashedRoute::class);
    
    $routes->scope('/', function (RouteBuilder $builder) {
        $builder->setExtensions(['json']);

        /**
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, src/Template/Pages/home.ctp)...
         */
        $builder->connect('/', ['controller' => 'Homepages', 'action' => 'home'], ['_name' => 'home']);

        if (Configure::read('Maintenance.enabled')) {
            $builder->connect('/maintenance', ['controller' => 'Homepages', 'action' => 'maintenance'], ['_name' => 'maintenance']);
        }

        /**
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        $builder->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

        /**
         * Connect catchall routes for all controllers.
         *
         * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
         *    `$builder->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
         *    `$builder->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
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
        $builder->fallbacks(DashedRoute::class);
    });

    // Sito informativo
    $routes->scope('/funjob', ['_namePrefix' => 'funjob:', 'prefix' => false], function(RouteBuilder $builder) {
        $builder->connect('/funjob', ['controller' => 'Pages', 'action' => 'display', 0 => 'funjob'], ['_name' => 'whois']);

        $builder->connect(
            '/profiles',
            ['controller' => 'FunJobPages', 'action' => 'display', 0 => 'index'],
            ['_name' => 'profiles']
        );
        $builder->connect(
            '/profiles/users',
            ['controller' => 'FunJobPages', 'action' => 'display', 0 => 'profiles', 1 => 'user'],
            ['_name' => 'profiles:user']
        );
        $builder->connect(
            '/profiles/companies',
            ['controller' => 'FunJobPages', 'action' => 'display', 0 => 'profiles', 1 => 'company'],
            ['_name' => 'profiles:company']
        );
        $builder->connect(
            '/profiles/sponsors',
            ['controller' => 'FunJobPages', 'action' => 'display', 0 => 'profiles', 1 => 'sponsor'],
            ['_name' => 'profiles:sponsor']
        );


        $builder->connect('/', ['controller' => 'FunJobPages', 'action' => 'display', 0 => 'index'], ['_name' => 'info']);
        $builder->connect('/pages/*', ['controller' => 'FunJobPages', 'action' => 'display']);
        $builder->connect('/terms', ['prefix' => false, 'controller' => 'Pages', 'action' => 'display', 0 => 'terms_and_conditions'], ['_name' => 'terms']);
        $builder->connect('/cookies', ['prefix' => false, 'controller' => 'Pages', 'action' => 'display', 0 => 'cookie_policy'], ['_name' => 'cookies']);

        $builder->fallbacks(DashedRoute::class);
    });

    // Pubblicità
    // Se si utilizza nell'URL una parola come adv viene automaticamente bloccato da adblock (o affini)
    $routes->scope('/static/assets', ['_namePrefix' => 'adv:'], function($builder) {
        $builder->setExtensions(['json']);

        $builder->connect('/list', ['controller' => 'SponsorAdvs', 'action' => 'index'], ['_name' => 'active']);

        // Preleva pubblicità corrente e decrementa impressions
        $builder->connect('/get', ['controller' => 'SponsorAdvs', 'action' => 'index'], ['_name' => 'get']);

        $builder->connect(
            '/:uuid',
            ['controller' => 'SponsorAdvs', 'action' => 'image'],
            [
                //'filename' => '[0-9]+',
                'uuid'       => RouteValidator::UUID,
                'pass'     => ['uuid'],
                '_name'    => 'image'
        ]);
        $builder->connect(
            '/goto/:uuid',
            ['controller' => 'SponsorAdvs', 'action' => 'track'],
            [
                '_name' => 'track',
                'uuid'  => '[A-Za-z0-9\-]+',
                'pass'  => ['uuid']
            ]
        );

        $builder->fallbacks(DashedRoute::class);
    });

    // Quiz
    $routes->scope('/quiz', ['_namePrefix' => 'quiz:'], function (RouteBuilder $builder) {
        $builder->connect('/', ['controller' => 'Quizzes', 'action' => 'index'], ['_name' => 'index']);
        $builder->connect('/popular', ['controller' => 'Quizzes', 'action' => 'popular'], ['_name' => 'popular']);
        $builder->connect('/create', ['prefix' => null, 'controller' => 'Quizzes', 'action' => 'add'], ['_name' => 'create']);

        // Informazioni quiz (start sessione di gioco)
        $builder->connect(
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
        $builder->connect(
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
        $builder->connect(
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
        $builder->connect(
            '/:id-:title/score',
            ['controller' => 'Quizzes', 'action' => 'score'],
            [
                'id'    => RouteValidator::ID,
                'title' => RouteValidator::SLUG,

                '_name' => 'score',
                'pass'  => ['id']
            ]
        );


        $builder->scope('/report', function($builder) {

            // Log di sessione di gioco (in profilo utente)
            $builder->connect(
                '/log/:id',
                ['controller' => 'QuizSessionLevels', 'action' => 'view'],
                [
                    '_name' => 'replies',
                    'id'    => RouteValidator::ID,
                    'pass'  => ['id']
                ]
            );

            $builder->connect(
                '/detail/:id',
                ['controller' => 'QuizSessions', 'action' => 'view'],
                [
                    '_name' => 'report',
                    'id'    => RouteValidator::ID,
                    'pass'  => ['id']
                ]
            );

        });

        $builder->scope(
            '/categories',
            ['_namePrefix' => 'categories:', 'controller' => 'QuizCategoryBrowsers'],
            function($builder) {
                // Archivio
                $builder->connect(
                    '/',
                    ['plugin' => false, 'action' => 'index'],
                    [
                        '_name'  => 'archive',
                        'pass'  => []
                    ]
                );
                // Archivio sottocategoria
                $builder->connect(
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


        // $builder->connect(
        //     '/randomize',
        //     ['plugin' => false, 'prefix' => null, 'controller' => 'quizzes', 'action' => 'rand'],
        //     [
        //         '_name' => 'rand',
        //     ]
        // );

        // Editor
        $builder->connect(
            '/edit/:id',
            ['prefix' => null, 'controller' => 'quizzes', 'action' => 'edit'],
            ['_name' => 'edit', 'id' => '\d+', 'pass' => ['id']]
        );
        $builder->connect(
            '/delete/:id',
            ['prefix' => null, 'controller' => 'quizzes', 'action' => 'delete'],
            ['_name' => 'delete', 'id' => '\d+', 'pass' => ['id']]
        );

        $builder->fallbacks(DashedRoute::class);
    });

    $routes->scope(
        '/quiz/ranking/',
        ['_namePrefix' => 'quiz:ranking:', 'plugin' => null, 'prefix' => 'User', 'controller' => 'QuizUserRankings'],
        function ($builder) {
            $builder->connect('/add', ['action' => 'add'], ['_name' => 'add']);
            $builder->connect('/edit/:id', ['action' => 'edit'], ['_name' => 'edit', 'id' => RouteValidator::ID, 'pass' => ['id']]);
        }
    );

    // QuizCategory
    $routes->scope('/quiz/categories', ['_namePrefix' => 'quiz-categories:'], function(RouteBuilder $builder) {
        $builder->connect('/', ['prefix' => null, 'controller' => 'QuizCategories', 'action' => 'index'], ['_name' => 'search']);
        $builder->connect(
            '/:title--:id',
            ['prefix' => null, 'controller' => 'Quizzes', 'action' => 'browse'],
            [
                '_name' => 'browse',
                'id'    => RouteValidator::ID,
                'title' => RouteValidator::SLUG,
                'pass'  => ['id']
            ]
        );

        $builder->fallbacks(DashedRoute::class);
    });

    // CV
    $routes->scope('/cv/', ['_namePrefix' => 'cv:'], function(RouteBuilder $builder) {
        $builder->connect(
            '/:uuid/view/:user_id',
            ['controller' => 'CurriculumVitaes', 'action' => 'view'],
            [
                '_name'   => 'view',
                'uuid'    => RouteValidator::UUID,
                'user_id' => RouteValidator::ID,
                'pass'    => ['uuid', 'user_id']
            ]
        );

        $builder->connect(
            '/:uuid/request',
            ['prefix' => null, 'plugin' => null, 'controller' => 'CvAuthorizations', 'action' => 'add'],
            [
                '_name' => 'request',
                'uuid'  => RouteValidator::UUID,
                'pass'  => ['uuid']
            ]
        );

        $builder->connect(
            '/delete',
            ['prefix' => null, 'plugin' => null, 'controller' => 'CurriculumVitaes', 'action' => 'delete'],
            ['_name' => 'delete']
        );

        $builder->scope('/authorizations', function($builder) {
            $builder->connect(
                '/',
                ['prefix' => null, 'plugin' => null, 'controller' => 'CvAuthorizations', 'action' => 'index'],
                [
                    '_name' => 'authorizations:archive',
                    'pass'  => []
                ]
            );
            $builder->connect(
                '/filter/:state',
                ['prefix' => null, 'plugin' => null, 'controller' => 'CvAuthorizations', 'action' => 'filter'],
                [
                    '_name' => 'authorizations:filter',
                    'state' => '(pending|allowed|denied)',
                    'pass'  => ['state']
                ]
            );
            $builder->connect(
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
    $routes->scope('/me', ['_namePrefix' => 'me:'], function(RouteBuilder $builder) {
        $builder->connect('/', ['prefix' => null, 'controller' => 'users', 'action' => 'view'], ['_name' => 'profile']);
        $builder->connect('/dashboard', ['prefix' => null, 'controller' => 'UserDashboards', 'action' => 'index'], ['_name' => 'dashboard']);
        $builder->connect('/disable-account', ['prefix' => null, 'controller' => 'Users', 'action' => 'disable'], ['_name' => 'disable']);

        // Impostazioni (redirecta a /settings/:prefix)
        // Perchè ogni tipologia di Utente (user/company) ha impostazioni differente
        $builder->connect(
            '/settings/',
            ['controller' => 'users', 'action' => 'settings'],
            ['_name' => 'settings']
        );
        $builder->connect(
            '/settings/:prefix',
            ['controller' => 'users', 'action' => 'settings'],
            ['_name' => 'settings:prefixed']
        );

        $builder->scope('/quizzes', function (RouteBuilder $builder) {
            $builder->connect('/', ['prefix' => 'User', 'controller' => 'users', 'action' => 'quizzes'], ['_name' => 'quizzes']);

            // Quiz svolti (pubblico)
            //$builder->connect('/completed', ['prefix' => null, 'controller' => 'users', 'action' => 'quizCompleted'], ['_name' => 'quizzes:completed']);

            // Quiz svolti parte backend (pubblico)
            $builder->connect('/completed', ['prefix' => 'User', 'controller' => 'Quizzes', 'action' => 'played'], ['_name' => 'quizzes:completed']);
            $builder->connect('/created', ['prefix' => null, 'controller' => 'users', 'action' => 'quizCreated'], ['_name' => 'quizzes:created']);
        });

        //$builder->connect('/credits', ['prefix' => 'User', 'controller' => 'users', 'action' => 'credits'], ['_name' => 'credits']);
        $builder->connect('/orders', ['prefix' => 'User', 'controller' => 'StoreOrders', 'action' => 'index'], ['_name' => 'orders']);

        $builder->fallbacks(DashedRoute::class);
    });

    // User
    $routes->prefix('user', ['_namePrefix' => 'user:'], function (RouteBuilder $builder) {

        $routeContext = ['_namePrefix' => 'profile:', 'prefix' => null, 'controller' => 'users'];
        $builder->scope('/:id-:username/', $routeContext, function ($builder) {
            $routeParams  = [
                'pass'     => ['id'],
                'id'       => RouteValidator::ID,
                'username' => RouteValidator::SLUG
                //'fullname' => RouteValidator::SLUG
            ];

            $builder->connect('/', ['action' => 'view'], $routeParams + ['_name' => 'home']);
            $builder->connect('/friends', ['action' => 'friends'], $routeParams + ['_name' => 'friends']);
            $builder->connect('/groups', ['action' => 'groups'], $routeParams + ['_name' => 'groups']);
        });

        // Profilo pubblico utente
        $builder->connect(
            '/:id-:username',
            ['prefix' => null, 'controller' => 'users', 'action' => 'view'],
            [
            '_name'    => 'profile',
            'id'       => '\d+',
            'username' =>  RouteValidator::SLUG,
            'pass'     => ['id']
            ]
        );

        $builder->prefix('quizzes/', function (RouteBuilder $builder) {
            $builder->connect('/', ['prefix' => null, 'controller' => 'Quizzes', 'action' => 'index'], ['_name' => 'index']);

            // Quiz completati
            $builder->connect(
                'completed/:user_id',
                ['prefix' => null, 'controller' => 'users', 'action' => 'quizCompleted'],
                ['_name' => 'quizzes:completed', 'pass' => ['user_id'], 'user_id' => RouteValidator::ID]
            );

            // Quiz completati
            $builder->connect(
                'created/:user_id/:type',
                ['prefix' => null, 'controller' => 'users', 'action' => 'quizCreated', 'type' => 'default'],
                ['_name' => 'quizzes:created', 'pass' => ['user_id'], 'user_id' => '\d+']
            );
        });

        $builder->connect(
            '/search',
            ['plugin' => false, 'controller' => 'users', 'action' => 'search'],
            ['_name' => 'search']
        );

        $builder->fallbacks(DashedRoute::class);
    });

    $routes->scope('/companies', ['_namePrefix' => 'companies:', 'prefix' => null,  'controller' => 'Companies'], function($builder) {
        $builder->connect('/', ['action' => 'index'], ['_name' => 'index']);
        $builder->connect(
            '/:id--:username',
            ['action' => 'view'],
            ['_name' => 'profile', 'id' => RouteValidator::ID, 'username' => RouteValidator::SLUG, 'pass' => ['id']]
        );

        $builder->scope(
            '/browse',
            ['_namePrefix' => 'categories:', 'controller' => 'CompanyCategoriesBrowsers'],
            function($builder) {
                // Archivio
                $builder->connect(
                    '/',
                    ['plugin' => false, 'action' => 'index'],
                    [
                        '_name'  => 'archive',
                        'pass'  => []
                    ]
                );
                // Archivio sottocategoria
                $builder->connect(
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
    $routes->prefix('admin', function (RouteBuilder $builder) {

        $builder->connect('/quiz-categories/', ['controller' => 'quiz-categories', 'action' => 'index'], ['_name' => 'admin/quiz-categories/index']);
        $builder->connect('/quiz-categories/add', ['controller' => 'quiz-categories', 'action' => 'add'], ['_name' => 'admin/quiz-categories/add']);
        $builder->connect('/quiz-categories/edit/:id', ['controller' => 'quiz-categories', 'action' => 'edit'], ['pass' => ['id']], ['_name' => 'admin/quiz-categories/edit']);

        $builder->fallbacks(DashedRoute::class);
    });

    // User (company)
    // Prefisso utilizzato da gli users con role (company)
    $routes->prefix('company', function ($builder) {
        $builder->setExtensions(['json']);
        $builder->fallbacks(DashedRoute::class);
    });

    // User (sponsor)
    // Prefisso utilizzato da gli users con role (sponsor)
    $routes->prefix('sponsor', function ($builder) {
        $builder->fallbacks(DashedRoute::class);
    });

    // Autenticazione
    $routes->scope('/auth', ['_namePrefix' => 'auth:'], function(RouteBuilder $builder) {
        $builder->connect('/register', ['prefix' => null, 'controller' => 'users', 'action' => 'register'], ['_name' => 'register']);
        $builder->connect('/login', ['prefix' => null, 'controller' => 'users', 'action' => 'login'], ['_name' => 'login']);
        $builder->connect('/logout', ['prefix' => null, 'controller' => 'users', 'action' => 'logout'], ['_name' => 'logout']);

        $builder->connect('/endpoint/register', ['plugin' => null, 'controller' => 'HybridAuth', 'action' => 'register'], ['_name' => 'register:hybrid']);
        $builder->fallbacks(DashedRoute::class);
    });

    // Leaderboard
    $routes->scope('/leaderboard', ['_namePrefix' => 'leaderboard:'], function(RouteBuilder $builder) {
        $builder->connect('/', ['prefix' => null, 'controller' => 'leaderboards', 'action' => 'index'], ['_name' => 'index']);
        $builder->fallbacks(DashedRoute::class);
    });

    // Private message system
    $routes->scope('/messages', ['_namePrefix' => 'message:'], function (RouteBuilder $builder) {
        $builder->connect(
            '/',
            ['plugin' => false, 'prefix' => 'User', 'controller' => 'UserMessages', 'action' => 'index'],
            ['_name' => 'archive']
        );
        $builder->connect(
            '/conversation/:uuid',
            ['plugin' => false, 'prefix' => 'User', 'controller' => 'UserMessages', 'action' => 'view'],
            ['_name' => 'view', 'uuid' => RouteValidator::UUID, 'pass' => ['uuid']]
        );

        $builder->connect(
            '/compose',
            ['plugin' => false, 'prefix' => 'User', 'controller' => 'UserMessages', 'action' => 'add', 'username' => null],
            ['_name' => 'compose', 'username' => '(^$|' . RouteValidator::SLUG .')', 'pass' => ['username']]
        );
        $builder->connect(
            '/compose/:username',
            ['plugin' => false, 'prefix' => 'User', 'controller' => 'UserMessages', 'action' => 'add', 'username' => null],
            ['_name' => 'compose:username', 'username' => RouteValidator::SLUG, 'pass' => ['username']]
        );
    });

    // Gruppi di utenti
    $routes->scope('/groups', ['_namePrefix' => 'groups:', 'prefix' => null, 'controller' => 'UserGroups'], function(RouteBuilder $builder) {
        $builder->connect('/', ['action' => 'index'], ['_name' => 'archive']);
        $builder->connect('/create', ['action' => 'add'], ['_name' => 'create']);
        $builder->connect('/join', ['action' => 'join'], ['_name' => 'join']);
        $builder->connect('/leave', ['action' => 'leave'], ['_name' => 'leave']);
    });

    $routes->scope('/my-groups', ['_namePrefix' => 'mygroups:', 'prefix' => 'User'], function(RouteBuilder $builder) {
        $builder->connect('/joined', ['controller' => 'Users', 'action' => 'groups', 'joined'], ['_name' => 'archive']);
        $builder->connect('/joined', ['controller' => 'Users', 'action' => 'groups', 'joined'], ['_name' => 'archive:joined']);
        $builder->connect('/created', ['controller' => 'Users', 'action' => 'groups', 'created'], ['_name' => 'archive:created']);
    });

    // Gruppi di utenti:  /groups/:id/:slug
    $routes->scope('/groups/:id/:slug', ['_namePrefix' => 'groups:', 'controller' => 'UserGroups'], function($builder) {
        $builderettings = ['id' => RouteValidator::ID, 'slug' => RouteValidator::SLUG, 'pass' => ['id']];

        $builder->connect('/', ['action' => 'view'], $builderettings + ['_name' => 'view']);
        $builder->connect('/members', ['action' => 'members'], $builderettings + ['_name' => 'members']);
        $builder->connect('/edit', ['action' => 'edit'], $builderettings + ['_name' => 'edit']);
        $builder->connect('/delete', ['action' => 'delete'], ['_name' => 'delete']);
    });

    $routes->scope('/big-brains', ['_namePrefix' => 'bigbrains:'], function($builder) {
        $builder->connect(
            '/',
            ['plugin' => null, 'prefix' => null, 'controller' => 'BigBrains', 'action' => 'index'],
            ['_name' => 'index']
        );

        $builder->connect(
            '/contact',
            ['plugin' => null, 'prefix' => null, 'controller' => 'BigBrains', 'action' => 'add'],
            ['_name' => 'contact']
        );

        $builder->fallbacks(DashedRoute::class);
    });

    /**
     * STORES
     */
    $routes->scope(
        '/store',
        ['_namePrefix' => 'store:', 'plugin' => null, 'prefix' => null],
        function ($builder) {

            // Backend
            $builder->prefix('admin', ['_namePrefix' => 'admin:', 'prefix' => 'Admin'], function($builder) {
                $builder->scope('/product', ['_namePrefix' => 'product:', 'prefix' => 'Admin'], function($builder) {
                    $builder->connect('/', ['controller' => 'StoreProducts', 'action' => 'index'], ['_name' => 'index']);
                    $builder->connect('/add', ['controller' => 'StoreProducts', 'action' => 'add'], ['_name' => 'add']);
                    $builder->connect('/edit/:id', ['controller' => 'StoreProducts', 'action' => 'edit'], ['_name' => 'edit', 'id' => RouteValidator::ID, 'pass' => ['id']]);
                });

                $builder->scope('/order', ['_namePrefix' => 'order:', 'controller' => 'StoreOrders'], function($builder) {
                    $builder->connect('/', ['action' => 'index'], ['_name' => 'index']);
                    $builder->connect('/:id', ['action' => 'view'], ['_name' => 'view', 'id' => RouteValidator::ID, 'pass' => ['id']]);
                });
            });

            // Frontend
            $builder->connect('/', ['controller' => 'Stores', 'action' => 'index', 'prefix' => false], ['_name' => 'index']);
            $builder->connect(
                '/archive/:id--:slug',
                ['controller' => 'Stores', 'action' => 'archive', 'prefix' => false],
                ['_name' => 'archive', 'id' => RouteValidator::ID, 'slug' => RouteValidator::SLUG, 'pass' => ['id']]
            );

            $builder->scope('/product', ['_namePrefix' => 'product:', 'controller' => 'Stores'], function($builder) {
                $builder->connect('/buy', ['action' => 'buy'], ['_name' => 'buy_form']);

                $builderValidator = ['slug' => RouteValidator::SLUG, 'id' => RouteValidator::ID, 'pass' => ['id']];
                $builder->connect('/:id--:slug', ['action' => 'view'], ['_name' => 'view'] + $builderValidator);
                $builder->connect('/:id--:slug/buy', ['action' => 'buy'], ['_name' => 'buy'] + $builderValidator);
            });

            $builder->fallbacks(DashedRoute::class);
    });

    $routes->scope('/account', ['_namePrefix' => 'account:', 'controller' => 'UserRegistrations'], function(RouteBuilder $builder) {
        $builder->connect('/incomplete', ['action' => 'requirements'], ['_name' => 'requirements']);
    
        $builder->connect('/confirm/resend', ['action' => 'confirmation_resend'], ['_name' => 'confirmation_resend']);
        $builder->connect('/confirm/:uuid', ['action' => 'confirmation'], ['_name' => 'confirmation', 'uuid' => RouteValidator::UUID, 'pass' => ['uuid']]);
        $builder->connect('/recovery', ['action' => 'recovery'], ['_name' => 'recovery']);
        $builder->connect('/recovery/:uuid', ['action' => 'reset'], ['_name' => 'reset', 'uuid' => RouteValidator::UUID, 'pass' => ['uuid']]);
    
    });

    $routes->scope('/backend', ['_namePrefix' => 'backend:'], function(RouteBuilder $builder) {
        $builder->connect('/', [], ['_name' => 'dashboard']);
    });
};