<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;

/**
 * UserFriends Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Friends
 *
 * @method \App\Model\Entity\UserFriend get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserFriend newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserFriend[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserFriend|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserFriend patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserFriend[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserFriend findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserFriendsTable extends Table
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

        $this->setTable('user_friends');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Requesters', [
            'className' => 'Users',
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Users', [
            'className'  => 'Users',
            'foreignKey' => 'friend_id'
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
            ->integer('user_id')
            ->integer('friend_id');

        $validator
            ->boolean('is_preferite')
            ->allowEmpty('is_preferite');

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
        $rules->add($rules->existsIn(['friend_id'], 'Users'));
        $rules->addCreate(
            $rules->isUnique(['user_id', 'friend_id'], __('Hai già inviato una richiesta di amicizia a questo utente')),
            'alreadySent',
            [
                'errorField' => 'friend_id',
            ]
        );

        return $rules;
    }


    /**
     * Verifica che un utente $settings['user_id'] è amico di $settings['friend_id']
     *
     * @param  Query  $q
     * @param  array  $settings
     *         user_id   => id utente 1
     *         friend_id => id utente 2
     *
     * @return \Cake\ORM\Query
     */
    protected function findIsFriendWith(Query $q, $settings = []) {
        if (!isset($settings['user_id']) && !isset($settings['friend_id'])) {
            throw new \RuntimeException(__('Parameter required: user_id => integer, friend_id => integer'));
        }

        $q->where(['friend_id' => $settings['user_id'], 'user_id' => $settings['friend_id']]);
        //$q->orWhere(['friend_id' => $settings['friend_id'], 'user_id' => $settings['user_id']]);

        return $q;
    }

    /**
     * Amicizie accettate
     *
     * @param  Query  $q
     * @return Query
     */
    public function findAccepted(Query $q) {
        return $q->where(['is_accepted' => true]);
    }

    /**
     * Amicizie da accettare
     *
     * @param  Query  $q
     * @return Query
     */
    public function findWaiting(Query $q, $settings = []) {
        $q->where(['is_accepted'  =>  false]);
        return $q;
    }

    /**
     * Mostra solo richieste per utente attivo
     *
     * @param  Query  $q        [description]
     * @param  array  $settings [description]
     * @return Query
     */
    public function findWaitingPending(Query $q, $settings = [])
    {
        $q->where([
            'is_requester' => false,
            'is_accepted'  => false,
        ]);
        return $q;
    }


    /**
     * Ricerca amici in base a:
     *
     * - Nome
     * - Cognome
     * - Ragione sociale
     * - Username
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    public function findSearchByTerm(Query $q, $settings = [])
    {
        if (!isset($settings['term'])) {
            throw new \RuntimeException(__('Parameter required: term => string'));
        }

        $q->bind(':searchTerm', '%'. $settings['term'] .'%');
        $q->matching('Users', function ($q) {
            $q->orWhere(['username LIKE :searchTerm']);
            $q->orWhere(['name LIKE :searchTerm']);
            $q->orWhere(['first_name LIKE :searchTerm']);
            $q->orWhere(['last_name LIKE :searchTerm']);
            return $q;
        });

        return $q;
    }

    public function findStarred(Query $q)
    {
        $q->where([ $this->getAlias() . '.is_preferite' => true]);
        return $q;
    }
}
