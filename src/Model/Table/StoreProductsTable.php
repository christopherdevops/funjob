<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StoreProducts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\StoreProduct get($primaryKey, $options = [])
 * @method \App\Model\Entity\StoreProduct newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StoreProduct[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StoreProduct|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StoreProduct patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StoreProduct[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StoreProduct findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StoreProductsTable extends Table
{
    const AVAILABILITY_MIN_NOTIFY_FROM = 5;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('store_products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Creators', [
            'className'  => 'Users',
            'foreignKey' => 'user_id'
        ]);

        $this->belongsToMany('Categories', [
            'className' => 'StoreProductCategories',
            'joinTable' => 'store_categories_products',
            'onlyIds'   => true,

            'foreignKey' => 'product_id',
        ]);

        // Prodotto genitore
        $this->belongsTo('ParentProducts', [
            'className'  => 'StoreProducts',
            'foreignKey' => 'child_of',
            'bindingKey' => 'id'
        ]);
        // Prodotti figli
        $this->hasMany('SubProducts', [
            'className'  => 'StoreProducts',
            'foreignKey' => 'child_of',
            'bindingKey' => 'id'
        ]);

        $this->hasMany('Pictures', [
            'className'  => 'StoreProductPictures',
            'foreignKey' => 'product_id'
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
            ->requirePresence(['name', 'amount', 'qty']);

        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->numeric('amount');

        $validator
            ->integer('qty');

        $validator
            ->boolean('is_visible')
            ->allowEmpty('is_visible');

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
        $rules->add($rules->existsIn(['user_id'], 'Creators'));

        return $rules;
    }

    /**
     * Restituisce solo i prodotti visibili
     *
     * @param  \Cake\ORM\Query $q
     * @param  array $settings [description]
     * @return \Cake\ORM\Query
     */
    protected function findVisible(\Cake\ORM\Query $q, $settings = [])
    {
        $q->where(['is_visible' => true]);
        return $q;
    }

    protected function findProduct(\Cake\ORM\Query $q, $settings = [])
    {
        return $q->where(function($exp, $q) {
            return $exp->isNull('child_of');
        });
    }

    protected function findSubProducts(\Cake\ORM\Query $q, $settings = [])
    {
        if (empty($settings['product_id'])) {
            throw new \RuntimeException('StoreProduct::findSubProduct require product_id parameter');
        }

        return $q->where(['child_of' => (int) $settings['product_id']]);
    }

    /**
     * Restituisce i prodotti solo in una certa categoria
     *
     * @param  \Cake\ORM\Query $q
     * @param  array $settings [description]
     * @return \Cake\ORM\Query
     */
    protected function findArchive(\Cake\ORM\Query $q, $settings = [])
    {
        if (empty($settings['category_id'])) {
            throw new \RuntimeException('StoreProduct::findArchive require category_id parameter');
        }

        $ids = (array) $settings['category_id'];

        $q->matching('Categories', function($q) use ($ids) {
            $q->where(['Categories.id IN' => $ids]);
            return $q;
        });

        $q->group('StoreProducts.id');
        return $q;
    }


    /**
     * Restituisce solo prodotti nello slider in homepage dello store
     *
     * @param  Query  $q
     * @return Query
     */
    protected function findSlider(Query $q)
    {
        $q->where(['in_home_slider' => true]);
        $q->select(['id', 'name', 'descr', 'type']);
        return $q;
    }

    /**
     * Ricerca prodotto
     */
    protected function findSearch(Query $q, $settings)
    {
        if (empty($settings['term'])) {
            throw new \RuntimeException(__('Parametro richiesto: {field}', ['field' => 'term']));
        }

        $term = filter_var($settings['term'], FILTER_SANITIZE_STRING);
        if (strpos($settings['term'], '*') === false) {
            $term = '*' .$term. '*';
        }

        // NOTA:
        // La ricerca tramite "tshirt" non restituisce risultati, anche se il prodotto è chiamato "t-shirt".
        //
        // Nome prodott         : T-shirt fuffa
        // Rircerca             : tshirt
        // Risultato aspettato  : prodotto trovato
        // Risultato            : nessun prodotto trovato
        $term = str_replace('-', '*', $term);

        //$q->where(["MATCH(StoreProducts.name) AGAINST(:term IN NATURAL LANGUAGE MODE)"]);
        $q->where(["MATCH(StoreProducts.name) AGAINST(:term IN BOOLEAN MODE)"]);
        $q->bind(':term', $term);

        return $q;
    }

    protected function findByCategory(Query $q, $settings = [])
    {
        if (!isset($settings['category_id'])) {
            throw new \Exception();
        }

        $ids = $settings['category_id'];

        $q->matching('Categories', function($q) use ($ids) {
            $q->where(['Categories.id' => $ids], ['Categories.id' => 'integer[]']);
            return $q;
        });

        // Elimina risultati doppioni
        $q->group('StoreProducts.id');

        return $q;
    }

    /**
     * Restituisce prodototti/subprodotti non eliminati
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    public function findNotDeleted(Query $q, $settings = [])
    {
        $q->where(['is_deleted' => false]);
        return $q;
    }

    /**
     * Prodotti con disponibilità in scadenza
     *
     * @param  Query  $q
     * @param  array  $settings
     * @return Query
     */
    public function findMinimumAvailability(Query $q, $settings = [])
    {
        $q->where(function($exp, $q) {
            $exp->between($this->getAlias() . '.qty', 0, self::AVAILABILITY_MIN_NOTIFY_FROM);
            return $exp;
        });

        $q->orderAsc( $this->getAlias() . '.qty' );
        return $q;
    }


    public function findWithCategories(Query $q, $settings = [])
    {
        $q->contain(['Categories']);
        return $q;
    }

}
