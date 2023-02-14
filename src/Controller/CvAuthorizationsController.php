<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\ForbiddenException;

/**
 * CvAuthorizations Controller
 *
 * @property \App\Model\Table\CvAuthorizationsTable $CvAuthorizations
 */
class CvAuthorizationsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->deny();

        if (!in_array($this->Auth->user('type'), ['user', 'admin'])) {
            throw new ForbiddenException(__('Funzionalità riservata ai soli utenti privati'));
        }

        $this->loadComponent('Csrf');
    }

    public function getAdvertising()
    {
        if ($this->request->is('ajax')) {
            return false;
        }

        parent::getAdvertising();
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $user_id = $this->Auth->user('id');

        // Count su stati
        $pending = $this->CvAuthorizations->find('archive', compact('user_id'))->find('pending')->count();
        $granted = $this->CvAuthorizations->find('archive', compact('user_id'))->find('allowed')->count();
        $denied  = $this->CvAuthorizations->find('archive', compact('user_id'))->find('denied')->count();
        $counter = compact('pending', 'granted', 'denied');

        // Se l'utente ha un CV e se necessita di autorizzazioni
        $this->loadModel('Users');


        $user_cv = $this->Users->get($this->Auth->user('id'), [
            'fields'  => ['id'],
            'contain' => [
                'AccountInfos' => ['fields' => ['cv', 'public_cv']]
            ]
        ]);

        $this->set(compact('counter', 'user_cv'));
        $this->set('_serialize', ['counter']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function filter($byState = null)
    {

        if (!$this->request->is('ajax')) {
            throw new \Cake\Network\Exception\ForbiddenException();
        }

        $q = $this->CvAuthorizations->find('archive', [
            'user_id' => $this->Auth->user('id')
        ]);

        if (in_array($byState, ['pending', 'allowed', 'denied'])) {
            $q->find($byState);
        }

        $cvAuthorizations = $this->paginate($q, ['limit' => 30]);

        $this->set(compact('cvAuthorizations'));
        $this->set('_serialize', ['cvAuthorizations']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->autoRender = false;

        $CvAuthorization = $this->CvAuthorizations->newEntity();

        $this->request->data('requester_user_id', $this->Auth->user('id'));
        $this->request->data('allowed', null);

        if ($this->request->is('post')) {
            $CvAuthorization = $this->CvAuthorizations->patchEntity($CvAuthorization, $this->request->getData());
            if ($this->CvAuthorizations->save($CvAuthorization)) {
                $this->Flash->success(__x('Autorizzazione CV creata', 'Richiesta inviata: attendi che venga abilitata dall\'utente'));
                return $this->redirect($this->referer('/'));
            }

            // Tramite buildRules() isUnique verifico che l'utente non abbia già
            // inviato una richiesta. Se sì, lo redirecto alla pagina del profilo utente
            // come se avessi nuovamente salvato l'entity
            $errors = $CvAuthorization->errors();
            if (isset($errors['user_id']['alreadySent'])) {
                $this->Flash->success(__x('Autorizzazione CV creata', 'Richiesta inviata: attendi che venga abilitata dall\'utente'));
                return $this->redirect($this->referer('/'));
            }
        }

        $this->set(compact('CvAuthorization'));
        $this->set('_serialize', ['CvAuthorization']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Cv Authorization id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->autoRender = false;

        if (!$this->request->is('ajax')) {
            throw new \Cake\Network\Exception\ForbiddenException();
        }

        $cvAuthorization = $this->CvAuthorizations->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $cvAuthorization = $this->CvAuthorizations->patchEntity($cvAuthorization, $this->request->getData(), [
                'fieldList' => ['allowed']
            ]);

            $this->CvAuthorizations->saveOrFail($cvAuthorization);
        }

        $this->set(compact('cvAuthorization'));
        $this->set('_serialize', ['cvAuthorization']);
    }

}
