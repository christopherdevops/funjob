<?php
namespace App\Model\Table;

use App\Model\Entity\UserField;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\Entity;

use Cake\Validation\Validator;

use Cake\Event\Event;
use Cake\Event\EventManager;
use ArrayObject;


use Hashids\Hashids;

/**
 * UserFields Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserField get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserField newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserField[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserField|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserField patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserField[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserField findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserFieldsTable extends Table
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

        $this->setTable('user_fields');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'cv' => [
                'path'          => 'webroot{DS}uploads{DS}user{DS}cv{DS}{field-value:user_id}',
                'fields'        => [
                    'type' => 'cv__type',
                    'size' => 'cv__size',
                    'dir'  => 'cv__dir'
                ],

                // Elimina file se si elimina l'entity
                'keepFilesOnDelete' => false,

                // Nome file
                'nameCallback' => function($upload, $uploadSettings) {
                    $Hashids = new \Hashids\Hashids('aklsfiou7234i0tio3yufgioyadsuiy67fu89yhsdjklfgksdiugds90g', 25);
                    return $Hashids->encode($_POST['user_id']) . '.pdf';
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

        $validator->allowEmpty(['cv']);
        $validator->add('cv', [
            'mimeType' => [
                'rule'    => ['mimeType', ['application/pdf']],
                'message' => __('Estenzioni accettate: pdf'),
                'on'      => function($context) {
                    return !empty($context['data']['cv']);
                }
            ],
            // 'uploadError' => [
            //     'rule'    => 'uploadError',
            //     'message' => __('The logo upload failed.'),
            //     'on'      => function($context) {
            //         return !empty($context['data']['cv']);
            //     },

            //     'last'    => true
            // ],
    ]);

        $validator->allowEmpty('public_cv')->boolean('public_cv');

        $validator->allowEmpty('birthday');
        $validator->add('birthday', 'isValidDate', [
            'rule'    => ['date', 'ymd'],
            'message' => __('Data non valida')
        ]);

        $validator->allowEmpty(['profession']);
        $validator->add('profession', 'length', [
            'rule'    => ['maxLength', 40],
            'message' => __('Massimo 40 caratteri')
        ]);

        return $validator;
    }

    public function validationStoreRequirements(Validator $validator)
    {
        $validator->notEmpty(
            'address',
            __('Indirizzo di residenza richiesto (Pannello di controllo > Profilo > Indirizzo)')
        );

        $validator->notEmpty(
            'live_city',
            __('Indirizzo di residenza richiesto (città) (Pannello di controllo > Profilo > Indirizzo)')
        );

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


    public function beforeSave(\Cake\Event\EventInterface $event, Entity $Entity, ArrayObject $options)
    {
        // Previene errore quando viene inviato il valore empty (Nessun sesso specificato) del selectform"
        // Error: SQLSTATE[01000]: Warning: 1265 Data truncated for column 'sex' at row 1
        if (isset($Entity->sex)) {
            if (!in_array($Entity->sex, ['male', 'female'])) {
                $Entity->sex = null;
            }
        }
    }
}
