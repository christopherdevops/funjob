<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StoreProductCategories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentStoreCategories
 * @property \Cake\ORM\Association\HasMany $ChildStoreCategories
 *
 * @method \App\Model\Entity\StoreCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\StoreCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StoreCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StoreCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StoreCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StoreCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StoreCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class StoreProductCategoriesTable extends Table
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

        $this->setTable('store_product_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree', [
            'level' => 'level', // salva sul campo "level" del database il livello
        ]);

        $this->addBehavior('Translate', [
            'fields'                 => ['name'],
            'allowEmptyTranslations' => false,
            'translationTable'       => 'i18n_store_product_categories'

            // Nel caso servisse una validazione per le traduzioni
            //'validator'              => 'translated',
        ]);

        $this->belongsTo('ParentStoreCategories', [
            'className' => 'StoreProductCategories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildStoreCategories', [
            'className' => 'StoreProductCategories',
            'foreignKey' => 'parent_id'
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

        $validator->integer('level');
        $validator->notEmpty('name');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentStoreCategories'));
        return $rules;
    }
}
