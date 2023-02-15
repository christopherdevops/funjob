<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserMessageActors Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserMessageActor get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserMessageActor newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserMessageActor[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageActor|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserMessageActor patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageActor[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageActor findOrCreate($search, callable $callback = null, $options = [])
 */
class UserMessageRecipientsTable extends Table
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

        $this->table('user_message_recipients');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Users', [
            'className'  => 'Users',
            'foreignKey' => 'user_id'
        ]);

        $this->belongsTo('Replies', [
            'className'  => 'UserMessageReplies',
            'foreignKey' => 'reply_id'
        ]);

        $this->belongsTo('Conversations', [
            'className'  => 'UserMessageConversations',
            'foreignKey' => 'conversation_id'
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
        $validator->notEmpty(['username']);

        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('user_message_thread')
            ->allowEmpty('user_message_thread');

        $validator
            ->boolean('is_readed')
            ->allowEmpty('is_readed');

        $validator
            ->boolean('is_sender')
            ->allowEmpty('is_sender');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function findUnread(\Cake\ORM\Query $query, $settings = [])
    {
        if (empty($settings['user_id'])) {
            throw new \RuntimeException(__('{0} missing argument: user_id', __CLASS__));
        }

        $query->where([
            'user_id'     => $settings['user_id'],
            'is_unreaded' => true
        ]);

        return $query;
    }
}
