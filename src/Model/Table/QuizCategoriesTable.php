<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;

/**
 * QuizCategories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentQuizCategories
 * @property \Cake\ORM\Association\HasMany $ChildQuizCategories
 *
 * @method \App\Model\Entity\QuizCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\QuizCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QuizCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QuizCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QuizCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QuizCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QuizCategory findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class QuizCategoriesTable extends Table
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

        $this->setTable('quiz_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree', [
            'level' => 'level', // salva sul campo "level" del database il livello; necessario per ricerca categoria
        ]);

        $this->addBehavior('Translate', [
            'fields'                 => ['name'],
            'allowEmptyTranslations' => false,
            'translationTable'       => 'I18n_quiz_categories'

            // Nel caso servisse una validazione per le traduzioni
            //'validator'              => 'translated',
        ]);


        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'cover' => [
                'path'   => 'webroot{DS}uploads{DS}quiz_categories{DS}cover{DS}{field-value:id}',
                'fields' => [
                    'type' => 'cover__type', // QuizQuestion.type utilizzato per altro
                    'size' => 'cover__size',
                    'dir'  => 'cover__dir'
                ],
                'nameCallback' => function ($uploadData, $settings) {
                    //$ext = pathinfo($uploadData['name'], PATHINFO_EXTENSION);
                    //return \Cake\Utility\Text::uuid() .'.'. $ext;
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
                    foreach (['300x150'] as $size) {
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

        $this->belongsTo('ParentQuizCategories', [
            'className' => 'QuizCategories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildQuizCategories', [
            'className' => 'QuizCategories',
            'foreignKey' => 'parent_id'
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

        $validator->notEmpty('name');


        $validator->allowEmpty('cover');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentQuizCategories'));

        return $rules;
    }


    /**
     * Categorie calde
     */
    public function findPrimary(\Cake\ORM\Query $q)
    {
        $PopularCategories = TableRegistry::get('HomepagePopularCategories');
        $ids = $PopularCategories->find('list')->enableHydration(false)->toList();

        // Previene eccezione quando $ids è vuoto (Datasource)
        if (!empty($ids)) {
            $q->where(['QuizCategories.id IN' => $ids]);
        }

        return $q;
    }
}
