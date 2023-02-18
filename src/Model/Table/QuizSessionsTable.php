<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Cache\Cache;
use Cake\I18n\I18n;

/**
 * QuizSessions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Quizzes
 * @property \Cake\ORM\Association\BelongsTo $Categories
 *
 * @method \App\Model\Entity\QuizSession get($primaryKey, $options = [])
 * @method \App\Model\Entity\QuizSession newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QuizSession[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QuizSession|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QuizSession patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QuizSession[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QuizSession findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class QuizSessionsTable extends Table
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

        $this->setTable('quiz_sessions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsTo('Quizzes', [
            'foreignKey' => 'quiz_id',
            'joinType'   => 'INNER'
        ]);

        // $this->belongsTo('Categories', [
        //     'foreignKey' => 'category_id'
        // ]);


        $this->hasMany('Levels', [
            'className' => 'QuizSessionLevels',

            // Delete dei livello quando si elimina la QuizSession
            'dependent' => true,
        ]);

        $this->hasMany('LevelsPassed', [
            'className' => 'QuizSessionLevels',
            'finder'    => 'passed',

            // Delete dei livello quando si elimina la QuizSession
            'dependent' => true
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
            ->allowEmpty('lang');

        // $validator
        //     ->requirePresence('score', 'create')
        //     ->notEmpty('score');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['quiz_id'], 'Quizzes'));
        //$rules->add($rules->existsIn(['category_id'], 'Categories'));

        return $rules;
    }

    public function findIsVisible(Query $q) {
        return $q->where([
            $this->alias() . '.is_hidden'  => false,
            $this->alias() . '.is_deleted' => false
        ]);
    }

    /**
     * Restituisce le categorie di tutti i quizzes giocati
     *
     * @return Query
     */
    public function findPlayedCategories(Query $q, $settings = [])
    {
        if (empty($settings['user_id'])) {
            throw new \Exception(__('{class} parameter: $settings["user"] required', ['class' => __CLASS__]));
        }

        $q->contain([
            // Potrebbe essere non necessario avere il titolo del quiz (da eliminare eventualmente per velocizzare il tutto)
            //'Quizzes',
            'Quizzes.Categories'
        ]);

        // Solo sessioni visibili nel diario
        $q->where([
            $this->alias() . '.is_hidden'  => false,
            $this->alias() . '.is_deleted' => false,
            $this->alias() . '.user_id'    => (int) $settings['user_id']
        ]);

        // Scorre ResultSet e crea array:
        // "id_cat|Nome categoria" => [
        //      0 => id_quiz
        // ]
        $mapper = function ($entity, $key, $mapReduce) {
            $TableQuizCategories = \Cake\ORM\TableRegistry::get('QuizCategories');

            $k   = $entity['quiz']['id'];
            $value = null;

            if (empty($entity['quiz']['categories'])) {
                $mapReduce->emitIntermediate($entity['quiz']['id'], '|' . __('Nessun categoria'));
            } else {
                foreach ($entity['quiz']['categories'] as  $category) {
                    $cacheKey = sprintf('%s_paths_%d', I18n::getLocale(), $category['id']);
                    $path     = Cache::remember($cacheKey, function() use ($TableQuizCategories, $category) {
                        return $TableQuizCategories
                            ->find('path', ['for' => $category['id']])
                            ->select(['id', 'name', 'parent_id'])
                            ->enableHydration(false)
                            ->toArray();
                    }, 'quiz_categories');

                    $nodes = [];
                    foreach ($path as $category) { $nodes[] = $category['name']; }

                    $name = implode(' > ', $nodes);
                    //$name  = $category['name'];
                    $name = '' . $category['id'] .'|'. $name;

                    $mapReduce->emitIntermediate($entity['quiz']['id'], $name);
                }
            }
        };

        // Contatore: quiz per categoria
        $reducer = function ($quizzes, $categories, $mapReduce) {
            $mapReduce->emit(count($quizzes), $categories);
        };

        $q->mapReduce($mapper, $reducer);


        return $q;
    }

    /**
     * Quiz completati dall'utente
     *
     * @param  Query  $query
     * @param  array  $options [description]
     *
     * @return \Cake\ORM\ResultSet
     */
    public function findQuizCompleted(Query $query, array $options) {

        if (empty($options['user_id'])) {
            throw new \Cake\Network\Exception\BadRequestException('Parameter "user_id" is required');
        }

        $query->contain([
            'Quizzes' => function ($q) {
                $q->select(['id', 'title', 'type', 'image__src', 'image__dir', 'color']);
                $q->find('published');
                return $q;
            },
            'Quizzes.Categories',

            'LevelsPassed' => function($q) {
                $q->order(['level' => 'ASC']);
                return $q;
            }

            // Tutti i livelli
            // 'Levels' => function($q) {
            //     $q->order(['level' => 'ASC']);
            //     return $q;
            // }
        ]);

        $query->select([
            'QuizSessions.id',
            'QuizSessions.quiz_id',
            'QuizSessions.user_id',
        ]);

        $query->where([
            'QuizSessions.user_id'    => $options['user_id'],
            'QuizSessions.is_deleted' => false
        ]);

        // Solo quiz di una determinata tipologia
        if (!empty($options['quiz_type'])) {
            $query->matching('Quizzes', function($q) use ($options) {
                $q->where(['type' => $options['quiz_type']]);
                return $q;
            });
        }


        //$query->group('QuizSessions.id');

        return $query;
    }

}
