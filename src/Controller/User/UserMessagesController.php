<?php
namespace App\Controller\User;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Cache\Cache;

use Cake\Network\Exception\ForbiddenException;

/**
 * UserMessages Controller
 *
 * @property \App\Model\Table\UserMessageThreadsTable $UserMessageThreads
 */
class UserMessagesController extends AppController
{

    public function initialize(): void
    {
        $this->UserMessages = $this->loadModel('UserMessageConversations');
        $this->loadComponent('Security');

        parent::initialize();
        $this->Auth->deny();

        if (in_array($this->request->action, ['add', 'view'])) {
            Configure::load('emoticon');
            $icons = Configure::read('emoticon.map');
            $icons = array_flip($icons);

            $this->set('emoticons', $icons);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $q = $this->UserMessages->find('inbox', ['user_id' => $this->Auth->user('id')]);
        $q->contain([
            'Senders' => ['fields' => ['id', 'email', 'username']],
        ]);

        $userMessages = $this->paginate($q, ['limit' => 10]);

        $this->set(compact('userMessages', 'replies'));
        $this->set('_serialize', ['userMessages']);
    }

    /**
     * View method
     *
     * @param string|null $id User Message id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $q = $this->UserMessages->find();
        $q->where(['uuid' => $id]);

        $q->contain([
            'Replies' => function($q) {
                $q->order(['Replies.id' => 'ASC']);
                return $q;
            },
            'Replies.ReplySenders',
            'Replies.Recipients' =>  function($q) {
                $q->where(['user_id' => $this->Auth->user('id')]);
                return $q;
            },
            'Replies.Recipients.Users'
        ]);
        $userMessage = $q->first();

        // Flag "is_unreaded=false" su nuovi messaggi
        $unreadeIds = [];
        foreach ($userMessage->replies as $reply) {
            foreach ($reply->recipients as $recipient) {
                if ($recipient->is_unreaded) {
                    $unreadeIds[] = $recipient->get('id');
                }
            }
        }

        if (!empty($unreadeIds)) {
            $this->UserMessages->Replies->Recipients->updateAll(['is_unreaded' => false], ['id IN' => array_values($unreadeIds)]);
        }

        // TODO:
        // Verificare se utente attivo è nei recipients

        $userMessageReply = $this->UserMessages->Replies->newEntity();


        $this->set(compact('userMessage', 'userMessageReply'));
        $this->set('_serialize', ['userMessage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($username = null)
    {
        $this->loadModel('Users');
        $userMessage = $this->UserMessages->newEntity();

        if ($this->request->is('post')) {

            // Converte username in ID
            $this->Users = \Cake\ORM\TableRegistry::get('Users');
            $user = $this->Users->find()
                ->select('id')
                ->where(['username' => $this->request->data('replies.0.recipients.1.username')])
                ->first();

            $this->request->data('replies.0.recipients.1.user_id', $user->id);

            try {
                $this->_userCanSendMessage($this->Auth->user('id'), $user->id);
            } catch(ForbiddenException $e) {
                $this->Flash->error($e->getMessage());
                return $this->redirect($this->referer('/'));
            }

            // Aggiunge dati per save
            $this->request->data('sender_id', $this->Auth->user('id'));
            $this->request->data('uuid', \Cake\Utility\Text::uuid());
            $this->request->data('replies.0.sender_id', $this->Auth->user('id'));

            $this->request->data('replies.0.recipients.0.user_id', $this->Auth->user('id'));
            $this->request->data('replies.0.recipients.0.is_sender', true);
            $this->request->data('replies.0.recipients.0.is_unreaded', false);

            // TODO (future)
            // Implementare logica per inviare messaggi a più utenti qui

            $userMessage = $this->UserMessages->patchEntity($userMessage, $this->request->data, [
                'associated' => [
                    'Replies',
                    'Replies.Recipients'
                ]
            ]);

            if ($this->UserMessages->save($userMessage)) {
                $this->Flash->success(__('Messaggio inviato'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user message could not be saved. Please, try again.'));
        } elseif ($this->request->is('get')) {
            // Auto complete
            if (!empty($this->request->username)) {
                $this->request->data('replies.0.recipients.1.username', $this->request->username);
            }
        }


        // $templates = $this->Users->MessageTemplates
        //     ->find('global')
        //     ->find('byUser', [
        //         'user_id' => $this->Auth->user('id')
        //     ])
        //     ->find('list', [
        //         'keyField'   => 'name',
        //         'valueField' => 'body',
        //         'groupField' => 'user_id'
        //     ]);

        $this->set(compact('userMessage', 'templates'));
        $this->set('_serialize', ['userMessage']);
    }

    public function reply()
    {
        $this->request->allowMethod('POST');
        $this->loadModel('Users');

        $userConversation = $this->UserMessages->get($this->request->getData('conversation_id'));
        $recipients = $this->_getConversationRecipients();

        if ($this->request->is('post')) {
            $userMessageReply = $this->UserMessages->Replies->newEntity();

            // Aggiunge dati
            $this->request->data('sender_id', $this->Auth->user('id'));

            $cacheFiles = [];

            foreach ($recipients as $i => $User) {
                // Verifica che l'utente non sia ignorato
                try {
                    $x = $this->_userCanSendMessage($this->Auth->user('id'), $User->id);
                } catch (ForbiddenException $e) {
                    $this->Flash->error($e->getMessage());
                    return $this->redirect($this->referer(['action' => 'index']));
                }

                $isSender   = $User->id == $this->Auth->user('id');
                $dataPrefix = sprintf('recipients.%d', $i);
                $this->request->data($dataPrefix . '.user_id', $User->id);
                $this->request->data($dataPrefix . '.is_sender', $isSender);
                $this->request->data($dataPrefix . '.is_unreaded', !$isSender);

                $cacheFiles[] = 'user_inbox_user_'. $User->id;
            }

            $userMessageReply = $this->UserMessages->Replies->patchEntity($userMessageReply, $this->request->data, [
                'associated' => [
                    'Recipients'
                ]
            ]);

            if ($this->UserMessages->Replies->save($userMessageReply)) {
                // Cancella user_inbox_N file
                if (!empty($cacheFiles)) {
                    Cache::deleteMany($cacheFiles);
                }

                $this->Flash->success(__('Messaggio inviato'));
                return $this->redirect(['action' => 'view', $userConversation->uuid]);
            }

            $this->Flash->error(__('Impossibile inviare messaggio'));
            return $this->redirect(['action' => 'view', $userConversation->uuid]);
        }
    }

    /**
     * Toglie flag "unreaded" ad un determinato messaggio
     *
     * Viene utilizzato tramite chiamata AJAX
     *
     * @return \Cake\Network\Response|null
     */
    public function markAsRead()
    {
        $this->request->allowMethod('POST');

        $conditions = [
            'user_id'     => $this->Auth->user('id'),
            'is_unreaded' => true
        ];

        $this->UserMessages->Replies->Recipients->updateAll(['is_unreaded' => false], $conditions);

        $this->redirect(['action' => 'index']);
    }


    /**
     * Verifica: l'utente può inviare il messaggio in questa discussione?
     *
     * @throws \Cake\Network\Exception\ForbiddenException $uid non nei recipients
     * @return \Cake\Colleciton\Collection
     */
    private function _getConversationRecipients()
    {
        // Verifica: l'utente può inviare il messaggio in questa discussione?
        $uid = (int) $this->Auth->user('id');

        $recipients = $this->UserMessages->find('recipients', [
            'conversation_id' => (int) $this->request->getData('conversation_id')
        ]);

        // Ordinamento: mostra in cima l'entity uguale a $uid
        $recipients = $recipients->sortBy(function($user) use ($uid) {
            return $user->id == $uid;
        });

        $userInRecipients = $recipients->filter(function($user) use ($uid) {
            return $user->id == $uid;
        });

        if ($userInRecipients->isEmpty()) {
            throw new \Cake\Network\Exception\ForbiddenException();
        }

        return $recipients;
    }


    /**
     * Verifica che l'utente che invia il messaggio non sia ignorato
     *
     * @param  [type] $sender    [description]
     * @param  [type] $recipient [description]
     * @return [type]            [description]
     */
    protected function _userCanSendMessage($sender, $recipient)
    {
        $ignoreMe = $this->Users->UserIgnoreLists
            ->find('ignoreUser', [
                'user_id'        => (int) $recipient,
                'ignore_user_id' => (int) $sender
            ])
            ->hydrate(false)
            ->count();

        if ($ignoreMe) {
            throw new ForbiddenException(__('Questo utente ti stà ignorando'));
        }
    }
}
