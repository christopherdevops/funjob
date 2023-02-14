<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\I18n\I18n;
use Cake\Cache\Cache;
use Cake\Core\Configure;

use Hiryu85\Traits\TableFindsTrait;

/**
 * Quizzes Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $QuizQuestions
 * @property \Cake\ORM\Association\HasMany $QuizSessions
 *
 * @method \App\Model\Entity\Quiz get($primaryKey, $options = [])
 * @method \App\Model\Entity\Quiz newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Quiz[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Quiz|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Quiz patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Quiz[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Quiz findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class QuizzesTable extends Table
{
    use TableFindsTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('quizzes');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('StripTags', [
            'title'       => ['allowable_tags' => []],
            'descr'       => ['allowable_tags' => []],
            'video_embed' => ['allowable_tags' => ['iframe']],
            'href'        => ['allowable_tags' => []]
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'image__src' => [
                'path'   => 'webroot{DS}uploads{DS}quiz{DS}cover{DS}{field-value:uuid}',
                'fields' => [
                    'type' => 'image__type', // QuizQuestion.type utilizzato per altro
                    'size' => 'image__size',
                    'dir'  => 'image__dir'
                ],
                'nameCallback' => function ($uploadData, $settings) {
                    //$ext = pathinfo($uploadData['name'], PATHINFO_EXTENSION);
                    //return \Cake\Utility\String::uuid() .'.'. $ext;
                    return 'cover.jpg'; // overrides file precedente
                },
                // This can also be in a class that implements
                // the TransformerInterface or any callable type.
                'transformer' => function (
                    \Cake\Datasource\RepositoryInterface $table,
                    \Cake\Datasource\EntityInterface $entity, $data, $field, $settings)
                {
                    // get the extension from the file
                    // there could be better ways to do this, and it will fail
                    // if the file has no extension
                    $extension = pathinfo($data['name'], PATHINFO_EXTENSION);

                    // Now return the original *and* the thumbnail
                    $filename = pathinfo($data['name'], PATHINFO_FILENAME);
                    $slug     = $filename; // \Cake\Utility\Inflector::slug($filename, '-');
                    $name     = $slug .'.'. $extension;

                    $files = [
                        $data['tmp_name'] => $name
                    ];

                    $imagine = new \Imagine\Imagick\Imagine();
                    $image   = $imagine->open($data['tmp_name']);

                    // Strip image: exif
                    $iccProfile = $image->palette()->profile();
                    $image->strip();
                    $image->profile($iccProfile);

                    // TODO: creare app.quiz.thumbnail.sizes
                    foreach (['1400x800', '500x300', '300x150', '900x400'] as $size) {
                        // Store the thumbnail in a temporary file
                        $tmp = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                        // Use the Imagine library to DO THE THING
                        list($w,$h) = explode('x', $size);

                        $boxSize = new \Imagine\Image\Box($w, $h);
                        $imagine = new \Imagine\Gd\Imagine();
                        //$mode    = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

                        // Save that modified file to our temp file
                        //$imagine->open($data['tmp_name'])
                        $image
                            ->thumbnail(
                                $boxSize,
                                \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
                                \Imagine\Image\ImageInterface::FILTER_LANCZOS
                            )
                            ->save($tmp);

                        $files[ $tmp ] = $slug . '--'. $size. '.' . $extension;
                    }

                    return $files;
                }
            ]
        ]);

        $this->belongsTo('Author', [
            'className'  => 'Users',
            'foreignKey' => 'user_id'
        ]);

        // $this->belongsTo('QuizCategories', [
        //     'foreignKey' => 'quiz_category_id'
        // ]);

        $this->hasMany('QuizQuestions', [
            'foreignKey' => 'quiz_id'
        ]);

        $this->hasMany('QuizSessions', [
            'foreignKey' => 'quiz_id'
        ]);

        $this->belongsToMany('Categories', [
            'className' => 'QuizCategories',
            'joinTable' => 'categories_quizzes',
            'onlyIds'   => true
        ]);

        $this->hasMany('Tags', [
            'className' => 'QuizTags',
        ]);

        $this->hasMany('UserRankings', [
            'className'  => 'QuizUserRankings',
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
        $validator->integer('id')->allowEmpty('id', 'create');
        $validator->allowEmpty(['embed_video', 'href', 'image__src']);

        $validator
            ->allowEmpty('descr')
            ->notEmpty(['title']);

        $validator->add('color', 'isHex', [
            'rule'    => 'hexColor',
            'message' => __('Richiede un colore esadecimale (#f0f0f0)')
        ]);


        $validator->provider('VideoEmbedValidation', '\Hiryu85\Model\Validation\VideoEmbedValidation');
        $validator->add('foreground_video_embed', 'isEmbedCode', [
            'rule'     => ['iframeExists'],
            'message'  => __('Richiede un codice embed (HTLM)'),
            'provider' => 'VideoEmbedValidation'
        ]);
        $validator->add('foreground_video_embed', 'isEmbedCode', [
            'rule'     => ['iframeWhitelist', ['www.youtube.com', 'player.vimeo.com']],
            'message'  => __('Questo host non è abilitato per l\'embed video'),
            'provider' => 'VideoEmbedValidation'
        ]);


        $validator->add('href', 'isUrl', [
            'rule'    => 'url',
            'message' => __('Richiede un indirizzo valido (con http://)')
        ]);

        $this->validateUploadCover($validator);

        return $validator;
    }

    /**
     * Validazione Cover
     * UploadBehavior
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validateUploadCover(Validator $validator)
    {
        // Upload validation

        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\ImageValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\DefaultValidation::class);

        $validator->add('image__src', 'uploadedMimetype', [
            'rule'    => ['mimeType', Configure::read('funjob.upload.extensions')],
            'message' => __('Formato file non abilitato ({mimes})', [
                'mimes' => implode(', ', Configure::read('funjob.upload.extensions'))
            ]),
        ]);

        // In bytes
        // 100 Kb => 100 000 bytes
        $validator->add('image__src', 'fileBelowMaxSize', [
            'rule'     => ['isBelowMaxSize', Configure::read('funjob.upload.maxSize')],
            'message'  => __(
                'Dimensione massima immagine: {size} KB',
                ['size' => Configure::read('funjob.upload.maxSize') / 1000]
            ),
            'provider' => 'upload'
        ]);

        $validator->add('image__src', 'fileAboveMinHeight', [
            'rule'     => ['isAboveMinHeight', Configure::read('funjob.upload.minHeight')],
            'message'  => __(
                'L\'altezza minima dell\'immagine deve essere superiore a {minSize} px',
                ['minSize' => Configure::read('funjob.upload.minHeight')]
            ),
            'provider' => 'upload'
        ]);
        $validator->add('image__src', 'fileAboveMinWidth', [
            'rule'     => ['isAboveMinWidth', Configure::read('funjob.upload.minWidth')],
            'message'  => __(
                'La larghezza minima dell\'immagine deve essere superiore a {minSize} px',
                ['minSize' => Configure::read('funjob.upload.minWidth')]
            ),
            'provider' => 'upload'
        ]);

        return $validator;
    }

    public function beforeSave($event, $entity, $options)
    {
        if ($entity->tag_string) {
            $entity->tags = $this->_buildTags($entity->tag_string);
        }
    }

    protected function _buildTags($tagString)
    {
        // Trim tags
        $newTags = array_map('trim', explode(',', $tagString));
        // Remove all empty tags
        $newTags = array_filter($newTags);
        // Reduce duplicated tags
        $newTags = array_unique($newTags);

        $out = [];
        $query = $this->Tags->find()
            ->where(['Tags.tag IN' => $newTags]);

        // Remove existing tags from the list of new tags.
        foreach ($query->extract('tag') as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }
        // Add existing tags.
        foreach ($query as $tag) {
            $out[] = $tag;
        }
        // Add new tags.
        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['tag' => $tag]);
        }
        return $out;
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
        $rules->add($rules->existsIn(['user_id'], 'Author'));

        // NOTA:
        // in fase di update non vengono passate le categorie
        $max = 10;
        $rules->addCreate($rules->validCount('categories', 1, '>=', __('Seleziona una categoria')));
        $rules->addCreate($rules->validCount('categories', $max, '<=', __('Poi selezionare non più di {max} categorie', ['max' => $max])));

        return $rules;
    }

    /**
     * Restituisce solo quiz non disabilitate (dall'admin)
     *
     * @param  Query  $q
     * @return Query
     */
    public function findNotDisabled(Query $q)
    {
        $field = $this->alias() . '.is_disabled';
        return $q->where([$field => false]);
    }

    /**
     * Restituisce solo i Quiz pubblicati
     */
    public function findPublished(Query $q)
    {
        $q->find('notDisabled');

        $field = $this->alias() . '.status';
        return $q->where([$field => ['published']], [$field => 'string[]']);
    }

    /**
     * Restituisce i quiz ordinati per ultimo inserimento
     */
    public function findLatests($Query)
    {
        return $Query
            ->find('published')
            ->orderDesc('Quizzes.id');
    }

    /**
     * Restituisce solo i pubblicati
     */
    public function findArchive(Query $q)
    {
        $q->find('published');
        $q->find('notDisaled');
        return $q;
    }

    /**
     * Restituisce i quiz di un autore
     * @param  Query  $q
     * @param  array $settings
     * @return Query
     */
    public function findByAuthor(Query $q, $settings) {
        if (empty($settings['user_id'])) {
            throw new \Exception();
        }

        $q->where(['user_id' => $settings['user_id']], ['user_id' => 'integer']);
        return $q;
    }

    /**
     * Bind autore
     *
     * @param  Query $q
     * @return Query
     */
    public function findWithAuthor(Query $q, $settings = [])
    {
        $q->contain([
            'Author' => function($q) {
                $q->select(['id', 'avatar', 'username']);
                return $q;
            }
        ]);
        return $q;
    }

    /**
     * Restituisce anche le domande disponibili per ogni quiz
     *
     * @param  Query  $q
     * @return Query
     */
    protected function findWithQuestionsCounter(Query $q) {
        $q->select(['published_questions' => $q->func()->count('QuizQuestions.quiz_id')]);
        $q->leftJoinWith('QuizQuestions', function($q) {
            $q->find('isPublished');
            return $q;
        });
        $q->group(['Quizzes.id']);

        return $q;
    }

    /**
     * Con ranking utente
     *
     * @param  Query  $q [description]
     * @return [type]    [description]
     */
    protected function findWithRanking(Query $q) {
        $q->leftJoinWith('UserRankings', function($q) {
            $q->select(['UserRankings.quiz_id', '_avg' => $q->func()->avg('rank')]);
            return $q;
        });
        $q->group(['UserRankings.quiz_id']);

        // $q->select(['_ranking' => $q->func()->avg('UserRankings.rank')]);
        // $q->leftJoinWith('UserRankings', function($q) {
        //     return $q;
        // });
        // $q->group(['Quizzes.id']);

        return $q;
    }

    /**
     * Ricerca quiz in base a termine di ricerca (titolo quiz)
     *
     * @param  Query  $q
     * @param  array  $settings
     *         str  $settings['term'] Termine di ricerca
     *         bool $settings['tags'] Ricerca termine anche tra i tags del quiz
     * @return Query
     */
    protected function findSearchByTerm(Query $q, $settings)
    {
        $_defaults = [
            'tags' => false
        ];
        $settings = array_merge($_defaults, $settings);


        if (!isset($settings['term'])) {
            throw new \Exception();
        }

        $term = $settings['term'];
        // Wildcard
        if (strpos('*', $term) === FALSE) {
            $term .= '*';
        }

        $q->bind(':searchTerm', $term);


        if ($settings['tags']) {
            $q->leftJoinWith('Tags');
            $q->andWhere(function($exp) {
                return $exp->or_([
                    'MATCH(Quizzes.title) AGAINST(:searchTerm IN BOOLEAN MODE)',
                    'MATCH(Tags.tag) AGAINST(:searchTerm IN BOOLEAN MODE)'
                ]);
            });
        } else {
            $q->where([
                'MATCH(Quizzes.title) AGAINST(:searchTerm IN BOOLEAN MODE)'
            ]);
        }

        return $q;
    }

    /**
     * Mostra solo i quizzes per una determinata categoria
     *
     * @param Query $query
     * @param array $settings
     * $settings = [
     *     'category_id' => [id]
     * ]
     */
    public function findByCategory($q, $settings)
    {
        extract($settings);

        if (empty($category_id)) {
            throw new \Cake\Network\Exception\BadRequestException('QuizzesTable::findByCategory need parameter "category_id"');
        }

        $q->matching('Categories', function($query) use ($category_id) {
            $query->where(['Categories.id' => $category_id], ['Categories.id' => 'integer[]']);
            return $query;
        });

        return $q;
    }

    /**
     * Restituisce solo quiz in base al type
     *
     * @param  \Cake\ORM\Query $Query    [description]
     * @param  array           $settings [description]
     * @return [type]                    [description]
     */
    public function findByType(\Cake\ORM\Query $Query, $settings = [])
    {
        if (empty($settings['type'])) {
            throw new \Cake\Network\Exception\BadRequestException(__('{0} need parameter "type"', __METHOD__));
        }

        $Query->where(['Quizzes.type = :quizType']);
        $Query->bind(':quizType', $settings['type']);

        return $Query;
    }

    /**
     * Estra domanda quiz con relativa risposta
     *
     * @param  Query $Query
     * @param  array $settings
     * $settings = [
     *     'quiz_id' => integer,
     *     'answerOnlyCorrect' => bool
     * ]
     */
    public function findQuestions($Query, $settings) {
        extract($settings);

        if (empty($quiz_id)) {
            throw new \RunTimeException('quiz_id parameter required');
        }

        $Query->where(['id' => $quiz_id]);
        $Query->contain([
            'QuizQuestions' => function($q) use ($quiz_id, $settings) {
                $q->where(['quiz_id' => $quiz_id]);
                $q->contain([
                    'QuizAnswers' => function($q) use ($settings) {
                        if (!empty($settings['answerOnlyCorrect'])) {
                            $q->where(['is_correct' => true]);
                        }

                        return $q;
                    }
                ]);

                return $q;
            },
        ]);

        return $Query;
    }

    /**
     * Restituisce quizzes in base allo stato
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    protected function findByStatus(Query $q, $settings = [])
    {
        $this->requireSetting($settings, 'status');

        switch($settings['status']) {

            case 'draft':
                $q->where([
                    'Quizzes.status'      => 'draft',
                    'Quizzes.is_disabled' => false
                ]);
            break;

            // Non in bozza, e pubblicati
            case 'published':
                $q->find('published');
            break;

            case 'unpublished':
                $q->where(function($exp, $q) {
                    $alias = $this->alias();

                    return $exp->or_([
                        $alias . '.status <> "published"',
                        $alias . '.is_disabled' => true
                    ]);
                });
            break;

            // Nascosti (disabilitati dall'admin)
            // PS: ban
            case 'disabled':
                $q->where(['Quizzes.is_disabled' => true]);
            break;
        }

        return $q;
    }


    /**
     * Restituisce le categorie di tutti i quizzes giocati
     *
     * @return Query
     */
    public function findCreatedByCategories(Query $q, $settings = [])
    {
        if (empty($settings['user_id'])) {
            throw new \Exception(__('{class} parameter: $settings["user"] required', ['class' => __CLASS__]));
        }

        $q->contain([
            'Categories'
        ]);

        // Solo sessioni visibili nel diario
        $q->where([
            $this->alias() . '.status'      => 'published',
            $this->alias() . '.is_disabled' => false,
            $this->alias() . '.user_id'     => (int) $settings['user_id']
        ]);

        // Scorre ResultSet e crea array:
        // "id_cat|Nome categoria" => [
        //      0 => id_quiz
        // ]
        $mapper = function ($entity, $key, $mapReduce) {
            $TableQuizCategories = \Cake\ORM\TableRegistry::get('QuizCategories');

            $k   = $entity['id'];
            $value = null;

            if (empty($entity['categories'])) {
                $mapReduce->emitIntermediate($entity['id'], '|' . __('Nessun categoria'));
            } else {
                foreach ($entity['categories'] as  $category) {
                    $cacheKey = sprintf('%s_paths_%d', I18n::locale(), $category['id']);
                    $path     = Cache::remember($cacheKey, function() use ($TableQuizCategories, $category) {
                        return $TableQuizCategories
                            ->find('path', ['for' => $category['id']])
                            ->select(['id', 'name', 'parent_id'])
                            ->hydrate(false)
                            ->toArray();
                    }, 'quiz_categories');

                    $nodes = [];
                    foreach ($path as $category) { $nodes[] = $category['name']; }

                    $name = implode(' > ', $nodes);
                    //$name  = $category['name'];
                    $name = '' . $category['id'] .'|'. $name;

                    $mapReduce->emitIntermediate($entity['id'], $name);
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


    public function findWithUserRankingAVG(Query $q)
    {
        $q->leftJoinWith('UserRankings', function($q) {
            $q->select(['UserRankings.quiz_id', '_avg' => $q->func()->avg('rank')]);
            return $q;
        });
        $q->group(['UserRankings.quiz_id']);
        $q->orderDesc('_avg');

        return $q;
    }

}
