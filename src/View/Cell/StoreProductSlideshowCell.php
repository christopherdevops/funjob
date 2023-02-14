<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;

/**
 * StoreProductSlideshow cell
 */
class StoreProductSlideshowCell extends Cell
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
        $this->loadModel('StoreProducts');

        $products = Cache::remember('home_store_products', function() {
            return $this->StoreProducts
                ->find('slider')
                ->find('notDeleted')
                ->contain([
                    'Pictures' => function($q) {
                        $q->group(['product_id']);
                        return $q;
                    }
                ])
                ->limit(10)->all();
        }, 'xshort');

        $this->set(compact('products'));
    }
}
