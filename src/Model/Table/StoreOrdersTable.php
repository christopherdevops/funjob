<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use Cake\Validation\Validator;

// Event system
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use ArrayObject;
// Notifica
use Cake\Mailer\MailerAwareTrait;

/**
 * StoreOrders Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\StoreOrder get($primaryKey, $options = [])
 * @method \App\Model\Entity\StoreOrder newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StoreOrder[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StoreOrder|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StoreOrder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StoreOrder[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StoreOrder findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StoreOrdersTable extends Table
{
    use MailerAwareTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('store_orders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Products', [
            'className'  => 'StoreProducts',
            'foreignKey' => 'product_id'
        ]);
    }

    public function implementedEvents(): array
    {
        return [
            'Model.afterSave' => 'onNewOrderEvent'
        ];
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
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        // $validator->add('user_id', 'checkUserFields', [
        //     'message' => __('E\' necessario impostare i propri dati (nome cognome, indirizzo) per effettuare un ordine'),
        //     'rule'    => function($value, $context) {
        //         $User = TableRegistry::get('Users')->get($value, [
        //             'fields' => ['id', 'type', 'first_name', 'last_name', 'name']
        //         ]);

        //         if (in_array($User->type, ['admin', 'user'])) {
        //             $UserFields = TableRegistry::get('UserFields');
        //         } else {
        //             $UserFields = TableRegistry::get('CompanyFields');
        //         }


        //         $account = $UserFields->find()->where(['user_id' => $User->id])->firstOrFail();
        //         if (in_array($User, ['admin', 'user'])) {
        //             if (empty($User->first_name) || empty($User->last_name)) {
        //                 return $this->invalidate('user_id', __('E\' necessario specificare il proprio nome e cognome'));
        //                 return false;
        //             }

        //             if (empty($account->live_city) || empty($account->address)) {
        //                 return $this->invalidate('user_id', __('E\' necessario specificare il proprio indirizzo di residenza'));
        //                 return false;
        //             }
        //         } else {
        //             if (empty($User->name)) {
        //                 return false;
        //             }

        //             if (empty($account->city) || empty($account->address)) {
        //                 return false;
        //             }
        //         }

        //         return false;
        //     }
        // ]);

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
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }



    public function findByStatus(Query $query, $options = [])
    {
        if (!empty($options['state'])) {
            $q->where(['status' => $options['state']]);
        }

        return $q;
    }

    public function findCompleted(Query $query, $options = [])
    {
        $q->where(['status' => 'completed']);
        return $q;
    }

    public function findModeration(Query $query, $options = [])
    {
        $q->where(['status' => ['waiting', 'accepted', 'idle']]);

        return $q;
    }


    /**
     * Evento: creazione ordine
     *
     * @param  \Cake\Event\Event                $event
     * @param  \Cake\Datasource\EntityInterface $entity
     * @param  \ArrayObject                     $options
     * @return void
     */
    public function onNewOrderEvent(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $order = $this->get($entity->id, [
                'contain' => ['Users', 'Products']
            ]);

            $this->getMailer('Order')->send('adminNotify', [$order]);
        }
    }
}
