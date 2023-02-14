<?php
namespace App\Controller\User;

use App\Controller\AppController;

use Cake\Event\Event;
use Cake\Core\Configure;

class UsersController extends AppController
{
    public $paginate = [
        'maxLimit' => 30
    ];

    public function initialize()
    {
        parent::initialize();


        if ($this->request->action == 'quizzes') {
            $this->loadComponent('Security');
        } elseif ($this->request->action == 'index') {
            $this->loadComponent('Security');

            // Le città vengono impostate tramite typeahead attraverso dei campi hidden
            // Devono essere quindi eliminate dal controllo
            // $ignoreFields = ['born_city_id', 'live_city_id'];
            //
            // NB:
            // Vengono dichiarati direttamente dentro il form attraverso $this->Form->ignoreField()
            // poichè ogni tab di configurazione utilizzata form differenti

            // Il campo cover viene impostato tramite un blockView
            // Il FormHelper sembra che non lo aggiunge nella lista dei campi permessi a quanto pare
            //$ignoreFields[] = 'background_cover';
            //$this->Security->config('unlockedFields', $ignoreFields);
        }

        // Redirect a settings in base al prefix
        if ($this->request->action == 'settings' && !in_array($this->Auth->user('type'), ['user', 'admin'])) {
            return $this->redirect(['_name' => 'me:settings']);
        }
    }

    // public function getAdvertising()
    // {
    //     return false;
    // }

    /**
     * Configurazione account FunJob
     */
    public function settings() {
        $this->loadModel('JobCategories');

        $User = $this->Users->get(
            $this->Auth->user('id'), [
                'contain' => [
                    //'JobOffers',
                    'BornCities', 'LiveCities',
                    'AccountInfos',
                    'ProfileBlocks',
                    'UserSkills'
                ]
            ]
        );

        if ($this->request->is('put')) {
            $this->request->data('user_skills');

            // Aggiunge property a UserEntity per poter fare la validazione di password_confirm
            $User->password_confirm = $User->password;
            // Non permette di aggiornare l'email (non uso un campo hidden percui non può farlo il SecurityComponent questo controllo)
            $User->email = $User->email;

            $data = $this->request->getData();

            if (!empty($data['user_skills'])) {
                $_skills             = $data['user_skills'];
                $data['user_skills'] = [];

                // Elimina campi form vuoti (senza label)
                foreach ($_skills as $values) {
                    if (!empty($values['name'])) {
                        $data['user_skills'][] = $values;
                    }
                }
            }

            // Cambio password: imposta password attuale se non definita
            if (!$this->request->getData('password')) {
                $data['password']         = $User->password;
                $data['password_confirm'] = $User->password;
            }

            $validateMathod = null;
            switch ($this->request->getData('_tab')) {
                case 'account':
                    $validateMathod = 'settingsAccountUser';
                break;

                case 'profile':
                    $validateMathod = 'settingsProfileUser';
                break;

                case 'job':
                    $validateMathod = 'settingsJobUser';
                break;
            }


            $this->Users->patchEntity($User, $data, [
                'validate'   => $validateMathod,
                'associated' => [
                    'JobOffers',
                    //'BornCities',
                    //'LiveCities',

                    'AccountInfos',
                    'ProfileBlocks',

                    'UserSkills'
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
                            $this->request->session()->write($key, $value);
                        }
                    }
                }

                $this->Flash->success(__('Opzioni aggiornate'));
                return $this->redirect($this->referer(['action' => 'settings']));
            } else {
                $this->Flash->error(__('Correggi i campi errati e riprova'));
                debug('');
                debug($User->errors());
            }
        }

        $langs = Configure::readOrFail('app.languages');
        $jobRoles      = []; // $this->JobCategories->find('list');
        $UserJobOffers = $jobRoles;


        // Per non mostrare la password nel form
        // $User->password = '';

