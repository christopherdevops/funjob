<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;

/**
 * CurriculumVitaes Controller
 *
 * @property \App\Model\Table\CurriculumVitaesTable $CurriculumVitaes
 */
class CurriculumVitaesController extends AppController
{
    public $modelClass = false;

    public function initialize() {
        parent::initialize();
        $this->Auth->deny(['delete']);
    }

    public function getAdvertising()
    {
        return false;
    }

    /**
     * View method
     *
     * @param string|null $id Curriculum Vitae id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($uuid, $user_id)
    {
        // aklsfiou7234i0tio3yufgioyadsuiy67fu89yhsdjklfgksdiugds90g
        $this->loadModel('Users');

        $User = $this->Users->get($user_id, [
            'contain' => [
                'AccountInfos' => ['fields' => ['id', 'user_id', 'public_cv', 'cv']]
            ]
        ]);

        $cvSrc = $User->account_info->cv_src;
        if (empty($User->account_info->cv)) {
            throw new NotFoundException(__('CV: Non caricato'));
        } elseif (!file_exists(WWW_ROOT . $cvSrc)) {
            throw new NotFoundException(__('CV: file non trovato'));
        }

        // CV privato: richiede autorizzazione
        if (!$User->account_info->public_cv)
        {
            try {
                $this->_checkViewGrant($User);
            } catch(\Cake\Network\Exception\ForbiddenException $e) {
                $this->Flash->error($e->getMessage());
                return $this->redirect($this->referer('/'));
            }
        }

        $name = $User->username;
        $ext  = pathinfo($cvSrc, PATHINFO_EXTENSION);

        if (!empty($User->fullname)) {
            $name = $User->fullname;
        }

        $out = sprintf('FunJobIT CV - %s.%s', $name, $ext);

        $response = $this->response->withFile(WWW_ROOT . $cvSrc, ['download' => true, 'name' => $out]);
        return $response;
    }

    /**
     * Verifica che l'utente loggato abbia i permessi per poter visualizzare il CV
     *
     * @param  \App\Entity\User $User
     * @throws ForbiddenException
     * @return \Cake\Network\Response|null
     */
    protected function _checkViewGrant($User)
    {
        $this->loadModel('CvAuthorizations');

        if (!$this->Auth->user()) {
            throw new ForbiddenException(__('CV: accedi a FunJob'));
        }

        if ($User->id == $this->Auth->user('id')) {
            return;
        }

        $q = $this->CvAuthorizations->find('byRequester', [
            'requester' => $this->Auth->user('id'),
            'user_id'   => $User->id
        ]);

        $request = $q->first();

        if (empty($request)) {
            return $this->redirect([
                '_name'    => 'user:profile',
                'id'       => $User->id,
                'username' => $User->slug,
                '#'        => 'cv-request'
            ]);
        } elseif ($request->allowed === null) {
            throw new ForbiddenException(__('Non puoi visualizzare il CV perchè l\'utente non ti ha ancora abilitato'));
        } elseif ($request->allowed === false) {
            throw new ForbiddenException(__('Non puoi visualizzare il CV: l\'utente ha rifiutato'));
        }
    }

    public function delete()
    {
        $this->autoRender = false;

        if (!in_array($this->Auth->user('type'), ['admin', 'user'])) {
            throw new ForbiddenException();
        }

        $this->loadModel('UserFields');
        $UserField = $this->UserFields->findByUserId($this->Auth->user('id'))->select(['id', 'cv', 'user_id'])->first();

        if (empty($UserField)) {
            $this->Flash->error(__('Eliminazione fallita'));
            return $this->redirect($this->referer());
        }

        // Rimuove Behavior altrimenti imposta nuovamente il campo CV con hashids
        $filename = $UserField->cvSrc;

        $this->UserFields->removeBehavior('Upload');
        $this->UserFields->patchEntity($UserField, ['cv' => null]);

        if ($this->UserFields->save($UserField)) {
            if (empty($filename)) {
                @unlink($filename);
            }

            $this->Flash->success(__('CV eliminato correttamente'));
            return $this->redirect($this->referer());
        }


        $this->Flash->error(__('Eliminazione fallita'));
        return $this->redirect($this->referer());
    }

}
