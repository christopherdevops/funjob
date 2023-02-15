<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QuizUserRankings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Quizzes
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\QuizUserRanking get($primaryKey, $options = [])
 * @method \App\Model\Entity\QuizUserRanking newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QuizUserRanking[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QuizUserRanking|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QuizUserRanking patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QuizUserRanking[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QuizUserRanking findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class QuizUserRankingsTable extends Table
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

        $this->setTable('quiz_user_rankings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Quizzes', [
            'foreignKey' => 'quiz_id'
        ]);
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
            ->integer('rank')
            ->add('rank', 'isRanking', [
                'rule'    => ['range', 1, 10],
                'message' => __('Numero da 1 a 10')
            ]);

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
        $rules->add($rules->existsIn(['quiz_id'], 'Quizzes'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->addCreate($rules->isUnique(['user_id', 'quiz_id'], __('Voto già esistente')));

        return $rules;
    }

    /**
     * Quiz popolari
     *
     * @param  Query  $q
     * @return Query
     */
    public function findPopular(Query $q)
    {
        $q->select([
            '_avg' => $q->func()->avg('rank')
        ]);

        $q->contain([
            'Quizzes' => function($q) {
                $q->select(['id', 'title', 'type', 'descr', 'image__src', 'image__dir']);
                return $q;
            }
        ]);

        $q->group(['quiz_id']);
        $q->orderDesc('_avg');

        return $q;
    }

}
