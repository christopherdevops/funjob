<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;
use Cake\Routing\Router;
use Cake\Utility\Text;
use Cake\Core\Configure;

/**
 * StoreProductCategories cell
 */
class StoreProductCategoriesCell extends Cell
{
    public $helpers = ['Tree'];

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
        $this->loadModel('StoreProductCategories');
        $categories = $this->__getCategories();
        $treeList   = $categories[0];
        $treeSelect = $categories[1];

        $opened = [];

        // deve prendere la categoria corrente in base al contesto
        //
        // archivio = categoria selezionata
        // home     = niente
        // ricerca  = niente
        // if ($this->request->getParam('action') == 'archive') {
        //     $pass = $this->request->getParam('pass');
        //     $cat  = (int) $pass[0];

        //     $opened = [$cat];
        // }

        $this->set(compact('treeList', 'treeSelect', 'opened'));
    }



    /**
     * Restituisce le categorie dello sto∂∂re
     *
     * Utilizza cache files
     *
     * @return
     */
    protected function __getCategories()
    {
        $lang = Configure::read('app.defaultLanguage');
        if ($this->request->getSession()->check('Config.language')) {
            $lang = $this->request->getSession()->read('Config.language');
        }

        $tree = Cache::remember('store_categories_tree__' . $lang, function() {
            return $this->StoreProductCategories->find('threaded')->all()->toArray();
        }, 'long');
        $list = Cache::remember('store_categories_list__' . $lang, function() {
            return $this->StoreProductCategories->find('treeList', ['spacer' => '* '])
                // "Nome categoria" => "url"
                ->combine(
                    function($name, $id) {
                        return Router::url([
                            '_name' => 'store:archive',
                            'id'    => $id,
                            'slug'  => Text::slug($name, '-')
                        ]);
                    },
                    function($name, $id) {
                        return $name;
                    })
            ->toArray();
        }, 'long');

        return [$tree, $list];
    }
}
