<?php
namespace App\Controller\User;

use App\Controller\AppController;

use Cake\Database\Expression\IdentifierExpression;
use Cake\Cache\Cache;

use Cake\Mailer\MailerAwareTrait;

/**
 * UserFriends Controller
 *
 * @property \App\Model\Table\UserFriendsTable $UserFriends
 */
class UserFriendsController extends AppController
{
    use MailerAwareTrait;


    public function initialize(): void
    {
        parent::initialize();

        if (in_array($this->request->getParam('action'), ['index', 'waiting', 'starred'])) {
            $this->loadComponent('Paginator');
        }

        $this->Auth->deny(['index', 'waiting', 'starred', 'add', 'edit', 'delete']);
    }

    // public function getAdvertising() {
    //     return false;
    // }

    /**
     * Lista amici (backend)
     *
     * Lista amici per poter eliminare un amicizia
     * Account > Amici > Lista
     */
    public function index()
    {
        $q = $this->UserFriends->find();
        $q->where(['user_id' => $this->Auth->user('id')]);
        $q->find('accepted');
        $q->contain(['Users']);

        // POST to GET
        if ($this->request->is('post')) {
            return $this->redirect(['?' => $this->request->getData()]);
        }

        if ($this->request->getQuery('term')) {
            $q->find('searchByTerm', ['term' => $this->request->getQuery('term')]);
        }

        $friends = $this->Paginator->paginate($q, ['limit' => 30]);

        $this->set(compact('friends'));
        $this->set('_serialize', ['friends']);
    }

    /**
     * Amici preferiti
     *
     * @return [type] [description]
     */
    public function starred()
    {
        $q = $this->UserFriends->find();
        $q->find('accepted');

        $q->where(['user_id' => $this->Auth->user('id')]);
        $q->find('starred');
        $q->contain(['Users']);

        $friends = $this->Paginator->paginate($q, ['limit' => 30]);

        $this->set(compact('friends'));
        $this->set('_serialize', ['friends']);
        $this->render('index');
    }

    /**
     * Richieste d'amicizia da moderare
     *
     * Effettuate e ricevute
     */
    public function _waiting() {
        $q = $this->UserFriends->find();

        $q->andWhere(['user_id'  => $this->Auth->user('id')]);
        $q->orWhere(['friend_id' => $this->Auth->user('id')]);
        $q->find('waiting');

        // Ordina richieste
        $direction = $q->newExpr()->addCase(
            [
                $q->newExpr()->eq('UserFriends.user_id', $this->Auth->user('id')),   // inviata
                $q->newExpr()->eq('UserFriends.friend_id', $this->Auth->user('id')), // ricevuta
            ],
            [1, 2], // 1=inviata 2=ricevuta
            ['integer', 'integer']
        );

        $q->select($this->UserFriends);
        $q->group(['request_id']);
        $q->order(['UserFriends.id' => 'ASC', '_direction' => 'ASC']);


        // Relazioni
        $this->UserFriends->belongsTo('UserSents', ['className'  => 'Users', 'foreignKey' => 'user_id']);
        $this->UserFriends->belongsTo('UserRecipients', ['className'  => 'Users', 'foreignKey' => 'friend_id']);

        $_fields = function($q) {
            return $q->select(['id', 'first_name', 'last_name', 'username', 'avatar', 'title']);
        };

        $q->contain([
            'UserRecipients' => $_fields,
            'UserSents'      => $_fields
        ]);

        $requests = $this->Paginator->paginate($q, ['limit' => 30]);

        $this->set(compact('requests'));
        $this->set('_serialize', ['requests']);
    }

    /**
     * Richieste d'amicizia da moderare
     *
     * Effettuate e ricevute
     */
    public function waiting() {
        $q = $this->UserFriends->find();
        $q->find('waiting');
        $q->where([
            'friend_id'    => $this->Auth->user('id'),
            'is_requester' => true
        ]);

        $q->select($this->UserFriends);
        //$q->group(['request_id']);
        $q->order(['UserFriends.id' => 'ASC']);

        // Relazioni
        $this->UserFriends->belongsTo('UserSents', ['className'  => 'Users', 'foreignKey' => 'user_id']);
        $this->UserFriends->belongsTo('UserRecipients', ['className'  => 'Users', 'foreignKey' => 'friend_id']);

        $_fields = function($q) {
            return $q->select(['id', 'first_name', 'last_name', 'username', 'avatar', 'title']);
        };

        $q->contain([
            'UserRecipients' => $_fields,
            'UserSents'      => $_fields
        ]);

        $requests = $this->Paginator->paginate($q, ['limit' => 30]);

        $this->set(compact('requests'));
        $this->set('_serialize', ['requests']);
    }

