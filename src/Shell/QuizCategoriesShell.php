<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

/**
 * QuizCategories shell command.
 */
class QuizCategoriesShell extends Shell
{

    public function initialize() {
        $this->QuizCategories = TableRegistry::get('QuizCategories');
    }

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
    }


    public function list() {
        $tree = $this->QuizCategories->find('treeList');

        $this->out('Tree:');
        $this->hr();

        foreach ($tree as $id => $category) {
            $this->out(sprintf('[#%d] %s', $id, $category));
        }
    }

    public function recover() {
        $this->out('FIX Tree');
        $this->QuizCategories->recover();
    }

    public function addNode($name, $parent_id = null) {
        $QuizCategory = $this->QuizCategories->newEntity(compact('name', 'parent_id'));
        $created      = $this->QuizCategories->save($QuizCategory);

        if (!$created) {
            $this->abort('Impossibile creare nodo');
        }

        $this->out('<success>Nodo creato</success>');
        $this->list();
    }
}
