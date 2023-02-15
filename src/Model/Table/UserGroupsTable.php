<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\Event\Event;
use ArrayObject;

/**
 * UserGroups Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserGroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserGroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserGroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserGroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserGroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserGroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserGroup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserGroupsTable extends Table
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

        $this->setTable('user_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Trim', [
            'name' => []
        ]);

        // Uploads
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'image' => [
                'path'   => 'webroot{DS}uploads{DS}user_groups{DS}cover{DS}{field-value:id}',
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
                    return 'cover.' . $extension;
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
                        //$mode    = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                        $imagine = new \Imagine\Gd\Imagine();

                        // EXACT ASPECT RATIO
                        // See: http://harikt.com/blog/2012/12/17/resize-image-keeping-aspect-ratio-in-imagine/
                        /*
                        $resizeimg = $imagine->open($data['tmp_name'])->thumbnail($boxSize, $mode);
                        $sizeR     = $resizeimg->getSize();
                        $widthR    = $sizeR->getWidth();
                        $heightR   = $sizeR->getHeight();
                        $preserve  = $imagine->create($boxSize);
                        $startX = $startY = 0;

                        if ( $widthR < $w ) {
                            $startX = ( $w - $widthR ) / 2;
                        }
                        if ( $heightR < $h ) {
                            $startY = ( $h - $heightR ) / 2;
                        }
                        $preserve->paste($resizeimg, new \Imagine\Image\Point($startX, $startY))->save($tmp);
                        // EXACT ASPECT RATIO (END)
                        */

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

        // Proprietario gruppo (chi ha creato il gruppo)
        // Verrà sostituito da relazione Administrator (role = owner) in futuro
        $this->belongsTo('Owners', [
            'className'  => 'Users',
            'foreignKey' => 'user_id'
        ]);

        // Amministratori di gruppo
        $this->hasMany('Administrators', [
            'className'  => 'UserGroupMembers',
            'foreignKey' => 'group_id',
            'conditions' => [
                'role IN' => ['owner', 'administrator']
            ]
        ]);

        $this->hasMany('UserGroupMembers', [
            'foreignKey' => 'group_id'
        ]);

        $this->belongsToMany('Members', [
            'className'        => 'Users',
            'through'          => 'UserGroupMembers',
            'foreignKey'       => 'group_id',
            'targetForeignKey' => 'user_id',
            'dependent'        => true
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
            ->requirePresence(['name', 'descr'], 'create')
            ->requirePresence(['descr'], 'update');

        $validator->allowEmpty(['descr', 'image', 'keywords']);

        $validator->add('name', [
            'maxChars' => [
                'rule'    => ['maxLength', 100],
                'message' => __('Massiamo 100 caratteri')
            ]
        ]);

        $validator->add('keywords', [
            'maxChars' => [
                'rule'    => ['maxLength', 255],
                'message' => __('Massiamo 255 caratteri')
            ]
        ]);

        $this->validationUploadCover($validator);

        // $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);
        // $validator->provider('upload', \Josegonzalez\Upload\Validation\DefaultValidation::class);
        // $validator->provider('upload', \Josegonzalez\Upload\Validation\ImageValidation::class);

        // Dimensione massima
        // $validator->add('image', 'fileAboveMinHeight', [
        //     'rule'     => ['isAboveMinHeight', 400],
        //     'message'  => __('Questa immagine dovrebbe essere più grande di 400 px in altezza'),
        //     'provider' => 'upload'
        // ]);
        // $validator->add('image', 'fileAboveMinWidth', [
        //     'rule'     => ['isAboveMinWidth', 400],
        //     'message'  => __('Questa immagine dovrebbe essere più grande di 400 px in larghezza'),
        //     'provider' => 'upload'
        // ]);

        // // Dimensione minima
        // $validator->add('image', 'fileBelowMinHeight', [
        //     'rule'     => ['isBelowMaxHeight', 1000],
        //     'message'  => __('Questa immagine NON dovrebbe essere più grande di 1000 px in altezza'),
        //     'provider' => 'upload'
        // ]);
        // $validator->add('image', 'fileBelowMinWidth', [
        //     'rule'     => ['isBelowMaxWidth', 1000],
        //     'message'  => __('Questa immagine NON dovrebbe essere più grande di 1000 px in larghezza'),
        //     'provider' => 'upload'
        // ]);

        return $validator;
    }

    public function validationUploadCover(Validator $validator) {
        // Upload
        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\ImageValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\DefaultValidation::class);

        $validator->add('image', 'isValidMime', [
            'rule'    => ['mimeType', ['image/jpg', 'image/jpeg', 'image/gif', 'image/png']],
            'message' => __('Estenzioni accettate: jpg, jpeg, gif, png'),
            'on'      => function ($context) {
                return !empty($context['data']['image']);
            }
        ]);

        // In bytes
        // 100 Kb => 100 000 bytes
        $maxSize = 150000;
        $validator->add('image', 'fileBelowMaxSize', [
            'rule'     => ['isBelowMaxSize', $maxSize],
            'message'  => __(
                'Dimensione massima immagine: {size} KB',
                ['size' => $maxSize / 1000]
            ),
            'provider' => 'upload'
        ]);

        $minHeight = 400;
        $validator->add('image', 'fileAboveMinHeight', [
            'rule'     => ['isAboveMinHeight', $minHeight],
            'message'  => __(
                'L\'altezza minima dell\'immagine deve essere superiore a {minSize} px',
                ['minSize' => $minHeight]
            ),
            'provider' => 'upload'
        ]);

        $minWidth = 400;
        $validator->add('image', 'fileAboveMinWidth', [
            'rule'     => ['isAboveMinWidth', $minWidth],
            'message'  => __(
                'La larghezza minima dell\'immagine deve essere superiore a {minSize} px',
                ['minSize' => $minWidth]
            ),
            'provider' => 'upload'
        ]);

        // Dimensione minima
        $maxHeight = 1000;
        $validator->add('image', 'fileBelowMinHeight', [
            'rule'     => ['isBelowMaxHeight', 1000],
            'message'  => __('Questa immagine NON dovrebbe essere più grande di 1000 px in altezza'),
            'provider' => 'upload'
        ]);
        $maxWidth = 1000;
        $validator->add('image', 'fileBelowMinWidth', [
            'rule'     => ['isBelowMaxWidth', 1000],
            'message'  => __('Questa immagine NON dovrebbe essere più grande di 1000 px in larghezza'),
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
        // Funzionante (in add)
        //$rules->add($rules->existsIn(['user_id'], 'UserGroupMembers', __('Utente non esistente')));
        $rules->addCreate($rules->isUnique(['slug'], __('Gruppo già esistente')));

        return $rules;
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        // Crea slug da titolo
        if (isset($data['name'])) {
            $data['slug'] = \Cake\Utility\Text::slug(strtolower($data['name']), '-');
        }
    }

    /**
     * Restituisce ultimi Gruppi creati
     *
     * @param  \Cake\ORM\Query $q
     * @return \Cake\ORM\Query
     */
    public function findLatests(\Cake\ORM\Query $q)
    {
        return $q->orderDesc('UserGroups.id');
    }

    /**
     * Restituisce il numero dei membri di un gruppo
     *
     * Attualmente viene utilizzato il componente CounterCache anzichè questo find
     *
     * @param  \Cake\ORM\Query $q
     * @return \Cake\ORM\Query
     */
    public function findMembersCount(\Cake\ORM\Query $q)
    {
        $q->contain([
            'Owners'  => function($q) {
                return $q->select(['id', 'first_name', 'last_name', 'username']);
            },
            'Members' => function($q) {
                $q->select([
                    'total' => $q->func()->count('*')
                ]);

                $q->group('UserGroupMembers.group_id');
                return $q;
            }
        ]);

        return $q;
    }

    /**
     * Ricerca gruppi
     *
     * @param  \Cake\ORM\Query $q
     * @param  array           $settings
     * @return \Cake\ORM\Query
     */
    public function findSearch(\Cake\ORM\Query $q, $settings = [])
    {
        if (empty($settings['name'])) {
            throw new \RuntimeException(__d('dev', 'Parametro "termine" richiesto'));
        }

        $term = $settings['name'];

        if (strpos($settings['name'], '*') === false) {
            $term = $term . '*';
        }

        $q->bind(':groupTerm', $term, 'string');

        $q->where(function($exp, $q) {
            return $exp->or_([
                'MATCH(keywords) AGAINST(:groupTerm IN BOOLEAN MODE)',
                'MATCH(name) AGAINST(:groupTerm IN BOOLEAN MODE)'
            ]);
        });

        return $q;
    }
}
