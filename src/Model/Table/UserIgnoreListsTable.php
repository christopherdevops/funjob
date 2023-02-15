<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserIgnoreLists Model
 *
 * @property \Cake\ORM\Association\BelongsTo $RecipientUsers
 * @property \Cake\ORM\Association\BelongsTo $IgnoredUsers
 *
 * @method \App\Model\Entity\UserIgnoreList get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserIgnoreList newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserIgnoreList[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserIgnoreList|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserIgnoreList patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserIgnoreList[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserIgnoreList findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserIgnoreListsTable extends Table
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

        $this->setTable('user_ignore_lists');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('RecipientUsers', [
            'className'  => 'Users',
            'foreignKey' => 'recipient_user_id'
        ]);
        $this->belongsTo('IgnoredUsers', [
            'className'  => 'Users',
            'foreignKey' => 'ignored_user_id'
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
        $rules->add($rules->existsIn(['recipient_user_id'], 'RecipientUsers'));
        $rules->add($rules->existsIn(['ignore_user_id'], 'IgnoredUsers'));
        $rules->addCreate(
            $rules->isUnique(['ignore_user_id', 'recipient_user_id'], __('Hai già ignorato questo utente'))
        );

        return $rules;
    }



    protected function findIgnoreUser(Query $q, $settings = [])
    {
        if (empty($settings['user_id']) || empty($settings['ignore_user_id'])) {
            throw new \Exception(__('Parametri mancanti: user_id o ignore_user_id'));
        }

        $q->andWhere(['recipient_user_id' => (int) $settings['user_id']]);
        $q->andWhere(['ignore_user_id'    => (int) $settings['ignore_user_id']]);

        return $q;
    }
}
