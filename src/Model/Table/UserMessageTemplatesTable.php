<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserMessageTemplates Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserMessageTemplate get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserMessageTemplate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserMessageTemplate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageTemplate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserMessageTemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageTemplate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserMessageTemplate findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserMessageTemplatesTable extends Table
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

        $this->setTable('user_message_templates');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
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
            ->allowEmpty('title');

        $validator
            ->allowEmpty('subject');

        $validator
            ->allowEmpty('body');

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


    protected function findGlobal(Query $q, $settings = [])
    {
        //$q->orWhere(['user_id' => null]);
        return $q;
    }

    protected function findByUser(Query $q, $settings = [])
    {
        if (empty($settings['user_id'])) {
            throw new \Exception();
        }

        $q->where(['user_id' => (int) $settings['user_id']]);
        $q->orWhere(function($exp, $q) {
            return $exp->isNull('user_id');
        });

        return $q;
    }

}