    /**
     * Richiede amicizia
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->autoRender = false;
        $this->request->allowMethod('POST');


        if ($this->request->is('post')) {
            $userFriend1 = $this->UserFriends->newEntity($this->request->getData());
            $userFriend2 = $this->UserFriends->newEntity($this->request->getData());

            $saved = $this->UserFriends->connection()->transactional(function () use ($userFriend1, $userFriend2) {
                // Duplico richiesta d'amicizia: Swap fk users
                $userFriend2->user_id      = $userFriend1->friend_id;
                $userFriend2->friend_id    = $userFriend1->user_id;
                $userFriend2->is_requester = false;

                $original = $this->UserFriends->save($userFriend1);
                $userFriend1->request_id   = $userFriend1->id;
                $userFriend1->is_requester = true;

                $userFriend2->request_id = $userFriend1->id;

                $original = $this->UserFriends->save($userFriend1);
                $cloned   = $this->UserFriends->save($userFriend2);

                return $original && $cloned;
            });

            if ($saved) {
                // Elimina cache contatore amici in attesa
                Cache::deleteMany([
                    sprintf('user_%d', $userFriend1->user_id),
                    sprintf('user_%d', $userFriend2->user_id)
                ], 'user_friends_waiting');

                $this->Flash->success(
                    __('Richiesta di amicizia in attesa di approvazione da parte dell\'utente')
                );

                try {
                    $UserRequester = $this->UserFriends->Users->get($this->request->getData('user_id'));
                    $UserRecipient = $this->UserFriends->Users->get($this->request->getData('friend_id'));
                    $this->getMailer('UserFriend')->send('newFriendRequest', [$UserRecipient,$UserRequester]);
                } catch(Exception $e) {
                    dd($e->getMessage());
                }

                //return $this->render('/Homepages/index');
                return $this->redirect($this->referer('/'));
            }

            $alreadySent = false;
            foreach ([$userFriend1, $userFriend2] as $_entity) {
                $errors = $_entity->errors('friend_id');
                if (in_array('alreadySent', array_keys($errors))) {
                    $alreadySent = true;
                }
            }

            if ($alreadySent) {
                $this->Flash->error(__('Attendi che l\'utente dia un responso per la tua richiesta di amicizia'));
            } else {
                $this->Flash->error(__('Impossibile richiedere amicizia'));
            }

            return $this->redirect($this->referer('/'));
        }
    }

    /**
     * Accetta o rifiuta richiesta d'amicizia
     *
     * @param string|null $id User Friend id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->autoRender = false;
        $this->request->allowMethod('PUT');

        $userFriend = $this->UserFriends->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            // Ricavo UserFriends per entrambi gli utenti
            $entities = $this->UserFriends->find()
                    ->where(['request_id' => $userFriend->request_id])
                    ->select(['id', 'user_id', 'friend_id']) // friend_id per CounterCacheBehavior
                    ->all();

            if ($this->request->getData('is_accepted')) {
                $success = $this->UserFriends->connection()->transactional(function() use ($entities) {
                    foreach ($entities as $request) {
                        $request->is_accepted = true;
                        if (!$this->UserFriends->save($request)) {
                            return false;
                        }
                    }

                    return true;
                });

                if ($success) {
                    $this->_deleteCache($entities);
                    $this->Flash->success(__('Richiesta accettata'));
                } else {
                    $this->Flash->error(__('Impossibile aggiornare richiesta'));
                }

                return $this->redirect($this->referer('/'));
            } else {
                // NOTE 1: Riga rimossa per validazione isUnique[user_id,friend_id]
                // NOTE 2: Attraverso il CounterCache Behavior viene aggiornato il conteggio (solo in fase di delete)
                $success = $this->UserFriends->connection()->transactional(function() use ($entities) {
                    $deleted = true;

                    foreach ($entities as $request) {
                        if (!$this->UserFriends->delete($request)) {
                            $deleted = false;
                            break;
                        }
                    }

                    return $deleted;
                });

                if ($success) {
                    $this->_deleteCache($entities);
                    $this->Flash->success(__x('Richiesta di amicizia rifiutata', 'Richiesta non accettata'));
                }

                return $this->redirect($this->referer('/'));
            }
        }
    }

    /**
     * Rifiuta la richiesta di amicizia
     *
     * Viene richiamata solo nella prima fase (accettazione/rifiuto amicizia). Per eliminare un amicizia viene invocato
     * il metodo edit
     *
     * @param string|null $id User Friend id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'delete']);

        $entity   = $this->UserFriends->get($id);
        $entities = $this->UserFriends->find()
            ->where(['request_id' => $entity->request_id])
            ->all();

        $deleted = $this->UserFriends->connection()->transactional(function () use ($entities) {
            $deleted = true;

            foreach ($entities as $entity) {
                if (!$this->UserFriends->delete($entity)) {
                    $deleted = false;
                    break;
                }
            }

            return $deleted;
        });

        if ($deleted) {
            $this->_deleteCache($entities);
            $this->Flash->success(__('Richiesta di amicizia non accettata.'));
        }

        return $this->redirect($this->referer('/'));
    }

    /**
     * Flag "is_preferite" ad amicizia
     *
     * Metodo "toggle"
     */
    public function star()
    {
        $this->request->allowMethod(['put', 'post']);
        $this->autoRender = false;

        $UserFriend = $this->UserFriends->get($this->request->getData('id'));
        if ($UserFriend->user_id != $this->Auth->user('id')) {
            throw new \Cake\Network\Exception\ForbiddenException();
        }

        $UserFriend = $this->UserFriends->patchEntity($UserFriend, $this->request->getData(), [
            'fieldList' => ['is_preferite']
        ]);

        if (!$this->UserFriends->save($UserFriend)) {
            throw new \Cake\Network\Exception\BadRequestException();
        }

        if (!$this->request->is('ajax')) {
            return $this->redirect($this->referer(['action' => 'index']));
        }

        $this->set('_serialize', []);
    }

    /**
     * Cancella cache friends_waiting_count degli utenti in $entities
     *
     * @param  array $entities
     * @return bool
     */
    protected function _deleteCache($entities) {
        $files = [];
        foreach ($entities as $entity) {
            $files[] = sprintf('user_%d', $entity->user_id);
        }

        return Cache::deleteMany($files, 'user_friends_waiting');
    }
}
