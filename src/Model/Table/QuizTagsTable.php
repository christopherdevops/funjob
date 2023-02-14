<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QuizTags Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Quizzes
 *
 * @method \App\Model\Entity\QuizTag get($primaryKey, $options = [])
 * @method \App\Model\Entity\QuizTag newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QuizTag[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QuizTag|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QuizTag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QuizTag[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QuizTag findOrCreate($search, callable $callback = null, $options = [])
 */
class QuizTagsTable extends Table
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

        $this->table('quiz_tags');
        $this->displayField('tag');
        $this->primaryKey('id');

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

        $validator
            ->allowEmpty('tag');

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

        return $rules;
    }
}
