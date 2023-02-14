<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HomepagePopularQuizzes Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Quizzes
 *
 * @method \App\Model\Entity\HomepagePopularQuiz get($primaryKey, $options = [])
 * @method \App\Model\Entity\HomepagePopularQuiz newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HomepagePopularQuiz[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HomepagePopularQuiz|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HomepagePopularQuiz patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HomepagePopularQuiz[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HomepagePopularQuiz findOrCreate($search, callable $callback = null, $options = [])
 */
class HomepagePopularQuizzesTable extends Table
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

        $this->setTable('homepage_popular_quizzes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Quizzes', [
            'foreignKey' => 'quiz_id'
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
        $rules->add($rules->isUnique(['quiz_id'], 'Quizzes'));

        return $rules;
    }


    /**
     * Restituisce quiz popolari
     *
     * @param  Query $q
     * @return Query
     */
    public function findPopular(Query $q)
    {
        $q->matching('Quizzes', function($q) {
            $q->select(['id', 'title', 'status', 'image__src', 'image__dir']);
            $q->find('published');
            return $q;
        });

        return $q;
    }
}
