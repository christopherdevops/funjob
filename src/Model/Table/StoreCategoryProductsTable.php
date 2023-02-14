<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StoreCategoryProducts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Quizzes
 * @property \Cake\ORM\Association\BelongsTo $Categories
 *
 * @method \App\Model\Entity\StoreCategoryProduct get($primaryKey, $options = [])
 * @method \App\Model\Entity\StoreCategoryProduct newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StoreCategoryProduct[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StoreCategoryProduct|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StoreCategoryProduct patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StoreCategoryProduct[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StoreCategoryProduct findOrCreate($search, callable $callback = null)
 */
class StoreCategoryProductsTable extends Table
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

        $this->table('categories_quizzes');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('StoreProducts', [
            'foreignKey' => 'product_id'
        ]);
        $this->belongsTo('Categories', [
            'className'  => 'StoreProductCategories',
            'foreignKey' => 'category_id'
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
        $rules->add($rules->existsIn(['product_id'], 'StoreProducts'));
        $rules->add($rules->existsIn(['category_id'], 'Categories'));

        return $rules;
    }
}
