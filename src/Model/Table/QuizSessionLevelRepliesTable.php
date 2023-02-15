<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QuizSessionLevelReplies Model
 *
 * @property \Cake\ORM\Association\BelongsTo $QuizSessionLevels
 * @property \Cake\ORM\Association\BelongsTo $Questions
 * @property \Cake\ORM\Association\BelongsTo $QuizAnswers
 *
 * @method \App\Model\Entity\QuizSessionLevelReply get($primaryKey, $options = [])
 * @method \App\Model\Entity\QuizSessionLevelReply newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QuizSessionLevelReply[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QuizSessionLevelReply|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QuizSessionLevelReply patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QuizSessionLevelReply[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QuizSessionLevelReply findOrCreate($search, callable $callback = null, $options = [])
 */
class QuizSessionLevelRepliesTable extends Table
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

        $this->table('quiz_session_level_replies');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('QuizSessionLevels', [
            'foreignKey' => 'quiz_session_level_id'
        ]);
        $this->belongsTo('Questions', [
            'className'  => 'QuizQuestions',
            'foreignKey' => 'question_id'
        ]);
        $this->belongsTo('Answers', [
            'className'  => 'QuizAnswers',
            'foreignKey' => 'answer_id'
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
        $rules->add($rules->existsIn(['quiz_session_level_id'], 'QuizSessionLevels'));
        $rules->add($rules->existsIn(['question_id'], 'Questions'));
        $rules->add($rules->existsIn(['answer_id'], 'Answers'));

        return $rules;
    }
}
