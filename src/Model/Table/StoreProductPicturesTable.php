<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StoreProductPictures Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\StoreProductPicture get($primaryKey, $options = [])
 * @method \App\Model\Entity\StoreProductPicture newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StoreProductPicture[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StoreProductPicture|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StoreProductPicture patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StoreProductPicture[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StoreProductPicture findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StoreProductPicturesTable extends Table
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

        $this->setTable('store_product_pictures');
        $this->setDisplayField('image');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Products', [
            'className'  => 'StoreProducts',
            'foreignKey' => 'product_id'
        ]);


        // Uploads
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'image' => [
                'path'   => 'webroot{DS}uploads{DS}store{DS}product{DS}pictures{DS}{field-value:product_id}',
                'fields' => [
                    'type' => 'type',
                    'size' => 'size',
                    'dir'  => 'dir'
                ],

                'nameCallback' => function($uploadData, $settings) {
                    // get the extension from the file
                    // there could be better ways to do this, and it will fail
                    // if the file has no extension
                    $extension = pathinfo($uploadData['name'], PATHINFO_EXTENSION);

                    // Now return the original *and* the thumbnail
                    $filename = pathinfo($uploadData['name'], PATHINFO_FILENAME);
                    $slug     = \Cake\Utility\Inflector::slug($filename, '-');
                    $name     = $slug .'.'. $extension;

                    return $name;
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
                    $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                    // Now return the original *and* the thumbnail
                    $filename = pathinfo($data['name'], PATHINFO_FILENAME);

                    $files = [
                        $data['tmp_name'] => $data['name']
                    ];

                    // TODO: creare app.user.thumbnail.sizes
                    foreach (['100x100', '200x200', '400x400'] as $size) {
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
                            ->save($tmp);

                        $files[ $tmp ] = $filename . '--'. $size. '.' . $extension;
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
            ->allowEmpty('image');

        $validator
            ->allowEmpty('dir');

        // Upload validation
        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\ImageValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\DefaultValidation::class);

        // Mimetypes
        $validator->add('image', 'isValidMime', [
            'rule'    => ['mimeType', ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']],
            'message' => __('Carica un immagine')
        ]);

        // Errori upload
        $validator->add('image', 'fileFileUpload', [
            'rule'     => 'isFileUpload',
            'message'  => __('Non è stato trovato nessun file da caricare'),
            'provider' => 'upload'
        ]);
        $validator->add('image', 'fileSuccessfulWrite', [
            'rule'     => 'isSuccessfulWrite',
            'message'  => __('Errore file system durante l\'upload'),
            'provider' => 'upload'
        ]);

        // Dimensioni minime
        $validator->add('image', 'fileAboveMinHeight', [
            'rule'     => ['isAboveMinHeight', 200],
            'message'  => __('Questa immagine richiede un altezza superiore di 200 pixel'),
            'provider' => 'upload'
        ]);
        $validator->add('image', 'fileAboveMinWidth', [
            'rule'     => ['isAboveMinHeight', 200],
            'message'  => __('Questa immagine richiede una larghezza superiore a 200 pixel'),
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
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }
}
