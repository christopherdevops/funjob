<?php
namespace App\Controller;
use Hybrid_Auth;

use Cake\Core\Configure;

use App\Controller\AppController;

/**
 * Homepages Controller
 *
 * @property \App\Model\Table\HomepagesTable $Homepages
 */
class HybridAuthController extends AppController
{

    public function initialize(): void {
        parent::initialize();

        // $this->loadComponent('Auth');
        $this->Auth->allow(['register', 'login']);

        // $this->Auth->config('authenticate.ADmad/HybridAuth.HybridAuth', [
        //     // All keys shown below are defaults
        //     'fields' => [
        //         'provider'          => 'provider',
        //         'openid_identifier' => 'openid_identifier',
        //         'email'             => 'email'
        //     ],

        //     'profileModel'        => 'ADmad/HybridAuth.SocialProfiles',
        //     'profileModelFkField' => 'user_id',

        //     'userModel'           => 'Users',

        //     // The URL Hybridauth lib should redirect to after authentication.
        //     // If no value is specified you are redirect to this plugin's
        //     // HybridAuthController::authenticated() which handles persisting
        //     // user info to AuthComponent and redirection.
        //     'hauth_return_to' => null
        // ]);
    }

    private function login() {
        if ($this->request->is('post') || $this->request->getQuery('provider')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    public function register_old()
    {
        // if (!$this->request->is('post')) {
        //     throw new \Cake\Network\Exception\ForbiddenException();
        // }

        if (!$this->request->getQuery('provider')) {
            throw new \Cake\Network\Exception\BadRequestException();
        }

        //\Hybrid_Endpoint::process();
        $user = $this->Auth->identify();
        if (empty($user)) {
            throw new \Cake\Network\Exception\ForbiddenException();
        }

        if ($user) {
            $this->Auth->setUser($user);
            return $this->redirect($this->Auth->redirectUrl());
        }

    }

    public function register()
    {
        $this->autoRender = false;

        if (!$this->request->getQuery('provider')) {
            throw new \Cake\Network\Exception\BadRequestException();
        }

        $configSrc = APP. '../config/hybridauth.php';
        if (!file_exists($configSrc)) {
            throw new \Exception('Hybridauth config not exists', 1);
        }

        $config = require $configSrc;
        if (!is_array($config)) {
            throw new \Exception('Hybridauth config fail', 1);
        }

        if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done'])) {
            \Hybrid_Endpoint::process();
        }

        try {
            $provider    = $this->request->getQuery('provider');
            $adapter     = new \Hybrid_Auth($config['HybridAuth']);
            $adapterAuth = $adapter->authenticate($provider);

            //$accessToken = $adapterAuth->getAccessToken();
            $userProfile = $adapterAuth->getUserProfile();

            $this->Flash->success(
                __('Alcuni campi sono stati recuparti da {social}. Procedi con la registrazione di seguito', [
                    'social' => $provider
                ])
            );

            return $this->redirect([
                '_name' => 'auth:register',
                '?'     => [
                    'response' => base64_encode(json_encode($userProfile))
                ]
            ]);
        } catch(\Exception $e){
            echo 'Oops, we ran into an issue! ' . $e->getMessage();
        }
    }

}
