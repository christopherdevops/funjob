<?php
namespace App\Model\Table;

use App\Traits\UserTableTrait;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\Event\Event;
use Cake\Event\EventManager;

use ArrayObject;

use Hiryu85\Upload\File\Path\UserCvProcessor;
use Hiryu85\Traits\TableFindsTrait;

/**
 * Companies Model
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
class CompaniesTable extends Table
{
    use TableFindsTrait;
    use UserTableTrait;

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

        // Associazioni: globali
        $this->setGlobalAssociations();
        $this->setGlobalBehaviors();

        // Associazioni per company
        $this->hasOne('AccountInfos', [
            'foreignKey' => 'user_id',
            'className'  => '\App\Model\Table\CompanyFieldsTable',
        ]);

        $this->hasOne('ProfileBlocks', [
            'foreignKey' => 'user_id',
            'className'  => '\App\Model\Table\CompanyProfileBoxesTable',
        ]);

        $this->belongsToMany('Categories', [
            'className' => 'CompanyCategories',
            'through'   => 'CompanyCategoryThroughs'
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
            ->requirePresence('username', 'create')
            ->notEmpty('username', null, __('Campo obbligatorio'));

        $validator
            ->allowEmpty('fullname', null, __('Campo obbligatorio'));

        $validator
            ->allowEmpty('password', null, __('Campo obbligatorio'));

        $validator
            ->email('email', null, __('Valore non valido'))
            ->allowEmpty('email', null, __('Campo obbligatorio'));


        // UPLOADS
        $validator->allowEmpty(['avatar']);

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

    public function validationSettingsProfileCompany(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        return $validator;
    }

    public function findCompany(Query $q)
    {
        return $q->where(['is_company' => true]);
    }


    public function findIsActive(Query $q)
    {
        $q->where([
            'is_disabled' => false,
        ]);

        return $q;
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

        if (empty($settings['name'])) {
            throw new \Exception(
                __('{method} richiede parametro: {param}', ['method' => __METHOD__, 'param' => 'name'])
            );
        }

        $term = $settings['name'];
        if (!$settings['exact']) {
            if (strpos($term, '*') === false) {
                $term = sprintf('*%s*', $term);
            }
        }

        //$q->select($this);
        $q->where(['MATCH(username) AGAINST(:companyTerm IN BOOLEAN MODE)']);
        $q->bind(':companyTerm', $term, 'string');

        return $q;
    }


    protected function findCategories(Query $q)
    {
        $q->contain(['Categories']);
        return $q;
    }

    /**
     * Restituisce Company appartenenti a una specifica categoria
     *
     * @param  Query  $q
     * @param  array  $settings
     *         int[]|int category_ids
     * @return Query
     */
    protected function findFilterByCategory(Query $q, $settings = [])
    {
        if (empty($settings['category_ids'])) {
            throw new \Exception(__('{method} richiede parametro: {param}', [
                'method' => __METHOD__,
                'param'  => 'category_ids'
            ]));
        }

        $ids = $settings['category_ids'];
        if (!is_array($ids)) {
            $ids = (array) $settings['category_ids'];
        }

        $q->matching('Categories', function($q) use ($ids) {
            $q->where(['Categories.id IN' => $ids], ['id' => 'integer[]']);
            return $q;
        });

        return $q;
    }


    /**
     * Restituisce le company associate alle città specificate
     *
     * @param  Query  $q
     *
     * @param  array  $settings
     *         string|array $settings['city_id']
     *
     * @return Query
     */
    protected function findFilterByCity(Query $q, $settings = [])
    {
        $this->requireSetting($settings, 'city_id');
        if (!is_array($settings['city_id'])) {
            $ids = (array) $settings['city_id'];
        }

        $ids = implode(',', $settings['city_id']);
        $q->bind(':city_ids', $ids, 'string');

        $q->matching('AccountInfos', function($q) use ($ids) {
            $q->where(['AccountInfos.city_id IN (:city_ids)'], ['AccountInfos.city_id' => 'string']);
            return $q;
        });

        return $q;
    }

    protected function findWithAccountInfo(Query $q) {
        $q->contain(['AccountInfos']);
        //$q->select($this->AccountInfos);
        return $q;
    }

}
