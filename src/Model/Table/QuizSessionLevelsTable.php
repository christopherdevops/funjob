<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QuizSessionLevels Model
 *
 * @property \Cake\ORM\Association\BelongsTo $QuizSessions
 *
 * @method \App\Model\Entity\QuizSessionLevel get($primaryKey, $options = [])
 * @method \App\Model\Entity\QuizSessionLevel newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QuizSessionLevel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QuizSessionLevel|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QuizSessionLevel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QuizSessionLevel[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QuizSessionLevel findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class QuizSessionLevelsTable extends Table
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

        $this->table('quiz_session_levels');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('QuizSessions', [
            'foreignKey' => 'quiz_session_id'
        ]);

        $this->hasMany('Replies', [
            'className' => 'QuizSessionLevelReplies'
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
            ->integer('level')
            ->allowEmpty('level');

        $validator
            ->integer('points')
            ->allowEmpty('points');

        $validator
            ->integer('score')
            ->allowEmpty('score');

        $validator
            ->boolean('help_50perc_used')
            ->allowEmpty('help_50perc_used');

        $validator
            ->boolean('help_25perc_used')
            ->allowEmpty('help_25perc_used');

        $validator
            ->boolean('help_75perc_used')
            ->allowEmpty('help_75perc_used');

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
        $rules->add($rules->existsIn(['quiz_session_id'], 'QuizSessions'));

        return $rules;
    }


    /**
     * Restituisce solo i livelli passati
     *
     * Fà riferimento al campo "points"
     *
     * @param  \Cake\ORM\Query $q
     */
    public function findPassed($q)
    {
        $minScore = \Cake\Core\Configure::read('app.quiz.funjob.minScoreRequired');

        $q->where(['score >=' => $minScore]);
        $q->order('level', 'ASC');

        return $q;
    }

}
