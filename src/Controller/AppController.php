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
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\I18n;

use Cake\View\CellTrait;
use Cake\Core\Configure;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    use CellTrait;


    public $paginate = [
        // Other keys here.
        'maxLimit' => 30
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        $this->loadComponent('Security', ['blackHoleCallback' => 'blackhole']);
        // $this->loadComponent('Csrf');

        if (Configure::read('ssl')) {
            $this->Security->requireSecure();
        }

        $this->loadComponent('Auth', [
            'authError'     => __('Per visualizzare questa pagina è richiesto il login'),
            'loginAction'   => ['_name' => 'auth:login'],
            'loginRedirect' => ['_name' => 'me:dashboard'],
            'storage'       => 'Session',
            'authorize'     => 'Controller', // Controller::isAuthorized($user)
            'flash'         => [
                'params' => ['class' => ['alert', 'alert-danger', 'alert-dismissible']]
            ],

            'authenticate' => [
               'Form' => [
                    'finder' => 'auth', // Model/Table/Users::auth()
                    'fields' => ['username' => 'username', 'password' => 'password']
               ],
            ]
        ]);

        // TODO #future:
        // Sarebbe più appropiato configurare l'auth adapter solo se il plugin è caricato
        //
        // if (\Cake\Core\Plugin::loaded('ADmad/HybridAuth')) {
        //     $this->Auth->config('authenticate.ADmad/HybridAuth.HybridAuth', [
        //     ]);
        // }

        if (!$this->request->is('ajax')) {

            switch ($this->request->getParam('prefix')) {
                case 'admin':
                    $layout = 'backend';
                break;

                default:
                    if (
                        $this->request->getParam('controller') == 'Quizzes' &&
                        $this->request->getParam('action') == 'play'
                    ) {
                        $layout = 'frontend-fullscreen';
                    } else {
                        $layout = 'frontend-sidebar';
                    }

                    $this->prepareAdvertising();
            }

            // Set the layout.
            $this->viewBuilder()->setLayout($layout);
        }

        // Default language
        $lang = Configure::readOrFail('app.defaultLanguage');

        if ($this->request->getSession()->check('Auth.User.lang')) {
            $lang = $this->request->getSession()->read('Auth.User.lang');
        } else {
            if ($this->request->getSession()->check('Config.language')) {
                $lang = $this->request->getSession()->read('Config.language');
            }
        }

        // GET overrides
        if (!empty($this->request->getQuery('lang')) && in_array($lang, ['it', 'en', 'ru', 'es', 'fr'])) {
            $lang = $this->request->getQuery('lang');
        }

        $this->request->getSession()->write('Config.language', $lang);
        I18n::setLocale($lang);

        // Entity che contiene l'utente loggato
        // Utilizzato da vari helper (vedi UserHelper)
        if ($this->Auth->user()) {
            $UserAuth = new \App\Model\Entity\User($this->Auth->user(), ['guard' => false]);
            $this->set(compact('UserAuth'));
        }
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        if (!array_key_exists('_serialize', $this->viewBuilder()->getVars()) &&
            in_array($this->response->getType(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    /**
     * Blackhole callback
     *
     * Utilizzato da SecurityComponent
     */
    public function blackhole($errorType)
    {
        // Forza URL con SSL
        if ($errorType == 'secure') {
            $domain = env('APP_DOMAIN', env('SERVER_NAME'));
            return $this->redirect('https://' .$domain. $this->request->getAttribute('here'));
        }

        return null;
    }

    /**
     * Verifica che l'utente possa accedere all'url
     *
     * Utilizzata da AuthComponent
     *
     * @param  [type]  $user [description]
     * @return boolean       [description]
     */
    public function isAuthorized($user = null)
    {
        // Any registered user can access public functions
        if (empty($this->request->getParam('prefix'))) {
            return true;
        }

        // L'admin può accedere a qualsiasi prefix
        // Skip dei vari controlli, ha accesso a tutto.
        if ($user['type'] === 'admin') {
            return true;
        }

        // Prefixes liberi    : user, company, sponsor
        // Prefixes riservati : admin
        if (in_array($this->request->getParam('prefix'), ['user', 'company', 'sponsor'])) {
            return true;
        } else {
            return $this->request->getParam('prefix') == $user['type'];
        }

        return false;
    }

    /**
     * Estrae pubblicità
     *
     * @return void
     */
    public function getAdvertising()
    {
        if ($this->request->is('ajax')) {
            return [];
        }

        // Quando non viene restituita nessuna view non si estra nessuna pubblicità
        // FUTURE:
        // Da valutare il comportamento... potrebbe non essere corretto
        if ($this->autoRender == false) {
            return [];
        }

        // Banner pubblicitari in sidebar
        $this->loadComponent('Advertising');
        return $this->Advertising->get(2, 'banner')->toArray();
    }

    protected function prepareAdvertising()
    {
        $advs = $this->getAdvertising();

        // Se non c'è nessuna pubblicità mostrare adv#0 (banner funjob di richiesta pubblicità)
        if (empty($advs)) {
            $this->loadModel('SponsorAdvs');
            $advs = [];

            $adv = $this->SponsorAdvs->newEntity([
                'uuid'        => '2cc29ea7-2cc3-439e-9aa4-0aaa8aff2ea44',
                'type'        => 'banner',
                'title'       => __('Pubblicità disponibile'),
                'descr'       => __('Acquista angolo pubblicitario'),
                'href'        => \Cake\Routing\Router::url(['_name' => 'funjob:profiles:sponsor']),
                'banner__img' => 'holder.js/200x200?text=' . __('Acquista spazio'),
                'banner__dir' => ''
            ], ['validate' => false, 'guard' => false]);

            $adv->set(['id' => 0], ['guard' => false]);
            $advs[0] = $adv;
        }

        $this->set(compact('advs'));
    }
}
