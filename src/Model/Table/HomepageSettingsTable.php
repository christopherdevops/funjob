<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HomepageSettings Model
 *
 * @method \App\Model\Entity\HomepageSetting get($primaryKey, $options = [])
 * @method \App\Model\Entity\HomepageSetting newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HomepageSetting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HomepageSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HomepageSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HomepageSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HomepageSetting findOrCreate($search, callable $callback = null, $options = [])
 */
class HomepageSettingsTable extends Table
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

        $this->setTable('homepage_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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

        $validator->allowEmpty(['foreground_video_embed', 'foreground_video2_embed']);

        $validator->add('foreground_video_href','isValidUrl', [
            'rule'    => ['url'],
            'message' => __('Richiede URL')
        ]);
        $validator->add('foreground_video2_href','isValidUrl', [
            'rule'    => ['url'],
            'message' => __('Richiede URL')
        ]);

        // Iframe validation
        $validator->provider('VideoEmbedValidation', '\Hiryu85\Model\Validation\VideoEmbedValidation');
        $validator->add('foreground_video_embed', 'isEmbedCode', [
            'rule'     => ['iframeExists'],
            'last'     => true,
            'message'  => __('Richiede un codice embed'),
            'provider' => 'VideoEmbedValidation'
        ]);
        $validator->add('foreground_video_embed', 'isWhiteListedHost', [
            'rule'     => ['iframeWhitelist', ['youtube.be', 'www.youtube.com']],
            'message'  => __('Questo host non è abilitato per l\'embed video'),
            'provider' => 'VideoEmbedValidation'
        ]);

        $validator->add('foreground_video2_embed', 'isEmbedCode', [
            'rule'     => ['iframeExists'],
            'last'     => true,
            'message'  => __('Richiede un codice embed'),
            'provider' => 'VideoEmbedValidation'
        ]);
        $validator->add('foreground_video2_embed', 'isWhiteListedHost', [
            'rule'     => ['iframeWhitelist', ['youtube.be', 'www.youtube.com']],
            'message'  => __('Questo host non è abilitato per l\'embed video'),
            'provider' => 'VideoEmbedValidation'
        ]);

        return $validator;
    }
}
