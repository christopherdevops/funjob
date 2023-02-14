<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Core\Configure;

/**
 * FakeUsers cell
 */
class FakeUsersCell extends Cell
{
    const MEN_URL        = 'https://randomuser.me/api/portraits/thumb/men/%d.jpg';
    const WOMEN_URL      = 'https://randomuser.me/api/portraits/thumb/women/%d.jpg';
    const PICTURES_INDEX = 2;

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($rows)
    {
        $points   = $this->_getRandomPoints($rows);
        $pictures = $this->_getRandomPictures($rows);
        $users    = array_replace_recursive($points, $pictures);

        $api_key = Configure::read('GoogleMap.api.key');
        $api_ver = Configure::read('GoogleMap.api.version');

        // $users  = json_decode(file_get_contents(
        //     'https://randomuser.me/api/?results=' .$rows. '&inc=picture,gender'
        // ), false);

        $this->set(compact('api_ver', 'api_key', 'users'));
    }

    /**
     * Restituisce delle foto randomiche tramite randomuser.me
     * Non effettua una richiesta
     *
     * @param  integer $limit
     * @return array
     */
    private function _getRandomPictures($limit = 10)
    {
        $pictures = [];
        $thumbs   = [];

        $pictures[0] = range(0,99);
        $pictures[1] = range(0,99);

        for ($i=0; $i < $limit; $i++) {
            $sex      = rand(0,1);
            $index    = array_rand($pictures[ $sex ]);
            $thumbs[] = [ self::PICTURES_INDEX => sprintf( $sex == 0 ? self::MEN_URL : self::WOMEN_URL , $pictures[$sex][$index]) ];

            unset($pictures[$sex][$index]);
        }

        return $thumbs;
    }

    /**
     * Restituisce punti randomici su mappa
     *
     * Utilizza la tabella map_points
     * @see https://www.random.org/geographic-coordinates/
     *
     * @param  integer $limit
     * @return array
     */
    private function _getRandomPoints($limit = 10)
    {
        $this->loadModel('MapPoints');

        $points   = [];
        $entities = $this->MapPoints->find()
            ->order('RAND()')
            ->limit($limit)
            ->hydrate(false)
            ->all();

        foreach ($entities as $point) {
            $points[] = [$point['lat'], $point['lng']];
        }

        return $points;
    }

}
