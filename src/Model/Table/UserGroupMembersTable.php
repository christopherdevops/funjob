<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserGroupMembers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserGroupMember get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserGroupMember newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserGroupMember[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserGroupMember|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserGroupMember patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserGroupMember[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserGroupMember findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserGroupMembersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('user_group_members');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('CounterCache', [
            'Groups' => [
                'members_count' => [
                    //'ignoreDirty' => true
                ]
            ]
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Groups', [
            'className'  => 'UserGroups',
            'foreignKey' => 'group_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('group')
            ->allowEmpty('group');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['group_id'], 'Groups'));

        return $rules;
    }

    protected function findByUserAndGroup(\Cake\ORM\Query $q, $settings = [])
    {
        if (empty($settings['user_id'])) {
            throw new RuntimeException(__('Parametro: user_id richiesto'));
        }

        if (empty($settings['group_id'])) {
            throw new RuntimeException(__('Parametro: group_id richiesto'));
        }

        $q->where([
            'user_id'  => $settings['user_id'],
            'group_id' => $settings['group_id']
        ]);

        return $q;
    }
}
