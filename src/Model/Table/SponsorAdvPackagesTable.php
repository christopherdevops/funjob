<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SponsorAdvPackages Model
 *
 * @method \App\Model\Entity\SponsorAdvPackage get($primaryKey, $options = [])
 * @method \App\Model\Entity\SponsorAdvPackage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SponsorAdvPackage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdvPackage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SponsorAdvPackage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdvPackage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SponsorAdvPackage findOrCreate($search, callable $callback = null, $options = [])
 */
class SponsorAdvPackagesTable extends Table
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

        $this->setTable('sponsor_adv_packages');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->allowEmpty('type');

        $validator
            ->integer('impressions');

        $validator
            ->numeric('tax_paypal');

        $validator
            ->numeric('tax_funjob')
            ->allowEmpty('tax_funjob');

        $validator
            ->integer('tax_iva');

        $validator
            ->integer('price');

        return $validator;
    }


    public function findSelectOptions(Query $q, $settings = [])
    {
        $q->select([]);

        $mapper = function ($entity, $key, $mapReduce) {
            if ($entity->type == 'banner') {
                $status = __('Banner in pagina');
            } else {
                $status = __('Banner nei quiz (Maximum Memories)');
            }
            $mapReduce->emitIntermediate($entity, $status);
        };
        $reducer = function ($entities, $status, $mapReduce) {
            $options = [];

            foreach ($entities as $Package) {
                $options[ $Package->id ] = __('{views} visualizzazioni = {amount} euro', [
                    'views'  => (int) $Package->impressions,
                    'amount' => $Package->amount
                ]);
            }

            $mapReduce->emit($options, $status);
        };

        $q->mapReduce($mapper, $reducer);

        return $q;
    }
}
