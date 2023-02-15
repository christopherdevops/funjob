<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HomepagePopularCategories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentQuizCategories
 * @property \Cake\ORM\Association\HasMany $ChildQuizCategories
 *
 * @method \App\Model\Entity\QuizCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\QuizCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QuizCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QuizCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QuizCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QuizCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QuizCategory findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class HomepagePopularCategoriesTable extends Table
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

        $this->table('homepage_popular_categories');
        $this->displayField('category_id');
        $this->primaryKey('id');


        $this->belongsTo('Categories', [
            'className'  => 'QuizCategories',
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
        $rules->add($rules->existsIn(['category_id'], 'Categories'));

        return $rules;
    }


    /**
     * Categorie calde
     *
     * PS: non più utilizzato?
     */
    public function findPrimary(\Cake\ORM\Query $q)
    {
        //$ids = \Cake\Configure\Configure::read('');
        $ids = [1,5,9,13,10,11,6,5,4];

        return $q->where(['QuizCategories.id IN' => $ids]);
    }
}
