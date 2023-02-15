<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CompanyCategories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentCompanyCategories
 * @property \Cake\ORM\Association\HasMany $ChildCompanyCategories
 *
 * @method \App\Model\Entity\CompanyCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\CompanyCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CompanyCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CompanyCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CompanyCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CompanyCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CompanyCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class CompanyCategoriesTable extends Table
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

        $this->setTable('company_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree', [
            'level' => 'level', // salva sul campo "level" del database il livello; necessario per ricerca categoria
        ]);

        $this->addBehavior('Translate', [
            'fields'                 => ['name'],
            'allowEmptyTranslations' => false,
            'translationTable'       => 'I18nCompanyCategories'

            // Nel caso servisse una validazione per le traduzioni
            //'validator'              => 'translated',
        ]);

        $this->belongsTo('Parents', [
            'className' => 'CompanyCategories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Childrens', [
            'className' => 'CompanyCategories',
            'foreignKey' => 'parent_id'
        ]);

        $this->belongsToMany('Categories', [
            'className'  => 'CompanyCategories',
            'through'    => 'CompanyCategoryThroughs',
            'foreignKey' => 'category_id'
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
            ->integer('level')
            //->requirePresence('level', 'create')
            ->notEmpty('level');

        $validator
            ->allowEmpty('name');

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
        $rules->add($rules->existsIn(['parent_id'], 'Parents'));

        return $rules;
    }
}
