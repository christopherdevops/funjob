<?php
namespace App\Controller\Component;

use Cake\ORM\TableRegistry;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Quiz component
 */
class AdvertisingComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    public function initialize(array $config = []): void
    {
        $this->Controller = $this->_registry->getController();
        $this->Auth = $this->Controller->Auth;

        $this->SponsorAdvs = TableRegistry::get('SponsorAdvs');
    }

    /**
     * Estra annunci pubblicitari
     *
     * @param  integer $limit Il numero di annunci da estrarre
     * @param  strin  $type   La tipologia di annunci da estrarre
     *
     * @return \Cake\ORM\ResultsSet Gli annunci che sono compatibili con l'utente attivo
     */
    public function get($limit = 1, $type = 'banner')
    {

        $query = $this->SponsorAdvs->find();
        $query->find('byType', compact('type'));

        if ($auth = $this->Auth->user()) {
            // Filtri per utenti privati
            // Campi: età, sesso
            if (in_array($auth['type'], ['user', 'admin'])) {
                // TODO: da sostituire con Sessione
                $this->Controller->loadModel('UserFields');
                $auth['user_fields'] = $this->Controller->UserFields->findByUserId($auth['id'])->first();

                // Età utente
                if (!empty($auth['user_fields']['birthday'])) {
                    // Richiede dati user_fields
                    $age = date('Y') - $auth['user_fields']['birthday']->format('Y');
                    $query->find('filterForUserAge', compact('age'));
                }

                // Sesso utente (solo per account privati)
                $query->find('filterForUserSex', ['sex' => $auth['user_fields']['sex']]);
            }

            if (empty($auth['country'])) {
                $auth['country'] = $this->Controller->request->getSession()->read('Config.language');
            }

            // Per lingua (filtro globale)
            $query->find('filterForCountry', ['country' => $auth['country']]);
        }

        $advs = $query
            ->find('isPublished')
            ->find('isActive')
        ->select(['id', 'title', 'descr', 'banner__img', 'banner__dir', 'href', 'uuid'])
        ->order(['RAND()'])
        ->limit($limit)
        ->all();

        return $advs;
    }
}
