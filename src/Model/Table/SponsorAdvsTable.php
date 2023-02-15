<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Database\Expression\IdentifierExpression;

use Hiryu85\Traits\TableFindsTrait;


/**
 * SponsorAdvs Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\SponsorAdv get($primaryKey, $options = [])
 * @method \App\Model\Entity\SponsorAdv newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SponsorAdv[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdv|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SponsorAdv patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdv[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdv findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SponsorAdvsTable extends Table
{
    use TableFindsTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->table('sponsor_advs');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('Clicks', [
            'className'  => 'SponsorAdvClicks',
            'foreignKey' => 'sponsor_adv_id'
        ]);
        $this->hasMany('Views', [
            'className'  => 'SponsorAdvViews',
            'foreignKey' => 'adv_id'
        ]);


        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'banner__img' => [
                'path'   => 'webroot{DS}uploads{DS}sponsor_advs{DS}banner{DS}{field-value:uuid}',
                'fields' => [
                    'type' => 'banner__mime',
                    'dir'  => 'banner__dir',
                    'size' => 'banner__size',
                ],

                'nameCallback' => function ($uploadData, $settings) {
                    return 'image.jpg'; // overrides file precedente
                },

                // This can also be in a class that implements
                // the TransformerInterface or any callable type.
                'transformer' => function (
                    \Cake\Datasource\RepositoryInterface $table,
                    \Cake\Datasource\EntityInterface $entity,
                    $data,
                    $field,
                    $settings
                ) {
                    // get the extension from the file
                    // there could be better ways to do this, and it will fail
                    // if the file has no extension
                    //$extension = pathinfo($data['name'], PATHINFO_EXTENSION);

                    $extension = 'jpg';

                    // Store the thumbnail in a temporary file
                    $tmp = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                    // Use the Imagine library to DO THE THING
                    $size    = new \Imagine\Image\Box(200, 200);
                    $mode    = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                    $imagine = new \Imagine\Gd\Imagine();
                    // Save that modified file to our temp file
                    $imagine->open($data['tmp_name'])
                        ->thumbnail($size, $mode)
                        ->save($tmp);

                    // Now return the original *and* the thumbnail
                    $filename = pathinfo($data['name'], PATHINFO_FILENAME);
                    $name     = \Cake\Utility\Inflector::slug($filename, '-') .'.'. $extension;
                    $slug     = 'image';
                    $extension = 'jpg';

                    $files = [
                        $data['tmp_name'] => $name,
                        $tmp              => 'thumb-' . $name,
                    ];

                    // TODO:
                    // Size in base al tipo


                    // TODO: creare app.quiz.thumbnail.sizes
                    foreach (['153x173', '400x400'] as $size) {
                        // Store the thumbnail in a temporary file
                        $tmp = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                        // Use the Imagine library to DO THE THING
                        list($w,$h) = explode('x', $size);

                        $boxSize = new \Imagine\Image\Box($w, $h);
                        $mode    = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
                        $imagine = new \Imagine\Gd\Imagine();

                        // Save that modified file to our temp file
                        $imagine->open($data['tmp_name'])
                            ->thumbnail($boxSize, $mode)
                            ->save($tmp, [
                                'flatten'               => false,
                                'jpeg_quality'          => 100,
                                'png_compression_level' => 9
                            ]);

                        $files[ $tmp ] = $slug . '--'. $size. '.' . $extension;
                    }

                    return $files;
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
    public function validationDefault(Validator $validator): \Cake\Validation\Validator
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->notEmpty(['descr', 'title'], __('Campo obbligatorio'));

        $validator
            ->allowEmpty('banner__dir');

        $validator
            ->dateTime('active_from')
            ->allowEmpty('active_from');

        $validator
            ->dateTime('active_to')
            ->allowEmpty('active_to');

        $validator
            ->integer('is_published')
            ->allowEmpty('is_published');

        $validator
            ->integer('filter_for_age')
            ->allowEmpty('filter_for_age');

        $validator
            ->notEmpty('filter_for_sex', __('Campo obbligatorio'))
            ->add('filter_for_sex', 'typeInList', ['rule' => ['inList', ['male', 'female', 'all'], false]]);

        $validator
            ->integer('filter_for_age__from')
            ->allowEmpty('filter_for_age__from');

        $validator
            ->integer('filter_for_age__to')
            ->allowEmpty('filter_for_age__to');

        $validator->add('href', 'isUrl', [
            'message' => __('Valore errato: richiede un indirizzo URL'),
            'rule'    => ['url', false]
        ]);

        $validator->add('href', 'hasHttp', [
            'message' => __('Deve iniziare con http:// o https://'),
            'rule'    => function ($value, $context) {
                return strpos($value, 'http') === 0;
            },
            'on' => function ($context) {
                return !empty($context['data']['href']);
            }
        ]);

        $validator->add('accept_term_and_conditions', 'isChecked', [
            'rule'    => ['equalTo', '1'],
            'message' => __('Accetta le condizioni')
        ]);


        $validator->add('title', 'maxLength', [
            'rule'    => ['maxLength', 100],
            'message' => __('Massimo 100 caratteri')
        ]);

        $validator->add('descr', 'maxLength', [
            'rule'    => ['maxLength', 150],
            'message' => __('Massimo 150 caratteri')
        ]);

        $billingFields = ['billing_name', 'billing_cf_vat', 'billing_address', 'billing_city', 'billing_state', 'billing_cap'];
        $validator->notEmpty($billingFields, __('Campo obbligatorio'));
        $validator->add('billing_email', 'isEmail', [
            'rule' => ['email'],
        ]);

        return $validator;
    }

    /**
     * Validazione Cover
     * UploadBehavior
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationUploadBanner(Validator $validator)
    {
        $validator = $this->validationDefault($validator);

        // Upload validation
        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\ImageValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\DefaultValidation::class);

        $validator->requirePresence('banner__img');
        $validator->add('banner__img', 'uploadedFile', [
            'rule'    => 'uploadedFile',
            'message' => __('Campo obbligatorio'),
            'on'      => 'create'
        ]);
        $validator->add('banner__img', 'uploadError', [
            'rule'    => 'uploadError',
            'message' => __('Impossibile caricare file'),
            'on'      => 'create'
        ]);
        $validator->add('banner__img', 'uploadedMimetype', [
            'rule'    => ['mimeType', ['image/png', 'image/jpg', 'image/jpeg']],
            'message' => __('Formato file non abilitato (.png, .jpg, .jpeg)'),
            'on'      => 'create'
        ]);

        $min_height = 200;
        $validator->add('banner__img', 'fileAboveMinHeight', [
            'rule'     => ['isAboveMinHeight', $min_height],
            'message'  => __('Deve avere un altezza maggiore di {min_height} pixel', ['min_height' => $min_height]),
            'provider' => 'upload'
        ]);

        $min_width = 200;
        $validator->add('banner__img', 'fileAboveMinWidth', [
            'rule'     => ['isAboveMinWidth', $min_width],
            'message'  => __('Deve avere un larghezza maggiore di {min_width} pixel', ['min_width' => $min_width]),
            'provider' => 'upload'
        ]);

        // In bytes
        // 100 Kb => 100 000 bytes
        $max_KB = 200;
        $validator->add('banner__img', 'fileBelowMaxSize', [
            'rule'     => ['isBelowMaxSize', ($max_KB * 1000)],
            'message'  => __('Dimensione massima immagine: {max_kb} KB', ['max_kb' => $max_KB]),
            'provider' => 'upload'
        ]);

        return $validator;
    }

    public function validationUploadBannerQuiz(Validator $validator)
    {
        $validator = $this->validationDefault($validator);

        // Upload validation
        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\ImageValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\DefaultValidation::class);

        $validator->requirePresence('banner__img');
        $validator->add('banner__img', 'uploadedFile', [
            'rule'    => 'uploadedFile',
            'message' => __('Campo obbligatorio'),
            'on'      => 'create'
        ]);
        $validator->add('banner__img', 'uploadError', [
            'rule'    => 'uploadError',
            'message' => __('Impossibile caricare file'),
            'on'      => 'create'
        ]);
        $validator->add('banner__img', 'uploadedMimetype', [
            'rule'    => ['mimeType', ['image/png', 'image/jpg', 'image/jpeg']],
            'message' => __('Formato file non abilitato (.png, .jpg, .jpeg)'),
            'on'      => 'create'
        ]);

        $min_height = 400;
        $validator->add('banner__img', 'fileAboveMinHeight', [
            'rule'     => ['isAboveMinHeight', $min_height],
            'message'  => __('Deve avere un altezza maggiore di {min_height} pixel', ['min_height' => $min_height]),
            'provider' => 'upload'
        ]);

        $min_width = 400;
        $validator->add('banner__img', 'fileAboveMinWidth', [
            'rule'     => ['isAboveMinWidth', $min_width],
            'message'  => __('Deve avere un larghezza maggiore di {min_width} pixel', ['min_width' => $min_width]),
            'provider' => 'upload'
        ]);

        // In bytes
        // 100 Kb => 100 000 bytes
        $max_KB = 100;
        $validator->add('banner__img', 'fileBelowMaxSize', [
            'rule'     => ['isBelowMaxSize', ($max_KB * 1000)],
            'message'  => __('Dimensione massima immagine: {max_kb} KB', ['max_kb' => $max_KB]),
            'provider' => 'upload'
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }


    /**
     * Restituisce adv pubblicati
     *
     * Per pubblicato si intende quando è stato approvato dall'admin
     * 1. Si è ricevuto il pagamento
     *
     * @param  Query $query
     * @return Query
     */
    public function findIsPublished($query)
    {
        return $query->where(['is_published' => true]);
    }

    /**
     * Restituisce solo ADV che sono attivi
     * (hanno impressioni, e la data odierna è compresa in quella dell'adv')
     */
    public function findIsActive(Query $q)
    {
        $q->where(function($exp) {
            $gte = $exp->and_(function($condition) {
                return $condition->gt('impression_lefts', 0);
            });

            return $exp->add( $exp->and_($gte) );
        });

        $q->where(function($exp, $q) {
            return $exp->between(
                $q->func()->now(),
                new IdentifierExpression('active_from'),
                new IdentifierExpression('active_to')
            );
        });

        return $q;
    }

    protected function findByType(Query $q, $settings = []) {
        $this->requireSetting($settings, 'type');

        $q->where(['SponsorAdvs.type' => $settings['type']], ['type' => 'string']);

        return $q;
    }


    /**
     * Filtra gli annunci in base all'età dell'utente
     *
     * Questo filtro ha bisogno di users.birthday completato
     *
     * @param  Query  $q
     * @param  array $settings
     *         int $age età utente
     * @return Query
     */
    protected function findFilterForUserAge(Query $q, $settings)
    {
        $this->requireSetting($settings, 'age');

        $age = $settings['age'];

        // Filtro su età non dichiarato
        $q->andWhere(function($exp) use ($age) {
            return $exp->add( $exp->and_(['filter_for_age__from IS NULL', 'filter_for_age__to IS NULL']) );
        });
        // Filtro su età dichiarato
        $q->orWhere(function($exp) use ($age) {
            $condition = $exp->and_(function($condition) use ($age) {
                $condition->lte('filter_for_age__from', $age);
                $condition->gte('filter_for_age__to', $age);
                return $condition;
            });

            return $exp->add($condition);
        });

        return $q;
    }

    /**
     * Filtra gli annunci in base al sesso definito dell'utente
     *
     * Il sesso non è un campo obbligatorio
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    protected function findFilterForUserSex(Query $q, $settings = [])
    {
        $this->requireSetting($settings, 'sex');

        if (!empty($settings['sex'])) {
            $q->where(['filter_for_sex IN' => [$settings['sex'], 'all']], ['filter_for_sex' => 'string']);
        } else {
            $q->where(['filter_for_sex' => 'all'], ['filter_for_sex' => 'string']);
        }

        return $q;
    }

    /**
     * Filtra gli annunci in base al sesso definito dell'utente
     *
     * Il sesso non è un campo obbligatorio
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    protected function findFilterForCountry(Query $q, $settings = [])
    {
        $this->requireSetting($settings, 'country');

        if (!empty($settings['country'])) {
            $q->where(['filter_for_country IN' => ['all', $settings['country']]], ['filter_for_country' => 'string']);
        } else {
            $q->where(['filter_for_country' => 'all'], ['filter_for_country' => 'string']);
        }

        return $q;
    }

}
