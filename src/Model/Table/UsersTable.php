<?php
namespace App\Model\Table;

use App\Traits\UserTableTrait;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use Cake\Validation\Validator;
use Cake\Core\Configure;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Utility\Text;
use ArrayObject;

use Hiryu85\Upload\File\Path\UserCvProcessor;
use Hiryu85\Traits\TableFindsTrait;
use Hiryu85\Model\UserRecoveryTrait;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $Quizzes
 * @property \Cake\ORM\Association\HasMany $StoreOrders
 * @property \Cake\ORM\Association\HasMany $StoreProducts
 * @property \Cake\ORM\Association\HasMany $UserCredits
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    use TableFindsTrait;
    use UserTableTrait;

    use UserRecoveryTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        // Associazioni (in comune per tutti gli utenti -in src/Traits/UserTableTrait-)
        $this->setGlobalAssociations();
        $this->setGlobalBehaviors();

        // Associazioni (solo per utenti "user")
        // PROFILES
        $this->belongsToMany('JobOffers', [
            'className'        => 'JobCategories',
            'joinTable'        => 'user_job_offers',
            'targetForeignKey' => 'job_id'
        ]);

        $this->belongsTo('BornCities', [
            'className'  => 'Cities',
            'strategy'   => 'select'
        ]);
        $this->belongsTo('LiveCities', [
            'className'  => 'Cities',
            'strategy'   => 'select'
        ]);

        $this->hasMany('UserSkills');

        $this->hasMany('UserJobOffers');
        $this->hasMany('CvAuthorizations');

        // PROFILE (solo per utenti)
        $this->hasOne('AccountInfos', [
            'foreignKey' => 'user_id',
            'className'  => '\App\Model\Table\UserFieldsTable',
        ]);
        $this->hasOne('ProfileBlocks', [
            'foreignKey' => 'user_id',
            'className'  => '\App\Model\Table\UserProfileBoxesTable',
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
        $validator->integer('id');

        $validator->allowEmpty('fullname', null, __('Campo obbligatorio'));
        $validator->email('email', false, __('Valore non valido'));

        $minLength = 3;
        $maxLength = 25;
        $validator->add('username', 'usernameLength', [
            'rule' => ['lengthBetween', $minLength, $maxLength],
            'message' => __('Il nickname deve essere di una lunghezza compresa dai {min_chars} ai {max_chars}', [
                'min_chars' => $minLength,
                'max_chars' => $maxLength
            ])
        ]);

        // Blacklist usernames
        $validator->add('username', 'notAllowedUsernames', [
            'message' => __('Questo username non è ammesso'),
            'rule'    => function($value, $context) {
                return !in_array($value, ['admin', 'administrator', 'moderator', 'owner']);
            }
        ]);
        $validator->add('username', 'reservedSocialRegisterUsername', [
            'message' => __('Questo username è riservato'),
            'rule'    => function($value, $context) {
                return strpos($value, 'funjob') !== 0;
            }
        ]);
        $validator->add('username', 'isValidNickname', [
            'message' => __('Caratteri non ammessi: utilizzare solo lettere, numeri, e _'),
            'rule'    => function($value, $context) {
                if (preg_match('/^[0-9a-z_]+/i', $value)) {
                    return true;
                }

                return false;
            }
        ]);

        $validator->allowEmpty('title');
        $validator->add('title', 'lenght', [
            'rule'    => ['maxLength', 140],
            'message' => __('Massimo 140 caratteri')
        ]);

        // UPLOADS
        $validator->allowEmpty(['avatar']);
        $this->validateUploadAvatar($validator);

        return $validator;
    }


    /**
     * Validazione Cover
     * UploadBehavior
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validateUploadAvatar(Validator $validator)
    {
        $validator->setProvider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);
        $validator->setProvider('upload', \Josegonzalez\Upload\Validation\ImageValidation::class);
        $validator->setProvider('upload', \Josegonzalez\Upload\Validation\DefaultValidation::class);

        $validator->add('avatar', 'uploadedMimetype', [
            'rule'    => ['mimeType', Configure::read('funjob.upload.extensions')],
            'message' => __('Formato file non abilitato ({mimes})', [
                'mimes' => implode(', ', Configure::read('funjob.upload.extensions'))
            ]),
        ]);

        // In bytes
        // 100 Kb => 100 000 bytes
        $validator->add('avatar', 'fileBelowMaxSize', [
            'rule'     => ['isBelowMaxSize', Configure::read('funjob.upload.maxSize')],
            'message'  => __(
                'Dimensione massima immagine: {size} KB',
                ['size' => Configure::read('funjob.upload.maxSize') / 1000]
            ),
            'provider' => 'upload'
        ]);

        // Dimensione minima
        $validator->add('avatar', 'fileAboveMinHeight', [
            'rule'     => ['isAboveMinHeight', 100],
            'message'  => __(
                'L\'altezza minima dell\'immagine deve essere superiore a {minSize} px',
                ['minSize' => Configure::read('funjob.upload.minHeight')]
            ),
            'provider' => 'upload'
        ]);
        $validator->add('avatar', 'fileAboveMinWidth', [
            'rule'     => ['isAboveMinWidth', 100],
            'message'  => __(
                'La larghezza minima dell\'immagine deve essere superiore a {minSize} px',
                ['minSize' => Configure::read('funjob.upload.minWidth')]
            ),
            'provider' => 'upload'
        ]);

        // Dimensione massima
        $validator->add('file', 'fileBelowMaxHeight', [
            'rule'     => ['isBelowMaxHeight', 400],
            'message'  => __('Dimensioni massime 400x400'),
            'provider' => 'upload'
        ]);
        $validator->add('file', 'fileBelowMaxWidth', [
            'rule'     => ['isBelowMaxWidt', 400],
            'message'  => __('Dimensioni massime 400x400'),
            'provider' => 'upload'
        ]);

        return $validator;
    }

    public function validationRegistration(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        $validator->requirePresence(['username', 'email', 'password', 'password_confirm', 'type'], __('Campo obbligatorio'));
        $validator->notEmpty(['username', 'email', 'password', 'password_confirm', 'type'], __('Campo obbligatorio'));

        $validator->add('first_name', 'userFirstName', [
            'rule'    => 'notBlank',
            'message' => __('Campo obbligatorio'),
            'on'      => function($context) {
                return $context['data']['type'] == 'user';
            }
        ]);
        $validator->add('last_name', 'userFirstName', [
            'rule'    => 'notBlank',
            'message' => __('Campo obbligatorio'),
            'on'      => function($context) {
                return $context['data']['type'] == 'user';
            }
        ]);

        $validator->notEmpty('first_name', __('Campo obbligatorio'), function($context) {
            return $context['data']['type'] == 'user';
        });
        $validator->notEmpty('last_name', __('Campo obbligatorio'), function($context) {
            return $context['data']['type'] == 'user';
        });
        $validator->notEmpty('name', __('Campo obbligatorio'), function($context) {
            return $context['data']['type'] == 'company';
        });

        $validator->add('password', [
            'minLength' => [
                'rule'    => ['minLength', 5],
                'message' => __('Caratteri minimi per password: 5')
            ]
        ]);

        $validator->add('password_confirm', [
            'compare' => [
                'rule'    => ['compareWith', 'password'],
                'message' => __('La password di conferma non è uguale alla password')
            ]
        ]);

        $validator->requirePresence('type');
        $validator->notBlank('type');
        $validator->add('type', 'isNonAdminAccount', [
            'message' => __('Tipologia account sconosciuta'),
            'rule'    => function($value, $context) {
                return in_array($value, ['user', 'company']);
            }
        ]);

        // $validator
        //     ->requirePresence('type')
        //     ->notEmpty('type')
        //     ->inList('type', ['user', 'company']);

        $validator
            ->requirePresence('accept_terms', true, __('Devi accettare i termini e le condizioni'))
            ->notEquals('accept_terms', 0, __('Devi accettare i termini e le condizioni'));

        return $validator;
    }

    /**
     * Validazione per aggiornamento impostazioni account (privato)
     *
     * @param  Validator $validator
     * @return Validator
     */
    public function validationSettingsAccountUser(Validator $validator)
    {
        //$validator = $this->validationDefault($validator);
        //$validator->requirePresence(['email']);
        $validator->allowEmpty(['password', 'password_confirm']);

        $validator->add('password', [
            'minLength' => [
                'rule'    => ['minLength', 5],
                'message' => __('Caratteri minimi per password: 5'),
                'on'      => function($context) {
                    return !empty($context['data']['password']);
                }
            ],
        ]);

        $validator->add('password_confirm', [
            'compare' => [
                'rule'    => ['compareWith', 'password'],
                'message' => __('La password di conferma non è uguale alla password'),
                'on'      => function($context) {
                    return !empty($context['data']['password']);
                }
            ],
        ]);

        return $validator;
    }

    public function validationSettingsAccountCompany(Validator $validator)
    {
        //$validator = $this->validationDefault($validator);
        //$validator->requirePresence(['email']);
        $validator->allowEmpty(['password', 'password_confirm']);

        $validator->add('password', [
            'minLength' => [
                'rule'    => ['minLength', 5],
                'message' => __('Caratteri minimi per password: 5'),
                'on'      => function($context) {
                    return !empty($context['data']['password']);
                }
            ],
        ]);

        $validator->add('password_confirm', [
            'compare' => [
                'rule'    => ['compareWith', 'password'],
                'message' => __('La password di conferma non è uguale alla password'),
                'on'      => function($context) {
                    return !empty($context['data']['password']);
                }
            ],
        ]);

        return $validator;
    }

    public function validationSettingsProfileUser(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        return $validator;
    }

    public function validationSettingsProfileCompany(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        return $validator;
    }

    public function validationSettingsJobUser(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        return $validator;
    }

    /**
     * Recupero password
     *
     * @param  Validator $validator [description]
     * @return Validator
     */
    public function validationAccountReset(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        $validator->requirePresence(['password', 'password_confirm'], __('Campo obbligatorio'));
        $validator->notEmpty(['password', 'password_confirm'], __('Campo obbligatorio'));

        $validator->add('password', [
            'minLength' => [
                'rule'    => ['minLength', 5],
                'message' => __('Caratteri minimi per password: 5')
            ]
        ]);

        $validator->add('password_confirm', [
            'compare' => [
                'rule'    => ['compareWith', 'password'],
                'message' => __('La password di conferma non è uguale alla password')
            ]
        ]);

        return $validator;
    }

    /**
     * Admin: aggiornamento flag is_bigbrain da edit utente
     *
     * @param  Validator $validator
     * @return Validator
     */
    public function validationBigBrainFlag(Validator $validator)
    {
        $validator->requirePresence(['is_bigbrain']);
        $validator->boolean('is_bigbrain');
        return $validator;
    }

    /**
     * Admin: aggiornamento flag can_logon da edit utente
     *
     * @param  Validator $validator
     * @return Validator
     */
    public function validationCanLogonFlag(Validator $validator)
    {
        $validator->requirePresence(['can_logon']);
        $validator->boolean('can_logon');
        return $validator;
    }

    /**
     * Verifica che l'account loggato abbia tutti i campi necessari (basilari)
     *
     * Creato perchè i social network non passano tutti i campi se si usa HybridAuth (username, email ad esempio)
     */
    public function validationRegistrationFields(Validator $validator)
    {
        $validator = $this->validationDefault($validator);

        //$validator->requirePresence(['username', 'email', 'password', 'password_confirm', 'type'], __('Campo obbligatorio'));
        $validator->notEmpty([
            'first_name', 'last_name', 'username', 'email', 'password', 'password_confirm', 'type'
        ], __('Campo obbligatorio'));

        $validator->add('type', 'isNonAdminAccount', [
            'message' => __('Tipologia account sconosciuta'),
            'rule'    => function($value, $context) {
                return in_array($value, ['user', 'company']);
            }
        ]);

        $validator->add('is_verified_mail', 'checkEmailVerification', [
            'message' => __('Devi prima verificare la tua email per poter accedere a funjob'),
            'rule'    => function($value, $context) {
                return (bool) $context['data']['is_verified_mail'] == true;
            }
        ]);

        $validator->add('password', [
            'minLength' => [
                'rule'    => ['minLength', 5],
                'message' => __('Caratteri minimi per password: 5')
            ]
        ]);

        $validator->add('password_confirm', [
            'compare' => [
                'rule'    => ['compareWith', 'password'],
                'message' => __('La password di conferma non è uguale alla password')
            ]
        ]);

        return $validator;
    }

    public function validationStoreRequirements(Validator $validator)
    {
        $validator->add('is_verified_mail', 'isVerifiedMail', [
            'message' => __('Devi verificare la tua e-mail {link}', ['link' => '<a href="#">da qui..</a>']),
            'rule'    => function($value, $context) {
                return (bool) $value === true;
            }
        ]);

        // Users
        $validator->notEmpty(
            'first_name',
            __('Nome richiesto (Pannello di controllo > Profilo > Nome & Cognome)'),
            function($context) {
                return in_array($context['data']['type'], ['admin', 'user']);
            }
        );
        $validator->notEmpty(
            'last_name',
            __('Cognome richiesto (Pannello di controllo > Profilo > Nome & Cognome)'),
            function($context) {
                return in_array($context['data']['type'], ['admin', 'user']);
            }
        );

        // COMPANIES
        $validator->notEmpty(
            'name',
            __('Nominativo aziendale richiesto (Pannello di controllo > Profilo > Nominativo)'),
            function($context) {
                return $context['data']['type'] == 'company';
            }
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
        $rules->add($rules->isUnique(['username'], __('Username giù utilizzato')));
        $rules->add($rules->isUnique(['email'], __('Indirizzo giù utilizzato')));

        return $rules;
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {

        // Converte in fase di registrazione "type" in "is_*"
        if (isset($data['type'])) {
            $data['is_company'] = $data['type'] == 'company';
            $data['is_user']    = $data['type'] == 'user';
        }
    }

    /* FINDs */
    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        $query
            ->select([
                'id',
                'username', 'password', 'type', 'email', 'country', 'lang', 'avatar',
                'first_name', 'last_name', 'name',

                'is_bigbrain', 'is_developer'
            ])
            ->where([
                'Users.can_logon'   => true,
                'Users.is_disabled' => false
            ]);

            if (Configure::read('Maintenance.enabled')) {
                $query->where(['type' => 'admin']);
            }

            return $query;
    }

    public function findIsActive(Query $q)
    {
        $q->where([
            'is_disabled' => false,
        ]);

        return $q;
    }

    /**
     * Restituisce solo gli utenti privati
     *
     * @param  Query  $q
     * @return Query
     */
    public function findIsPrivate(Query $q)
    {
        $q->where(['type IN' => ['user', 'admin']]);
        return $q;
    }

    /**
     * Restituisce solo le aziende
     *
     * @param  Query  $q
     * @return Query
     */
    public function findIsCompany(Query $q)
    {
        $q->where(['type' => 'company']);
        return $q;
    }


    /**
     * Restituisce solo utenti
     *
     * @param  \Cake\ORM\Query $query
     * @return \Cake\ORM\Query
     */
    public function findUser(\Cake\ORM\Query $query)
    {
        return $query
            ->where(['is_company' => false]);
    }


    public function findBigbrain(Query $q, $settings = [])
    {
        $q->find('isActive');
        $q->where(['is_bigbrain' => true]);

        return $q;
    }

    /**
     * Restituisce i bigbrain bloccati in home
     *
     * @param  Query  $q        [description]
     * @param  array  $settings [description]
     * @return [type]           [description]
     */
    public function findBigbrainHome(Query $q, $settings = [])
    {
        $q->find('Bigbrain');
        $q->where(['is_bigbrain_home' => true]);

        return $q;
    }


    public function findBySex(Query $q, $settings = [])
    {
        if (isset($settings['sex']) && in_array($settings['sex'], ['male', 'female'])) {
            $q->bind(':sex', $settings['sex']);
            $q->matching('AccountInfos', function($q) {
                return $q->where(['AccountInfos.sex = :sex']);
            });
        }

        return $q;
    }

    public function findByHobbies(Query $q, $settings = []) {
        if (!empty($settings['hobbies'])) {
            $q->bind(':hobbies', $settings['hobbies']);

            $q->matching('ProfileBlocks', function($q) {
                $q->where(['MATCH(ProfileBlocks.hobbies) AGAINST(:hobbies IN BOOLEAN MODE)']);
                return $q;
            });
        }

        return $q;
    }


    /************** COMPANY ****************/

    /**
     * Restituisce solo aziende
     *
     * @param  \Cake\ORM\Query $query
     * @return \Cake\ORM\Query
     */
    public function findCompany(\Cake\ORM\Query $query)
    {
        return $query
            ->where(['is_company' => true]);
    }


    /**
     * Ricerca company in base al nome
     * @param  Query  $q
     * @param  array  $settings
     *         string $settings['name']   Nome azienda
     *         bool   $settings['exact']  Se impostato a false la parola chiave viene trasformata in wildcard
     * @return Query
     */
    protected function findSearchCompanyByName(Query $q, $settings = [])
    {
        $_defaults = [
            'name'  => null,
            'exact' => true
        ];

        $settings = array_merge($_defaults, $settings);
        $this->requireSetting($settings, 'name');

        $term = $settings['name'];
        if (!$settings['exact']) {
            if (strpos($term, '*') === false) {
                $term = sprintf('*%s*', $term);
            }
        }

        $_matching = 'MATCH(username) AGAINST(:company_name IN BOOLEAN MODE)';
        $q->select(['_matching' => $_matching]);
        $q->where([$_matching]);
        $q->bind(':company_name', $term, 'string');
        $q->orderDesc('_matching');

        return $q;
    }



    protected function findCompanyCategories(Query $q, $settings = [])
    {
        //$q->contain(['CompanyCategories']);
        return $q;
    }


    protected function findWithAccountInfo(Query $q) {
        $q->contain(['AccountInfos']);
        $q->select($this->AccountInfos);
        return $q;
    }

}
