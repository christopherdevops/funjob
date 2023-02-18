<?php
namespace App\Controller;

use Cake\Collection\Collection;
use Cake\Core\Configure;

use App\Controller\AppController;
use App\Form\BigBrainContactForm;

/**
 * Bigbrains Controller
 *
 * @property \App\Model\Table\BigbrainsTable $Bigbrains
 */
class BigbrainsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->Auth->allow();

        $this->loadComponent('Recaptcha.Recaptcha', [
            'enable'  => true,     // true/false
            'type'    => 'image',  // image/audio
            'theme'   => 'light', // light/dark

            'sitekey' => Configure::read('recaptca_keys.key'),
            'secret'  => Configure::read('recaptca_keys.secret'),
            'lang'    => Configure::read('lang')
        ]);

        if ($this->request->getParam('action') == 'add') {
            // $this->loadComponent('Csrf');
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->loadModel('Users');
        $q = $this->Users->find();

        $q->select(['id', 'username', 'first_name', 'last_name', 'title', 'avatar', 'bigbrain_area']);

        // Confermato da Giuseppe in data 19 Luglio 2017:
        // I bigbrain potranno essere solo privati
        $q->where(['is_bigbrain' => true, 'type IN' => ['user', 'admin']]);
        $q->contain([
            'AccountInfos' => function($q) {
                return $q->select(['profession']);
            }
        ]);

        //$q->order(['bigbrain_from' => 'DESC']);

        $bigbrains = $this->paginate($q, ['limit' => 25]);
        $BigBrainContactForm = new BigBrainContactForm();

        $this->set(compact('bigbrains', 'BigBrainContactForm'));
        $this->set('_serialize', ['bigbrains']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['GET', 'POST']);
        $BigBrainContactForm = new BigBrainContactForm();

        if ($this->request->is('post')) {
            if ($this->Recaptcha->verify()) {
                if ($BigBrainContactForm->execute($this->request->data)) {
                    $this->Flash->success(__(
                        'Grazie! La tua richiesta verrà analizzata e verrai ricontattato su {0}',
                        $this->request->data['email']
                    ));

                    return $this->redirect($this->referer('/'));
                } else {
                    debug($BigBrainContactForm->getErrors());
                }
            } else {
                $this->Flash->error(__('"NON SONO UN ROBOT" errato... ritenta'));
            }
        }

        $this->set(compact('BigBrainContactForm'));
    }

}
