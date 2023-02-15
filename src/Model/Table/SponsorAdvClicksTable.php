<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SponsorAdvClicks Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $SponsorAdvs
 *
 * @method \App\Model\Entity\SponsorAdvClick get($primaryKey, $options = [])
 * @method \App\Model\Entity\SponsorAdvClick newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SponsorAdvClick[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdvClick|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SponsorAdvClick patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdvClick[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdvClick findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SponsorAdvClicksTable extends Table
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

        $this->setTable('sponsor_adv_clicks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('SponsorAdvs', [
            'foreignKey' => 'sponsor_adv_id'
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
            ->allowEmpty('ip');

        $validator
            ->allowEmpty('href');

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
        $rules->add($rules->existsIn(['sponsor_adv_id'], 'SponsorAdvs'));

        return $rules;
    }

    /**
     * Solo Views per il mese in $settings['month']
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    protected function findByMonth(Query $q, $settings = []) {
        if (!isset($settings['month'])) {
            throw new \Exception(__('findByMonth: parametro mancante "month"'));
        }

        $Dt = \DateTime::createFromFormat('Y-m-d', $settings['month'] . '-01');
        if (!$Dt) {
            throw new \Exception(__('findByMonth: formato non valido "month" (richiede: Y-m)'));
        }

        $q->where(function($exp, $q) use ($Dt) {
            return $exp->between('Clicks.created', $Dt->format('Y-m-d'), $Dt->format('Y-m-t'));
        });

        return $q;
    }
}
