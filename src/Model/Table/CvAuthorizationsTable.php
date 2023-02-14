<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CvAccess Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $CvUsers
 *
 * @method \App\Model\Entity\CvAuthorizations get($primaryKey, $options = [])
 * @method \App\Model\Entity\CvAuthorizations newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CvAuthorizations[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CvAuthorizations|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CvAuthorizations patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CvAuthorizations[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CvAuthorizations findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CvAuthorizationsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cv_authorizations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Recipients', [
            'className'  => 'Users',
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Requesters', [
            'className'  => 'Users',
            'foreignKey' => 'requester_user_id'
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
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator->requirePresence(['user_id', 'requester_user_id'], 'create');
        $validator->allowEmpty('reason');

        $validator
            ->allowEmpty('allowed');

        return $validator;
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
        $rules->add($rules->existsIn(['user_id'], 'Recipients'));
        $rules->add($rules->existsIn(['requester_user_id'], 'Requesters'));

        // Verifica che il requster_user_id non abbia già inviato la richiesta
        // Viene effettuato un controllo su CvAuthorizationsController::add sul messaggio di validazione di seguito
        $rules->add($rules->isUnique(['user_id', 'requester_user_id'], __('Richiesta in attesa di moderazione')), 'alreadySent');

        return $rules;
    }

    /**
     * Restituisce tutte le entitiy per user_id effettuate da sender
     *
     * @param  \Cake\ORM\Query $q
     * @param  array $settings
     * @return \Cake\ORM\Query
     */
    public function findByRequester(Query $q, $settings)
    {
        if (empty($settings['requester'])) {
            throw new \RuntimeException();
        }

        if (empty($settings['user_id'])) {
            throw new \RuntimeException();
        }


        $q->where([
            'requester_user_id' => $settings['requester'],
            'user_id'           => $settings['user_id']
        ]);

        return $q;
    }

    public function findArchive(Query $q, $settings = [])
    {

        if (empty($settings['user_id'])) {
            throw new \Exception();
        }

        $q->where([
            'user_id' => (int) $settings['user_id']
        ]);

        $q->contain([
            'Recipients' => function($q) {
                $q->select(['id', 'username', 'avatar']);
                return $q;
            },

            'Requesters' => function($q) {
                $q->select(['id', 'username', 'avatar']);
                return $q;
            }
        ]);

        return $q;
    }

    /**
     * Restituisce solo le righe che devono essere moderate dall'utente
     * @param  Query  $q
     * @return Query
     */
    public function findPending(Query $q)
    {
        return $q->where(['allowed IS NULL']);
    }

    /**
     * Restituisce solo le righe che sono autorizzati
     * @param  Query  $q
     * @return Query
     */
    public function findAllowed(Query $q)
    {
        return $q->where(['allowed' => true]);
    }

    /**
     * Restituisce solo le righe che non sono autorizzati
     * @param  Query  $q
     * @return Query
     */
    public function findDenied(Query $q)
    {
        return $q->where(['allowed' => false]);
    }

}
