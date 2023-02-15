<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QuizAnswers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $QuizQuestions
 *
 * @method \App\Model\Entity\QuizAnswer get($primaryKey, $options = [])
 * @method \App\Model\Entity\QuizAnswer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QuizAnswer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QuizAnswer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QuizAnswer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QuizAnswer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QuizAnswer findOrCreate($search, callable $callback = null)
 */
class QuizAnswersTable extends Table
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

        $this->setTable('quiz_answers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('QuizQuestions', [
            'foreignKey' => 'quiz_question_id'
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
            ->requirePresence('answer')
            ->notEmpty('answer', __('Campo obbligatorio'));

        $validator
            ->requirePresence('is_correct')
            ->allowEmpty('is_correct')
            ->boolean('is_correct');

        $min_chars = 1;
        $max_chars = 45;
        $validator->lengthBetween(
            'answer',
            [$min_chars, $max_chars],
            __('La risposta deve essere lunga da {min_chars} a {max_chars}', compact('min_chars', 'max_chars'))
        );

        return $validator;
    }

    public function validationQuizDefault(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        return $validator;
    }

    public function validationQuizTrueOrFalse(Validator $validator)
    {
        $validator = $this->validationDefault($validator);

        // Rimuove validazione notEmpty su answer
        // Per le true_or_false l'answer è salvata come NULL
        $validator->allowEmpty('answer');

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
        $rules->add($rules->existsIn(['quiz_question_id'], 'QuizQuestions'));
        return $rules;
    }


    // public function beforeSave(Event $event, QuizAnswer $QuizAnswer) {
    //     if ($QuizAnswer->isNew()) {
    //         debug($QuizAnswer);

    //         switch ($QuizAnswer->type) {
    //             case 'true_or_false':
    //             break;

    //             default:
    //         }
    //     }

    //     return true;
    // }
}
