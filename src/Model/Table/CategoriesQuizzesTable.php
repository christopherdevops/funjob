<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CategoriesQuizzes Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Quizzes
 * @property \Cake\ORM\Association\BelongsTo $Categories
 *
 * @method \App\Model\Entity\CategoriesQuiz get($primaryKey, $options = [])
 * @method \App\Model\Entity\CategoriesQuiz newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CategoriesQuiz[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CategoriesQuiz|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CategoriesQuiz patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CategoriesQuiz[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CategoriesQuiz findOrCreate($search, callable $callback = null)
 */
class CategoriesQuizzesTable extends Table
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

        $this->belongsTo('Quizzes', [
            'foreignKey' => 'quiz_id'
        ]);
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
        $rules->add($rules->existsIn(['quiz_id'], 'Quizzes'));
        $rules->add($rules->existsIn(['category_id'], 'Categories'));

        return $rules;
    }
}
