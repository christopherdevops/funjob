<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\Behavior\Translate\TranslateTrait;

use Cake\Validation\Validator;

/**
 * JobCategories Model
 *
 * @property \Cake\ORM\Association\HasMany $UserJobOffers
 *
 * @method \App\Model\Entity\JobCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\JobCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\JobCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JobCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JobCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JobCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\JobCategory findOrCreate($search, callable $callback = null)
 */
class JobCategoriesTable extends Table
{
    use TranslateTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('job_categories');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsToMany('Users', []);


        $this->addBehavior('Translate', ['fields' => ['name']]);
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

        $validator
            ->allowEmpty('name');

        return $validator;
    }
}
