<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserMessageReplies Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Senders
 *
 * @method \App\Model\Entity\UserMessageReply get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserMessageReply newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserMessageReply[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageReply|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserMessageReply patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageReply[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageReply findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserMessageRepliesTable extends Table
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

        $this->table('user_message_replies');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('StripTags', [
            'body' => [
                'allowable_tags' => []
            ]
        ]);


        $this->belongsTo('ReplySenders', [
            'className'  => 'Users',
            'foreignKey' => 'sender_id',
            'fields'     => ['id', 'username', 'email']
        ]);

        $this->belongsTo('Conversations', [
            'className'  => 'UserMessageConversations',
            'foreignKey' => 'conversation_id',
        ]);

        $this->hasMany('Recipients', [
            'className'  => 'UserMessageRecipients',
            'foreignKey' => 'reply_id',
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
            ->integer('user_message_thread')
            ->allowEmpty('user_message_thread');

        $validator
            ->notEmpty(['body']);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): \Cake\ORM\RulesChecker
    {
        $rules->add($rules->existsIn(['sender_id'], 'ReplySenders'));

        return $rules;
    }
}
