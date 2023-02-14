<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SponsorAdvViews Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Advs
 *
 * @method \App\Model\Entity\SponsorAdvView get($primaryKey, $options = [])
 * @method \App\Model\Entity\SponsorAdvView newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SponsorAdvView[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdvView|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SponsorAdvView patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdvView[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdvView findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SponsorAdvViewsTable extends Table
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

        $this->setTable('sponsor_adv_views');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Advs', [
            'className'  => 'SponsorAdvs',
            'foreignKey' => 'adv_id'
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
            ->integer('views')
            ->allowEmpty('views');

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
        $rules->add($rules->existsIn(['adv_id'], 'Advs'));

        return $rules;
    }


    /**
     * Restituisce views per giorno
     *
     * @param  Query  $q        [description]
     * @param  array  $settings [description]
     * @return [type]           [description]
     */
    protected function findByDate(Query $q, $settings = [])
    {
        if (!isset($settings['date'])) {
            throw new \Exception();
        }

        if (!isset($settings['adv_id'])) {
            throw new \Exception();
        }

        $q->where([
            'day'    => $settings['date'],
            'adv_id' => (int) $settings['adv_id']
        ]);

        return $q;
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
            return $exp->between('Views.day', $Dt->format('Y-m-d'), $Dt->format('Y-m-t'));
        });

        return $q;
    }

    /**
     * Solo Views per il periodo in $settings['date_from'] e $settings['date_to']
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    protected function findByPeriod(Query $q, $settings = []) {
        if (!isset($settings['date_from'])) {
            throw new \Exception(__('findByPeriod: parametro mancante "date_from"'));
        }

        if (!isset($settings['date_to'])) {
            throw new \Exception(__('findByPeriod: parametro mancante "date_to"'));
        }

        $q->where(function($exp, $q) use ($filterMonth) {
            $firstDay = \DateTime::createFromFormat('Y-m-d', $filterMonth . '-01');
            return $exp->between('Views.day', $firstDay->format('Y-m-d'), $firstDay->format('Y-m-t'));
        });

        return $q;
    }
}
