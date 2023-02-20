<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\I18n\I18n;

use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;

/**
 * Companies Controller
 *
 * @property \App\Model\Table\CompaniesTable $Companies
 */
class CitiesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        $referer = $this->referer();
        if (empty($referer)) {
            throw new ForbiddenException();
        }

        // Abilita ricerca città per utenti non registrati solo su
        // archivio aziende
        if (strpos($referer, '/companies')) {
            $this->Auth->allow(['search']);
        }
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->eventManager()->off($this->Csrf);
        $this->eventManager()->off($this->Security);
    }

    /**
     * Ricerca città
     */
    public function search()
    {
        return $this->_google();
    }

    private function _database()
    {
        $this->request->allowMethod(['POST', 'OPTIONS']);
        $this->autoRender = false;

        if (!$this->request->is('ajax')) {
            throw new \Cake\Network\Exception\ForbiddenException();
        }

        if (empty($this->request->getData('term'))) {
            throw new \Cake\Network\Exception\BadRequestException();
        }

        // $Cities = TableRegistry::get('Cities');
        // $Cities->removeBehavior('Translate');

        // $query  = $this->Cities->find();
        // $query->enableHydration(false);

        // $query->select(['name' => 'accent_city', 'value' => 'id']);
        // $query->where(['name LIKE' => '%' .$this->request->getData('term'). '%']);
        // $query->limit(10);

        // $results = $query->all()->toArray();


        $conn = ConnectionManager::get('cities');

        // LIKE (non sfrutta gli indici del database)
        // $stmt = $conn->execute(
        //     'SELECT accent_city AS name, id AS value, country_iso_code AS country FROM cities WHERE name LIKE :term LIMIT 10',
        //     ['term' => '%' .$this->request->getData('term'). '%']
        // );

        // FULLTEXT SEARCH
        $stmt = $conn->execute(
            'SELECT accent_city AS name, id AS value, country_iso_code AS country FROM cities WHERE MATCH(accent_city) AGAINST(:term IN BOOLEAN MODE) LIMIT 15',
            ['term' => $this->request->getData('term') . '*']
        );

        // Read all rows.
        $rows = $stmt->fetchAll('assoc');

        // Read rows through iteration.
        foreach ($rows as $row) {
            $results[] = $row;
        }

        $this->response->type('json');
        $this->response->body(json_encode(['results' => $results]));

        return $this->response;
    }

    private function _geonames()
    {
        $this->request->allowMethod(['POST', 'OPTIONS']);
        $this->autoRender = false;
        $this->response->type('json');

        $Client = new Client([
          'host'   => 'api.geonames.org',
          'scheme' => 'http',
          'auth'   => null,

          'timeout' => 5
        ]);

        $response = $Client->get('/search', [
            'username'        => 'papella',
            'name_startsWith' => $this->request->getData('term'),

            'featureClass'    => 'P',
            'featureCode'     => [
                // See http://www.geonames.org/export/codes.html
                'PPLS',
                'PPL',  // populated place: a city, town, village, or other agglomeration of buildings where people live and work
                'PPLC', // capital of a political entity
            ],

            'type'            => 'json',
            'style'           => 'FULL',
            'isNameRequired'  => true,
            'countryBias'     => 'IT',
            'maxRows'         => 10,
            'orderby'         => 'relevance'
        ]);

        if (!$response->isOk()) {
            throw new \Cake\Network\Exception\InternalErrorException();
        }

        if (empty($response->json['geonames'])) {
            $this->response->body(json_encode([]));
            return;
        }

        $results = [];

        foreach ($response->json['geonames'] as $record) {
            $results[] = [
                'name'        => sprintf(
                    '%s (%d, %s, %s)',
                    $record['asciiName'],
                    $record['countryCode'],
                    $record['adminCode1'],
                    !empty($record['adminCode2']) ? $record['adminCode2'] : ''
                ),
                'accent_city' => $record['toponymName']
            ] /*+ $record*/;
        }

        $this->response->body(json_encode(['results' => $results]));
        return $this->response;
    }

    /**
     * Autocomplete città attraverso Google Places API
     *
     * Limite giornaliero: 1,000 chiamate giornaliere, 150,000 (con carta di credito), >150,00 se si sforano le 150,00
     *
     * @return [type] [description]
     */
    private function _google()
    {
        $this->request->allowMethod(['POST', 'OPTIONS']);
        $this->autoRender = false;
        $this->response->type('json');

        $Client = new Client([
          'host'    => 'maps.googleapis.com',
          'scheme'  => 'https',
          'auth'    => null,
          'timeout' => 5,

          'headers'  => [
              'Referer' => Router::reverse(Router::getRequest(), true)
          ]
        ]);

        $response = $Client->get('/maps/api/place/autocomplete/json', [
            'input'    => $this->request->getData('term'),
            'types'    => '(cities)',
            'key'      => Configure::read('GoogleMap.api.key'),
            'language' => I18n::getLocale()
        ]);

        if (!$response->isOk()) {
            throw new \Cake\Network\Exception\InternalErrorException('HTTP exception');
        }

        if ($response->json['status'] != 'OK') {
            throw new \Cake\Network\Exception\InternalErrorException(__('API status [{0}]', $response->json['error_message']));
            return;
        }

        if (empty($response->json['predictions'])) {
            $this->response->body(json_encode([]));
            return;
        }

        $results = [];

        foreach ($response->json['predictions'] as $record) {
            $results[] = [
                'name'        => $record['description'],
                'accent_city' => $record['description'],
                'value'       => $record['place_id'],
                'country'     => $record['structured_formatting']['secondary_text']
            ];
        }

        $this->response->body(json_encode(compact('results')));
        return $this->response;
    }
}
