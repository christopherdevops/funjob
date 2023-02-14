<?php
namespace App\Traits;

use Cake\Event\Event;
use Cake\Event\EventManager;

use ArrayObject;


trait UserTableTrait {

    /**
     * Associazioni globali
     */
    public function setGlobalAssociations()
    {
        // Quiz creati
        $this->hasMany('Quizzes', [
            'foreignKey' => 'user_id'
        ]);

        // Sessioni di gioco
        $this->hasMany('QuizSessions');

        // Crediti utenti
        $this->hasOne('Credits', [
            'className'  => 'UserCredits',
            'foreignKey' => 'user_id'
        ]);

        // Ordini creati
        $this->hasMany('StoreOrders', [
            'foreignKey' => 'user_id'
        ]);

        // Gruppi utenti dove iscritto
        $this->belongsToMany('MemberOfGroups', [
            'className' => 'UserGroups',
            'through'   => 'UserGroupMembers'
        ]);

        // Amici
        $this->hasMany('Friends', [
            'className' => 'UserFriends'
        ]);

        // Lista utenti ignorati
        $this->hasMany('UserIgnoreLists', [
            'className' => 'UserIgnoreLists',
        ]);
    }


    public function setGlobalBehaviors()
    {
        $this->addBehavior('Timestamp');

        /* HybridAuth plugin */
        $this->hasMany('ADmad/HybridAuth.SocialProfiles');
        EventManager::instance()->on('HybridAuth.newUser', [$this, 'createUser']);

        // Uploads
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'avatar' => [
                'path'   => 'webroot{DS}uploads{DS}user{DS}avatar{DS}{field-value:id}',
                'fields' => [
                    'type' => 'image__type',
                    'size' => 'image__size',
                    'dir'  => 'image__dir'
                ],
                'nameCallback' => function($uploadData, $settings) {
                    return 'avatar.jpg';
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
                    $slug     = \Cake\Utility\Inflector::slug($filename, '-');
                    $name     = $slug .'.'. $extension;

                    $files = [
                        $data['tmp_name'] => $name
                    ];

                    // TODO: creare app.user.thumbnail.sizes
                    foreach (['28x28', '32x32', '80x80'] as $size) {
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

                        $files[ $tmp ] = $slug . '--'. $size. '.' . $extension;
                    }

                    return $files;
                }
            ],
        ]);
    }

}
