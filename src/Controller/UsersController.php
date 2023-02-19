<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Network\Exception\NotFoundException;
use Cake\Core\Configure;
use Cake\Mailer\MailerAwareTrait;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    use MailerAwareTrait;

    public function initialize(): void
    {
        parent::initialize();

        $this->Auth->allow(['register', 'login', 'view', 'quizCompleted', 'quizCreated', 'friends', 'groups', 'language']);

        if (in_array($this->request->getParam('action'), ['view'])) {

            // Se si tenta di accedere a /me e non si è loggati
            // Sostituito da MeRouteMiddleware (in src/Application.php)
            //
            // if ($this->request->_matchedRoute == '/me' && !$this->Auth->user()) {
            //     return $this->Auth->deny(['view']);
            // }

            // view: form richiesta CV
            // $this->loadComponent('Csrf');
        }
    }

    public function getAdvertising()
    {
        if ($this->request->getParam('action') == 'view') {
            return parent::getAdvertising();
        }

        return false;
    }

    /**
     * Redirect a pagina impostazioni in base al prefix utente
     *
     * PS:
     * Admin: è sempre un user.
     *
     */
    public function settings() {
        $this->autoRender = false;
        $baseRoute = ['_name' => 'me:settings:prefixed', 'prefix' => 'User'];

        if ($this->Auth->user('type') == 'company') {
            $baseRoute['prefix'] = 'company';
        }

        return $this->redirect($baseRoute);
    }

    /**
     * Disabilita profilo
     *
     * NOTA:
     * L'utente che disabilita il profilo non potrà più loggarsi, e perderà tutti i crediti PIX maturati
     */
    public function disable()
    {
        if (!$this->request->is('post')) {
            return null;
        }

        $User = $this->Users->get($this->Auth->user('id'), [
            'contain' => [
                'Credits'
            ]
        ]);

        $data = [
            'is_disabled' => true,
            'can_logon'   => false,
            'credit' => [
                'total' => 0
            ]
        ];

        $User = $this->Users->patchEntity($User, $data, ['associated' => ['Credits']]);
        if ($this->Users->save($User)) {
            $this->Auth->logout();
            $this->Flash->success(__('Account eliminato...'));
            return $this->redirect('/');
        } else {
            $this->Flash->error(__('Impossibile eliminare account'));
            return $this->redirect($this->referer('/'));
        }
    }

    /**
     * Profilo utente (pubblico)
     *
     * @param  int $id ID utente
     */
    public function view($id = null)
    {
        if (empty($id)) {
            throw new \Cake\Network\Exception\BadRequestException();
        }

        // Redirect su profilo aziendale
        // PS: Prende i dati da tables differenti
        if ($entity = $this->Users->findById($id)->first()) {
            if ($entity->type == 'company') {
                return $this->redirect(['_name' => 'companies:profile', 'id' => $entity->id, 'username' => $entity->slug]);
            }
        }

        $User = $this->Users->get($id, [
            'contain' => [
                'BornCities',
                'LiveCities',

                'ProfileBlocks',
                'AccountInfos',

                'UserSkills' => function($q) {
                    $q->order(['perc' => 'desc']);
                    return $q;
                },
                'Friends' => function($q) {
                    $q->where(['is_accepted' => true]);
                    return $q->limit(9);
                },
                'Friends.Users' => function($q) {
                    $q->select(['id', 'username', 'avatar']);
                    return $q;
                }
            ]
        ]);

        if ($User->is_disabled) {
            $this->Flash->error(__('Profilo non esistente'));
            return $this->redirect($this->referer());
        }

        // Amicizie
        if ($this->Auth->user()) {
            $user_id  = $this->Auth->user('id');
            $isFriend = null;

            if ($user_id != $id) {
                $isFriend = $this->Users->Friends
                    ->find('isFriendWith', [
                        'user_id' => $this->Auth->user('id'), 'friend_id' => $id
                    ])
                    ->autoFields(false)
                    ->first();
            }
        }

        $CvAuthorization = $this->Users->CvAuthorizations->newEmptyEntity();

        $this->set(compact('User', 'CvAuthorization', 'isFriend'));
        $this->set('_serialize', ['User']);
    }

    /**
     * Mostra lista dei quiz svolti
     *
     * I quiz devono essere mostrati nel diario
     *
     * @param  int $user_id
     * @param  str $quizType
     * @return \Cake\ORM\ResultSet
     */
    public function quizCompleted($user_id)
    {
        if ($this->request->is('post')) {
            $query = [];

            foreach ($this->request->getData() as $key => $value) {
                if (strpos('_', $key) !== 0) {
                    $query[$key] = $value;
                }
            }

            return $this->redirect($this->request->here . '?'. http_build_query($query));
        }

        $this->loadComponent('Paginator');

        $options['user_id'] = $user_id;

        $q = $this->Users->QuizSessions->find('quizCompleted', $options);
        $q->find('isVisible');

        // Filtri
        if ($this->request->getQuery('name')) {
            $term = trim($this->request->getQuery('name'));
            if (strpos($term, '*') === FALSE) {
                $term = '*' .$term. '*';
            }
            $q->bind(':name', $term);

            $_fulltext = 'MATCH(Quizzes.title) AGAINST(:name IN BOOLEAN MODE)';
            $q->select(['_match' => $_fulltext]);
            $q->where([$_fulltext]);
            $q->orderAsc('_match');
        }

        if ($this->request->getQuery('categories')) {
            $ids = $this->request->getQuery('categories', []);
            $q->matching('Quizzes.Categories', function($q) use ($ids) {
                $q->where(['Categories.id' => $ids], ['Categories.id' => 'integer[]']);
                return $q;
            });
        }

        $q->orderDesc('Quizzes.id');

        $limit      = $this->request->is('mobile') ? 10 : 30;
        $sessions   = $this->Paginator->paginate($q, ['limit' => $limit]);
        $User       = $this->Users->findById($user_id)->firstOrFail();
        $categories = $this->Users->QuizSessions
            ->find('playedCategories', ['user_id' => $options['user_id']])
            ->enableHydration(false)->all();

        $this->set(compact('sessions', 'User', 'categories'));
        $this->set('_serialize', ['sessions']);
        $this->render('profile_tabs/quiz_completed');
    }

    /**
     * Mostra lista dei quiz svolti
     *
     * I quiz devono essere mostrati nel profilo personale utente
     *
     * @param  int $user_id
     * @param  str $quizType
     * @return \Cake\ORM\ResultSet
     */
    public function quizCreated($user_id, $type = 'default')
    {
        if ($this->request->is('post')) {
            $query = [];

            foreach ($this->request->getData() as $key => $value) {
                if (strpos('_', $key) !== 0) {
                    $query[$key] = $value;
                }
            }

            return $this->redirect($this->request->here . '?'. http_build_query($query));
        }

        $this->loadComponent('Paginator');

        $options['user_id'] = $user_id;

        $q = $this->Users->Quizzes->find();
        $q->select(['id', 'title', 'image__src', 'image__dir', 'type', 'color']);
        $q->find('published');
        $q->find('byAuthor', $options);
        $q->find('withAuthor');
        $q->orderDesc('Quizzes.id');

        // Filtri
        if ($this->request->getQuery('name')) {
            $term = trim($this->request->getQuery('name'));
            if (strpos($term, '*') === FALSE) {
                $term = '*' .$term. '*';
            }
            $q->bind(':name', $term);

            $_fulltext = 'MATCH(Quizzes.title) AGAINST(:name IN BOOLEAN MODE)';
            $q->select(['_match' => $_fulltext]);
            $q->where([$_fulltext]);
            $q->orderAsc('_match');
        }

        if ($this->request->getQuery('categories')) {
            $ids = $this->request->getQuery('categories', []);
            $q->matching('Categories', function($q) use ($ids) {
                $q->where(['Categories.id' => $ids], ['Categories.id' => 'integer[]']);
                return $q;
            });
        }

        $this->loadComponent('Paginator');
        $this->loadModel('Quizzes');

        $limit      = $this->request->is('mobile') ?  10 : 30;
        $quizzes    = $this->Paginator->paginate($q, compact('limit'));
        $categories = $this->Quizzes->find('createdByCategories', compact('user_id'))->enableHydration(false)->all();

        $this->set(compact('quizzes', 'categories'));
        $this->set('_serialize', ['quizzes']);
        $this->render('profile_tabs/quiz_created');
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();

            if ($user) {
                $this->Auth->setUser($user);

                $UserEntity = $this->Users->get($this->Auth->user('id'));
                $UserEntity = $this->Users->patchEntity($UserEntity, ['last_seen' => new \DateTime()]);

                $this->Users->save($UserEntity);

                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Credenziali di accesso errate'), [
                    //'key' => 'auth'
                ]);
            }
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    private function _socialProfileDecode()
    {
        $userProfile = base64_decode($this->request->getQuery('response'));
        $userProfile = (array) json_decode($userProfile);

        foreach ($userProfile as $key => $value) {
            if ($key == 'displayName') {
                $this->request->query['username'] = \Cake\Utility\Text::slug($value, '');
            }

            $this->request->query[$key] = $value;
        }

        $this->request->query['fullname'] = $userProfile['firstName'] .' '. $userProfile['lastName'];
    }

    public function register()
    {
        //$this->ViewBuilder()->setLayout('default');
        if ($this->request->getQuery('response')) {
            $this->_socialProfileDecode();
        }

        $User = $this->Users->newEmptyEntity();

        if ($this->request->is('post'))
        {
            $User = $this->Users->patchEntity($User, $this->request->getData(), [
                'validate' => 'registration'
            ]);

            $User->confirmation_token = \Cake\Utility\Text::uuid();
            $User->can_login          = true;
            $User->is_verified_mail   = false;

            if ($this->Users->save($User)) {
                // Per sollevare l'evento "Auth.afterIdentify" di AuthComponent
                // perchè tramite UserListener.afterIdentify crea UserFields
                $this->Auth->identify();
                $this->Auth->setUser($User->toArray());

                $this->getMailer('User')->send('emailConfirmation', [$User]);
                $this->Flash->success(
                    __('Benvenuto su FunJob {username}', ['username' => '<strong>@' .$User->username. '</strong>']),
                    ['escape' => false]
                );
                return $this->redirect(['_name' => 'me:dashboard']);
            }
        }

        $this->set(compact('User'));
    }

    /**
     * Ricerca utenti
     *
     * Usata su /user-messages/add
     */
    public function search() {

        $q = $this->Users->find();
        $q->select(['id', 'username', 'first_name', 'last_name']);
        $q->where(['username LIKE' => '%' . $this->request->query('term') . '%']);

        $results = $q->limit(25)->all();

        $this->set(compact('results'));
        $this->set('_serialize', ['results']);
    }

    /**
     * Amici dell'utente
     *
     * Tab "Amici" su profilo pubblico utente
     */
    public function friends($user_id)
    {
        $this->loadComponent('Paginator');

        $q = $this->Users->Friends->find();
        $q->where(['user_id' => (int) $user_id]);
        $q->where(['is_accepted' => true]);

        $q->contain([
            'Users' => function($q) {
                $q->select(['id', 'username', 'title', 'first_name', 'last_name', 'avatar']);
                return $q;
            }
        ]);

        $friends = $this->Paginator->paginate($q, ['limit' => 30]);
        $this->set(compact('friends'));
        $this->render('profile_tabs/friends');
    }

    /**
     * Gruppi di cui fà parte l'utente
     *
     * Tab "Gruppi" su profilo pubblico utente
     */
    public function groups($user_id)
    {
        $this->loadComponent('Paginator');

        $this->loadModel('UserGroups');
        $q = $this->UserGroups->find();

        $q->matching('UserGroupMembers', function($q) use ($user_id) {
            $q->where(['UserGroupMembers.user_id' => (int) $user_id]);
            return $q;
        });

        $q->orderDesc('UserGroupMembers.id');

        $groups = $this->Paginator->paginate($q, ['limit' => 30]);
        $this->set(compact('groups'));
        $this->render('profile_tabs/groups');
    }

    /**
     * Aggiorna lingua sessione e lingua utente
     */
    public function language() {
        $this->request->allowMethod('POST');

        $lang = $this->request->getData('language');

        if (!empty($lang)) {
            $this->request->getSession()->write('Config.language', $lang);

            if ($this->request->getSession()->check('Auth.User')) {
                $this->request->getSession()->write('Auth.User.lang', $lang);

                $this->loadModel('Users');
                $User    = $this->Users->get((int) $this->Auth->user('id'));
                $User    = $this->Users->patchEntity($User, compact('lang'));
                $updated = $this->Users->save($User);
            }
        }


        return $this->redirect($this->referer());
    }
}
