<?php
namespace App\Controller\Company;

use App\Controller\AppController;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('Csrf');

        // Redirect a settings in base al prefix

    }

    /**
     * Configurazione account FunJob
     */
    public function settings() {
        if (!in_array($this->Auth->user('type'), ['company'])) {
            return $this->redirect(['_name' => 'me:settings']);
        }

        $this->loadModel('CompanyCategories');
        $this->Users = TableRegistry::get('Companies');

        $User = $this->Users->get($this->Auth->user('id'), [
            'contain' => [
                'AccountInfos',
                'ProfileBlocks',
                'Categories',
            ]
        ]);

        if ($this->request->is('put')) {
            // Aggiunge property a UserEntity per poter fare la validazione di password_confirm
            $User->password_confirm = $User->password;
            // Non permette di aggiornare l'email (non uso un campo hidden percui non può farlo il SecurityComponent questo controllo)
            $User->email = $User->email;

            $data = $this->request->getData();

            // Cambio password: imposta password attuale se non definita
            if (!$this->request->getData('password')) {
                $data['password']         = $User->password;
                $data['password_confirm'] = $User->password;
            }

            $validateMathod = null;
            switch ($this->request->getData('_tab')) {
                case 'account':
                    $validateMathod = 'settingsAccountCompany';
                break;

                case 'profile':
                    $validateMathod = 'settingsProfileCompany';
                break;

                case 'job':
                    $validateMathod = 'settingsJobCompany';
                break;
            }

            $this->Users->patchEntity($User, $data, [
                'validate'   => $validateMathod,
                'associated' => [
                    'AccountInfos',
                    'ProfileBlocks',
                    'Categories'
                ]
            ]);

            $updateFields      = $User->getDirty();
            $updateFieldValues = [];
            foreach ($updateFields as $key) {
                $updateFieldValues[$key] = $this->request->getData($key);
            }

            $setup = [];
            if ($this->Users->save($User, $setup)) {

                if ($this->Auth->user()) {
                    // Utilizzato per sovrascrivere la sessione Auth con i nuovi
                    // valori aggiornati
                    $Event = new Event('Controller.User.Settings.Updated', $User, [
                        'fields' => $updateFieldValues
                    ]);

                    $this->eventManager()->dispatch($Event);
                    $result = $Event->getResult();

                    if (!empty($result['session'])) {
                        foreach ($result['session'] as $key => $value) {
                            $this->request->getSession()->write($key, $value);
                        }
                    }
                }

                $this->Flash->success(__('Opzioni aggiornate'));
                return $this->redirect($this->referer(['action' => 'settings']));
            } else {
                $this->Flash->error(__('Correggi i campi errati e riprova'));
            }
        }

        $langs = Configure::readOrFail('app.languages');
        $companyCategories = $this->CompanyCategories->find('treeList', ['spacer' => '- ']);

        $this->set(compact('User', 'companyCategories', 'langs'));
    }


    /**
     * Ricerca aziende
     */
    public function search()
    {
        $form  = new \App\Form\UserJobOffersForm;
        $isSearch = $this->request->getQuery('_do');

        if ($isSearch) {

            // Imposta variabili GET come POST
            if ($this->request->is('get')) {
                $this->request->data = $this->request->query();
            }

            if ($form->validate($this->request->getData())) {
                $query = $this->Users->find('company');
                $query->select(['Users.id', 'Users.username', 'Users.first_name', 'Users.last_name', 'Users.avatar']);

                // UserSkills matching restituisce più righe per lo stesso utente
                $query->distinct(['Users.id']);

                // TODO: wrap in find*
                // if (!empty($this->request->getData('role'))) {
                //     $query->contain(['JobOffers']);
                //     $query->matching('JobOffers', function($q) {
                //         return $q->where(['job_id' => (int) $this->request->data['role']]);
                //     });
                // }

                // Filtro: nome e cognome
                if ($this->request->getData('fullname')) {
                    $fullname = filter_var($this->request->getData('fullname'));

                    $query->where(function($exp, $q) {
                        $concat = $q->func()->concat([
                            'first_name' => 'identifier',
                            ' ',
                            'last_name'  => 'identifier'
                        ]);

                        //return $exp->like($concat, '%' .$this->request->getData('fullname'). '%');
                        return $exp->eq($concat, $this->request->getData('fullname'));
                    });
                }

                // Filtro: età
                // TODO: spostare su componente
                if ($this->request->getData('age_from')) {
                    $query->where(function($exp, $q) {
                        return $q->newExpr()->add( sprintf('(YEAR(CURRENT_TIMESTAMP) - YEAR(Users.birthday)) >= %d', $this->request->data('age_from')) );
                    });
                }

                if ($this->request->getData('age_to')) {
                    $query->where(function($exp, $q) {
                        return $q->newExpr()->add( sprintf('(YEAR(CURRENT_TIMESTAMP) - YEAR(Users.birthday)) <= %d', $this->request->data('age_to')) );
                    });
                }

                // Città
                if ($this->request->getData('city_id')) {
                    $query->where(['live_city_id' => $this->request->data['city_id']]);
                }

                // SKILL tags
                if ($this->request->getData('skills')) {
                    // TODO #future:
                    // Vengono usati due query (matching e contain) per i seguenti motivi:
                    // 1. il matching serve per scartare gli utenti che non hanno le skills specificate
                    // 2. il contain viene usato per prelevare tutte le skill tags che matchano la ricerca (purtroppo il matching _matchingData tiene conto solo di una SkillTags)
                    $this->_searchFilterSkillMatching($query);
                    $this->_searchFilterSkillContain($query);
                }

                $this->loadComponent('Paginator');
                $users = $this->Paginator->paginate($query, ['limit' =>  30]);
            }
        }


        //$this->loadModel('JobCategories');
        //$jobs   = $this->JobCategories->find('list')->all();
        //$cities = [];
        //$cities = \Cake\ORM\TableRegistry::get('Cities')->find('list');

        $this->set(compact('users', 'jobs', 'cities', 'form', 'isSearch'));
        $this->set('_serialize', ['users']);
        $this->render();
    }

}