        $this->set(compact('User', 'jobRoles', 'UserJobOffers', 'langs'));
    }


    /**
     * Archivio Quiz creati dal giocatore
     */
    public function quizzes($status = 'published')
    {
        $this->loadModel('Quizzes');

        // POST 2 GET
        if ($this->request->is('post')) {
            $pass      = $this->request->getParam('pass');
            $pass['?'] = $this->request->getData();
            return $this->redirect($pass);
        }

        $q = $this->Quizzes->find();
        $q->find('withQuestionsCounter');
        $q->find('byAuthor',  ['user_id' => $this->Auth->user('id')]);
        $q->find('byStatus', ['status' => $status]);

        if ($this->request->getQuery('status')) {
            //$q->find('byStatus', ['status' => $this->request->getQuery('status')]);
        }


        if ($this->request->getQuery('type')) {
            $q->find('byType', ['type' => $this->request->getQuery('type')]);
        }

        if ($this->request->getQuery('term')) {
            $q->find('searchByTerm', [
                'term' => $this->request->getQuery('term'),
                'tags' => true
            ]);
        }


        $q->contain([
            'Author' => function($q) {
                $q->select(['id', 'username', 'type']);
                return $q;
            },
            'Categories'
        ]);
        $q->orderDesc('Quizzes.id');

        $q->select(['id', 'type', 'title', 'user_id', 'is_disabled', 'status']);
        $quizzes  = $this->paginate($q, ['limit' => 40]);

        $this->set(compact('quizzes'));
        $this->set('_serialize', ['quizzes']);
    }

    /**
     * Gruppi creati dall'utente
     */
    public function groups($filter = 'joined')
    {
        $this->loadComponent('Paginator');

        // Tab corrente
        $tab = $this->request->getParam('pass.0', 'joined');

        // Filtri
        if ($this->request->is('post')) {
            $data   = $this->request->getData();
            $params = [0 => $tab, '?' => $data];
            return $this->redirect($params);
        }


        if ($filter == 'joined') {
            $q       = $this->Users->MemberOfGroups->find();
            $user_id = $this->Auth->user('id');

            $q->matching('UserGroupMembers', function($q) use ($user_id) {
                $q->where(['UserGroupMembers.user_id' => (int) $user_id]);
                return $q;
            });

            $q->orderDesc('UserGroupMembers.id');
        } else {
            $q = $this->Users->MemberOfGroups->find();
            $q->where(['user_id' => $this->Auth->user('id')]);
        }

        if ($this->request->getQuery('term')) {
            $q->find('search', ['name' => $this->request->getQuery('term')]);
        }

        $entities = $this->Paginator->paginate($q);
        $this->set(compact('entities'));
    }

    /**
     * Ricerca utenti
     */
    public function search()
    {
        $form  = new \App\Form\UserJobOffersForm;
        $isSearch = empty($this->request->getQuery('_do')) ? false : true;

        if ($isSearch) {

            // Imposta variabili GET come POST
            if ($this->request->is('get')) {
                $this->request->data = $this->request->query();
            }

            $isValid   = $form->validate($this->request->getData());
            $useFilter = $this->_searchUseRequiredFilters();
            $canSearch = false; // Esegue query di ricerca

            // Imposta errore di validazione se nessun filtro obbligatorio è stato
            // compilato
            if (!$useFilter) {
                $errmsg = ['_required' => __('Utilizza almeno uno di questi filtri')];
                $form->setErrors([
                    'fullname' => $errmsg,
                    'username' => $errmsg,
                    'age_from' => $errmsg,
                    'age_to'   => $errmsg,
                    'city'     => $errmsg,
                    'skills'   => $errmsg
                ]);
            }

            // Non esegue ricerca se ci sono errori di validazione o non sono stati utilizzati i filtri requireds
            if (!$isValid || !$useFilter) {
                $isSearch  = false;
            } else {
                $canSearch = true;
            }

            if ($canSearch) {
                $query = $this->Users->find('user');
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
                    $query->bind(':fullname', $fullname, 'string');

                    // NOTE:
                    // Non preoccuparsi della ripetizione MATCH ... AGAINST
                    // Il database la calcola una sola volta.
                    // $query->select([
                    //     '_fullnameScore' => 'MATCH(first_name, last_name) AGAINST(:fullname)'
                    // ]);
                    //$query->orderDesc('_fullnameScore');
                    $query->where(['MATCH(Users.first_name, Users.last_name) AGAINST(:fullname IN BOOLEAN MODE)']);
                }

                if ($this->request->getData('sex')) {
                    $query->find('bySex', ['sex' => $this->request->getData('sex')]);
                }

                if ($this->request->getData('hobbies')) {
                    $query->find('byHobbies', ['hobbies' => $this->request->getData('hobbies')]);
                }

                if ($this->request->getData('username'))
                {
                    $query->bind(':username', '*'. $this->request->getData('username') . '*');
                    $query->where(['MATCH(Users.username) AGAINST(:username IN BOOLEAN MODE)']);
                }

                // Filtro: età
                if ($this->request->getData('age_from') || $this->request->getData('age_to')) {
                    $from = (int) $this->request->getData('age_from');
                    $to   = (int) $this->request->getData('age_to');

                    $query->matching('AccountInfos', function($q) use ($from, $to) {
                        return $q->where(function($expr, $q) use ($from, $to) {
                            $_sql = '(YEAR(CURRENT_TIMESTAMP) - YEAR(AccountInfos.birthday))';
                            $_exp = $q->newExpr();

                            if (!empty($from)) {
                                $_exp->gte($_sql, (int) $from);
                            }
                            if (!empty($to)) {
                                $_exp->lte($_sql, (int) $to);
                            }

                            return $_exp;
                        });
                    });
                }

                // Città
                if ($this->request->getData('city')) {
                    $ids = array_keys($this->request->getData('city'));

                    //$query->matching('AccountInfos', function($q) use ($ids) {
                    $query->innerJoinWith('AccountInfos', function($q) use ($ids) {
                        $q->select(['AccountInfos.live_city']);
                        $q->where(['AccountInfos.live_city_id' => $ids], ['AccountInfos.live_city_id' => 'string[]']);
                        return $q;
                    });
                }

                // SKILL tags
                if ($this->request->getData('skills')) {
                    // PS: viene dichiarato anche in _searchFilterSkillContain (poichè utilizza un altro oggetto query)
                    $tags = explode(',', filter_var($this->request->getData('skills'), FILTER_SANITIZE_STRING));
                    $tags = array_map('trim', $tags);
                    $query->bind(':skills', implode(' ', $tags), 'string');

                    // TODO #future:
                    // Vengono usati due query (matching e contain) per i seguenti motivi:
                    // 1. il matching serve per scartare gli utenti che non hanno le skills specificate
                    // 2. il contain viene usato per prelevare tutte le skill tags che matchano la ricerca (purtroppo il matching _matchingData tiene conto solo di una SkillTags)
                    $this->_searchFilterSkillMatching($query, $tags);
                    $this->_searchFilterSkillContain($query, $tags);
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

    /**
     * Verifica che sia stato utilizzato almeno uno dei filtri di ricerca
     *
     * Altrimenti mostrerebbe tutti gli utenti registrati
     *
     * @return bool
     */
    private function _searchUseRequiredFilters()
    {
        // Verifica che sia stato impostato almeno un filtro
        $pass = false;

        foreach (['fullname', 'username', 'age_from', 'ago_to', 'city', 'skills'] as $field) {
            if ($this->request->getData($field)) {
                $pass = true;
                break;
            }
        }

        return $pass;
    }

    /**
     * Filtra utenti in base a UserSkills
     *
     * PS: aggiunge un distinct su User.id perchè viene duplicato ogni User in base a quante UserSKills vengono trovate
     * TODO #future:
     *
     * Per ottimizzare la query sarebbe il caso di togliere il _searchFilterSkillContain e lavorare sul ResultsSet in modo tale da mergiare
     * i _matchingData? (da valutare)
     *
     * @param  \Cake\ORM\Query &$query
     * @return void
     */
    private function _searchFilterSkillMatching(\Cake\ORM\Query &$query, $tags = [])
    {
        $query->matching('UserSkills', function($q) {
            $q->select(['UserSkills.name', 'UserSkills.perc']);

            //$tags = explode(',', filter_var($this->request->data['skills'], FILTER_SANITIZE_STRING));
            //$tags = array_map('trim', $tags);

            // metodo 1: ricerca IN (veloce)
            // $q->where(['UserSkills.name IN' => $tags]);

            // metodo 2: ricerca LIKE (da inserire in for - più lenta del metodo 1 -)
            //foreach($tags as $tag) {
            //    $q->orWhere(['UserSkills.name LIKE' => '%'. $tag. '%']);
            //}

            // metodo 3:
            //$tags = implode(' ', $tags);
            $q->where(['MATCH(UserSkills.name) AGAINST(:skills IN BOOLEAN MODE)']);

            return $q;
        });

        return $query;
    }

    /**
     * Filtra utenti in base a UserSkills
     *
     * Viene fatto il contain su una nuova query (vedi strategy select)
     * Viene dichiarato nuovamente il placeholder :skills perchè l'altro è dichiarato nella query principale.
     *
     * @param  \Cake\ORM\Query &$query
     * @return void
     */
    private function _searchFilterSkillContain(\Cake\ORM\Query &$query, $tags = [])
    {
        // VIENE Utilizzato sia il Contain che il Matching perchè:
        //
        // 1. il contain viene fatto con una nuova query (percui non è possibile filtrare gli utenti)
        // 2. il matching viene fatto per estrarre solo gli utenti che hanno le skills specificate
        //    (scartando gli altri - a differenza del contain -)
        $query->contain([
            'UserSkills' => [
                'strategy'     => 'select',
                'queryBuilder' => function ($q) use ($query, $tags) {
                    // Viene ri-definito il placeholder :skills perchè UserSkills viene eseguito tramite
                    // sub-queries
                    $tags = explode(',', filter_var($this->request->getData('skills'), FILTER_SANITIZE_STRING));
                    $tags = array_map('trim', $tags);
                    $q->bind(':skills_2', implode(' ', $tags), 'string');


                    $q->select(['UserSkills.name', 'UserSkills.perc', 'UserSkills.user_id']);

                    // $tags = explode(',', filter_var($this->request->data['skills'], FILTER_SANITIZE_STRING));
                    // $tags = array_map('trim', $tags);
                    // $tags = implode(' ', $tags);

                    //$q->bind(':skills', $tags, 'string');
                    $q->where(['MATCH(UserSkills.name) AGAINST(:skills_2 IN BOOLEAN MODE)']);

                    return $q;
                }
            ]
        ]);

        return $query;
    }

    /**
     * Crediti dell'utente
     */
    public function credits()
    {
    }

}
