<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Utility\String;

/**
 * QuizCategoriesJsTree cell
 */
class QuizCategoriesJsTreeCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [
        'categoriesKey'
    ];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($baseSelector)
    {
        if (empty($baseSelector)) {
            throw new \RunTimeException('Cell: required paramenter: "baseSelector"');
        }
        $selector = $baseSelector;

        if ($this->request->getSession()->check('Config.language')) {
            $lang = $this->request->getSession()->read('Config.language');
        } else {
            $lang = Configure::read('app.defaultLanguage');
        }

        $this->loadModel('QuizCategories');
        $categories = Cache::remember(sprintf('quiz_categories_tree__%s', $lang), function() {
            $categories = $this->QuizCategories
                ->find('threaded')
                ->select(['id', 'name', 'parent_id'])
                // NOTE: Se si usa enableHydration=false e si itera tramite $Iterator non vengono impostate le nuove voci
                // $data['state'], difatti ogni nuova voce creata non compare in $categories (viene clonato?)
                //->enableHydration(false)
                ->bufferResults(false)
                ->toArray();

                // Scorre tutte le categorie per abilitare tramite opzione "state"
                // le categorie che contengono figli
                $Iterator = new \RecursiveIteratorIterator(
                    new \App\Lib\TreeIterator($categories),
                    \RecursiveIteratorIterator::SELF_FIRST
                );

                foreach ($Iterator as $i => $data) {
                    // Imposto state per jsTree:
                    // Se è un nodo che contiene figli non sarà selezionabile (disabilitato)
                    $data->state = ['opened' => false, 'disabled' => false, 'selectable' => true];
                    $data->text  = $data->name;

                    if (!empty($data->children)) {
                        $data->state['disabled']   = true;
                        $data->state['selectable'] = false;
                    }

                    //$Iterator->getInnerIterator()->offsetSet($i, $data);
                }

            return $categories;
        }, 'short');

        $this->set(compact('categories', 'selector'));
    }
}
