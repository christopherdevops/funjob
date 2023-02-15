<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserMessageConversations Model
 *
 * @method \App\Model\Entity\UserMessageThread get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserMessageThread newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserMessageThread[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageThread|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserMessageThread patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageThread[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageThread findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserMessageConversationsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('user_message_conversations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');


        $this->hasMany('Replies', [
            'className'  => 'UserMessageReplies',
            'foreignKey' => 'conversation_id',
        ]);

        $this->belongsTo('Senders', [
            'className'  => 'Users',
            'foreignKey' => 'sender_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): \Cake\Validation\Validator
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('subject');

        return $validator;
    }

    /**
     * Restituisce i messaggi destinati all'utente attuale
     *
     * @param  [type] $q        [description]
     * @param  array  $settings [description]
     * @return [type]           [description]
     */
    public function findInbox($q, $settings = [])
    {
        if (empty($settings['user_id'])) {
            throw new \Cake\Exceptions\RunTimeException(__('{0} require parameter "user_id"', __FUNCTION__));
        }

        // Creo relazione hasOne così da restituire un solo reply (l'ultimo)
        $this->hasOne('RepliesLast', [
            'className'  => 'UserMessageReplies',
            'foreignKey' => 'conversation_id',
            'sort'       => ['RepliesLast.id' => 'DESC'],
            'strategy'   => 'join',
        ]);

        // Filtra solo messaggi con utente corrente nei destinatari
        $q->matching('RepliesLast.Recipients', function($q) use ($settings) {
            $q->select([
                // Totale dei messaggi inviati nella conversazione
                'messages_count'  => $q->func()->count('*'),

                // Utilizzo il SUM su is_unreaded così da avere un conteggio
                // delle risposte non lette
                //
                // Valore che serve per mostrare i messagi non letti in cima alla lista
                'messages_unread' => $q->func()->sum('is_unreaded'),
            ]);

            $q->where(['Recipients.user_id' => $settings['user_id']]);

            return $q;
        });


        $q->contain([
            'RepliesLast.ReplySenders'
        ]);

        //$q->autoFields(false);
        $q->select([
            'UserMessageConversations.id',
            'UserMessageConversations.uuid',
            'UserMessageConversations.subject',
            'UserMessageConversations.context'
        ]);

        $q->order([
            'messages_unread'             => 'DESC', // Prima i messaggi non letti
            'UserMessageConversations.id' => 'DESC'
        ]);

        $q->group(['UserMessageConversations.id']);

        return $q;
    }

    /**
     * Restituisce tutti i messaggi non letti da $settings['user_id']
     *
     * @param  \Cake\ORM\Query $q
     * @param  array  $settings (user_id its required parameter)
     * @return \Cake\ORM\Query
     */
    public function findRecipients(Query $q, $settings = [])
    {
        if (empty($settings['conversation_id'])) {
            throw new \App\Error\FindRequiredParameter(__('{0}::{1} require parameter "conversation_id"', __CLASS__, __FUNCTION__));
        }

        $replies = $this->Replies->find()
            ->select(['id'])
            ->where([
                'Replies.conversation_id' => (int) $settings['conversation_id']
            ])
            ->contain([
                'Recipients' => function($q) {
                    $q->select(['Recipients.user_id', 'Recipients.reply_id', 'Users.id', 'Users.first_name', 'Users.last_name']);
                    $q->group(['Recipients.user_id']);
                    $q->contain(['Users']);

                    return $q;
                }
            ])
            ->all();

        return $replies->extract('recipients.{*}.user');
    }

    /**
     * Restituisce tutti i messaggi non letti da $settings['user_id']
     *
     * @param  \Cake\ORM\Query $q
     * @param  array  $settings (user_id its required parameter)
     * @return \Cake\ORM\Query
     */
    public function findUnread(Query $q, $settings = [])
    {
        if (empty($settings['user_id'])) {
            throw new \Cake\Exceptions\RuntimeException(__('{0} require parameter "user_id"', __FUNCTION__));
        }

        return $q;
    }
}
