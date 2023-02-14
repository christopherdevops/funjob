<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;

/**
 * StoreCompaniesLogos cell
 */
class StoreCompaniesLogosCell extends Cell
{

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
    public function display()
    {
        $merchants = Cache::remember('home_store_logos', function() {
            $logos     = glob(WWW_ROOT. 'img/gift-logos/*.png', GLOB_BRACE);
            $merchants = [];

            foreach ($logos as $logo) {
                $components = pathinfo($logo);

                $merchants[] = new \App\Model\Entity\StoreProductCompany([
                    'name'  => ucwords($components['filename']),
                    'image' => str_replace(WWW_ROOT, '', $logo)
                ]);
            }

            return $merchants;
        }, 'xshort');

        $this->set(compact('merchants'));
    }
}
