<?php
namespace App\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

use Cake\Validation\Validator;

/**
 * QuizQuestions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Quizzes
 * @property \Cake\ORM\Association\HasMany $QuizAnswers
 *
 * @method \App\Model\Entity\QuizQuestion get($primaryKey, $options = [])
 * @method \App\Model\Entity\QuizQuestion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QuizQuestion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QuizQuestion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QuizQuestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QuizQuestion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QuizQuestion findOrCreate($search, callable $callback = null)
 */
class QuizQuestionsTable extends Table
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

        $this->table('quiz_questions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Quizzes', [
            'foreignKey' => 'quiz_id'
        ]);
        $this->hasMany('QuizAnswers', [
            'foreignKey' => 'quiz_question_id'
        ]);

        $this->addBehavior('Timestamp');

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'source_book_photo' => [
                'path'   => 'webroot{DS}uploads{DS}quiz_questions{DS}sources{DS}{field-value:quiz_id}{DS}{field-value:uuid}',
                'fields' => [
                    'type' => 'source_book_photo__type', // QuizQuestion.type utilizzato per altro
                    'size' => 'source_book_photo__size',
                    'dir'  => 'source_book_photo__dir'
                ],
                // This can also be in a class that implements
                // the TransformerInterface or any callable type.
                'transformer' => function (
                    \Cake\Datasource\RepositoryInterface $table,
                    \Cake\Datasource\EntityInterface $entity, $data, $field, $settings
                ) {
                    // get the extension from the file
                    // there could be better ways to do this, and it will fail
                    // if the file has no extension
                    $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                    // Store the thumbnail in a temporary file
                    $tmp = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                    // Use the Imagine library to DO THE THING
                    // $size    = new \Imagine\Image\Box(40, 40);
                    // $mode    = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                    // $imagine = new \Imagine\Gd\Imagine();
                    // // Save that modified file to our temp file
                    // $imagine->open($data['tmp_name'])
                    //     ->thumbnail($size, $mode)
                    //     ->save($tmp);

                    // Now return the original *and* the thumbnail
                    $filename = pathinfo($data['name'], PATHINFO_FILENAME);
                    $name     = \Cake\Utility\Inflector::slug($filename, '-') .'.'. $extension;

                    return [
                        $data['tmp_name'] => $name,
                        //$tmp              => 'thumb-' . $name,
                    ];
                }
            ]
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
        $validator->provider('quiz', 'App\Model\Validation\QuizValidation');
        $validator->provider('url', 'App\Model\Validation\UrlValidation');

        $validator = $this->validationSource($validator);

        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('type')
            ->add('type', 'typeInList', ['rule' => ['inList', ['default', 'true_or_false'], false]]);

        $validator
            ->notEmpty('question');

        $validator
            ->integer('complexity')
            ->range('complexity', [1, 10])
            ->requirePresence('complexity', 'create')
            ->notEmpty('complexity');

        $min_chars = Configure::read('app.quizQuestion.minChars');
        $max_chars = Configure::read('app.quizQuestion.maxChars');
        $validator->lengthBetween(
            'question',
            [$min_chars, $max_chars],
            __('La domanda deve essere lunga da {min_chars} a {max_chars}', compact('min_chars', 'max_chars'))
        );

        $validator->requirePresence('quiz_answers', 'create');

        return $validator;
    }

    /**
     * Validazione della fonte
     *
     * @param  Validator $Validator
     * @return Validator
     */
    public function validationSource(Validator $Validator) {
        // URL
        $Validator
            ->requirePresence('source_url', [$this, 'onSourceUrl'], __('Campo richiesto'))
            ->notEmpty('source_url', __('Campo obbligatorio'), [$this, 'onSourceUrl'])
            ->add('source_url', 'url', [
                'rule'    => 'url',
                'message' => __('URL invalido'),
                'on'      => [$this, 'onSourceUrl']
            ])
            ->add('source_url', 'wikipediaUrl', [
                'rule'     => ['domainWhitelist', ['*.wikipedia.org']],
                'provider' => 'url',
                'message'  => __('Sono abilitati solo link provenienti da Wikipedia'),
                'on'       => [$this, 'onSourceUrl']
            ]);

        // LIBRO
        $Validator
            ->requirePresence('source_book_title', [$this, 'onSourceBook'], __('Campo richiesto'))
            ->notEmpty('source_book_title', __('Campo obbligatorio'), [$this, 'onSourceBook'])

            ->requirePresence('source_book_page', [$this, 'onSourceBook'], __('Campo richiesto'))
            ->notEmpty('source_book_page', __('Campo obbligatorio'), [$this, 'onSourceBook'])

            ->requirePresence('source_book_photo', [$this, 'onSourceBook'], __('Campo richiesto'))
            ->notEmpty('source_book_photo', __('Campo obbligatorio'), [$this, 'onSourceBook'])

            ->add('source_book_photo', 'uploadedFile', [
                'rule'    => 'uploadedFile',
                'message' => __('Allega la scansione della pagina'),
                'on'      => [$this, 'onSourceBook']
            ])
            ->add('source_book_photo', 'uploadError', [
                'rule'    => 'uploadError',
                'message' => __('Impossibile caricare file'),
                'on'      => [$this, 'onSourceBook']
            ])
            ->add('source_book_photo', 'uploadedMimetype', [
                'rule'    => ['mimeType', ['image/png', 'image/jpg', 'image/jpeg', 'image/gif']],
                'message' => __('Formato file non abilitato (.png, .gif, .jpg, .jpeg)'),
                'on'      => [$this, 'onSourceBook']
            ]);


        return $Validator;
    }


    public function validationQuizDefault(Validator $validator)
    {
        $validator = $this->validationDefault($validator);

        $validator->add('quiz_answers', 'checkAnswerRequired', [
            'rule' => function($value, $context) {
                return sizeof($value) == (int) Configure::read('app.quizQuestion.default.answersCount');
            },
            'message' => __('Questa tipologia di quiz necessita di {0} risposte', [4])
        ]);

        return $validator;
    }

    public function validationQuizTrueOrFalse(Validator $validator)
    {
        $validator = $this->validationDefault($validator);

        $validator->add('quiz_answers', 'checkAnswerRequired', [
            'rule' => function($value, $context) {
                return sizeof($value) == (int) Configure::read('app.quizQuestion.true_or_false.answersCount');
            },
            'message' => __('Questa tipologia di quiz necessita di {0} risposte', [2])
        ]);

        return $validator;
    }


    static public function onSourceUrl($context) {
        return isset($context['data']['source_type']) && $context['data']['source_type'] == 'url';
    }

    static public function onSourceBook($context) {
        return isset($context['data']['source_type']) && $context['data']['source_type'] == 'book';
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

        // TODO
        //$rules->add($rules->validCount('quiz_id', 10, '>=', 'Non è possibile inserire più di 10 domande per quiz'));
        return $rules;
    }

    /**
     * Verifica che le risposte alla domanda siano 4 (default), 2 (true_or_false)
     *
     * @param  array $value
     * @param  array $context
     * @return boolean
     */
    static public function checkAnswersCount($value, $context)
    {
        debug($context['data']['type']);
        debug(sizeof($value));

        switch ($context['data']['type']) {
            case 'true_or_false':
                return sizeof($value) == 2;
            break;

            default:
                return sizeof($value) == 4;
        }
    }

    static public function hasOnlyOneCorrectAnswer($value, $context)
    {
        $is_correct = 0;

        foreach ($context['data']['quiz_answers'] as $question) {
            if ($question['is_correct'] == '1') {
                $is_correct++;
            }
        }

        return $is_correct == 1;
    }

    /**
     * Restituisce solo domande pubblicate
     *
     * Un admin potrebbe disattivare una domanda perchè inesatta
     *
     * @todo
     * @param  Query  $q
     * @return Query
     */
    protected function findIsPublished(Query $q) {
        $q->where(['QuizQuestions.is_published' => true]);
        $q->where(['QuizQuestions.is_banned' => false]);
        return $q;
    }

    /**
     * Restituisce tutte le entity QuizQuestions in base al complexity
     *
     * @param  \Cake\ORM\Query $q
     * @param  array           $settings
     * @return \Cake\ORM\Query
     */
    public function findQuestionsByLevel(\Cake\ORM\Query $q, $settings = [])
    {
        if (empty($settings['complexity'])) {
            throw new \RunTimeException(__('{0} required paramenter complexity', __CLASS__));
        }

        if (isset($settings['quiz_id']) && !empty($settings['quiz_id'])) {
            $q->where(['QuizQuestions.quiz_id' => $settings['quiz_id']]);
        }

        $q->where(['QuizQuestions.complexity IN' => $settings['complexity']]);

        $q->find('isPublished');

        return $q;
    }


    /**
     * Restituisce tutte le entity QuizQuestions in base al complexity
     *
     * @param  \Cake\ORM\Query $q
     * @param  array           $settings
     * @return \Cake\ORM\Query
     */
    public function findCountByLevel(\Cake\ORM\Query $q, $settings = [])
    {
        if (isset($settings['quiz_id']) && !empty($settings['quiz_id'])) {
            $q->where([ 'QuizQuestions.quiz_id' => $settings['quiz_id'] ], ['QuizQuestions.quiz_id' => 'integer']);
        }

        // FUTURE:
        // $easy   = Configure::read('app.quiz.funjob.answerDifficultyLevel.1');
        // $medium = Configure::read('app.quiz.funjob.answerDifficultyLevel.2');
        // $hard   = Configure::read('app.quiz.funjob.answerDifficultyLevel.3');

        $easy = $q->newExpr()->addCase(
            $q->newExpr()->add(['QuizQuestions.complexity' => [1,2,3]], ['QuizQuestions.complexity' => 'integer[]']),
            'easy',
            'string'
        );
        $medium = $q->newExpr()->addCase(
            $q->newExpr()->add(['QuizQuestions.complexity' => [4,5,6,7]], ['QuizQuestions.complexity' => 'integer[]']),
            'medium',
            'string'
        );
        $hard = $q->newExpr()->addCase(
            $q->newExpr()->add(['QuizQuestions.complexity' => [8,9,10]], ['QuizQuestions.complexity' => 'integer[]']),
            'hard',
            'string'
        );

        $q->select([
            'easy'   => $q->func()->count($easy),
            'medium' => $q->func()->count($medium),
            'hard'   => $q->func()->count($hard)
        ]);

        $q->find('isPublished');

        return $q;
    }


    /**
     * Ricerca parole chiave nelle domande e risposte
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    public function findSearchByTerm(Query $q, $settings = [])
    {
        if (!isset($settings['term'])) {
            throw new \RunTimeException(__('{0} required paramenter "term"', __CLASS__));
        }

        if (isset($settings['in_answers']) && $settings['in_answers']) {
            $q->leftJoinWith('QuizAnswers', function($q) {
                $q->select(['id', 'quiz_question_id', 'answer', 'is_correct']);
                return $q;
            });

            // Raggruppa per quiz_question_id
            $q->group(['QuizQuestions.id']);
            $q->group(['QuizQuestions.quiz_id']);
        }


        $q->where(function($exp, $q) use ($settings) {
            $term = $settings['term'];

            if (strpos($term, '*') === false) {
                $term = '*' .$term. '*';
            }

            $q->bind(':searchTerm', $term);

            $_searchCondition = [
                'MATCH(QuizQuestions.question) AGAINST(:searchTerm IN BOOLEAN MODE)'
            ];

            if (isset($settings['in_answers']) && $settings['in_answers']) {
                $_searchCondition[] = 'MATCH(QuizAnswers.answer) AGAINST(:searchTerm IN BOOLEAN MODE)';
            }

            return $exp->or_($_searchCondition);
        });

        return $q;
    }

    /**
     * Restituisce le domande per una determinata categoria
     *
     * Supporta più categorie
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    public function findbyCategory(Query $q, $settings = [])
    {
        if (!isset($settings['category_id'])) {
            throw new \RunTimeException(__('{0} required paramenter "category_id"', __CLASS__));
        }

        $ids = $settings['category_id'];
        $ids = !is_array($ids) ? (array) $ids : $ids;

        $q->matching('Quizzes.Categories', function($q) use ($ids) {
            $q->where(['Categories.id' => $ids], ['Categories.id' => 'integer[]']);
            return $q;
        });

        return $q;
    }

    /**
     * Restituisce se la domanda $settings['question_id'] è già presente nelle domande di $settings['quiz_id']
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    protected function findAlreadyInQuiz(Query $q, $settings = [])
    {
        if (!isset($settings['quiz_id']) || empty($settings['quiz_id'])) {
            throw new \RunTimeException(__('{0} required paramenter "quiz_id"', __CLASS__));
        }

        if (!isset($settings['question_id']) || empty($settings['question_id'])) {
            throw new \RunTimeException(__('{0} required paramenter "question_id"', __CLASS__));
        }

        $question_id = (int) $settings['question_id'];
        $quiz_id     = (int) $settings['quiz_id'];

        $q->where([
            'QuizQuestions.quiz_id'      => $quiz_id,
            'QuizQuestions.is_published' => true // potrebbe essere stata disabilitata (dall'utente o dall'admin)
        ]);

        $q->where(function ($exp) use ($question_id) {
            return $exp->or_([
                'QuizQuestions.id'        => $question_id,
                'QuizQuestions.cloned_by' => $question_id
            ]);
        });

        return $q;
    }

}
