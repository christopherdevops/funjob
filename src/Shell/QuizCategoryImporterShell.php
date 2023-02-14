<?php
namespace App\Shell;

use Cake\Console\Shell;

/**
 * QuizCategoryUniversityImporter shell command.
 */
class QuizCategoryImporterShell extends Shell
{
    const UNIVERSITY_ROOT_NODEID = 1;
    const SPACER = ' / ';

    public function initialize()
    {
        parent::initialize();

        $this->_io->styles('exists', ['text' => 'green', 'blink' => true, 'bold' => true, 'reverse' => true, 'underline' => true]);
        $this->_io->styles('creating', ['text' => 'yellow', 'blink' => false, 'bold' => true, 'reverse' => true, 'underline' => true]);
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

        $parser->addSubcommand('universita', [
            'help'   => 'Importa categorie quiz (università)',
            'parser' => [
                'options'  => [
                    'file' => [
                        'required' => true,
                        'default'  => 'universita.csv'
                    ],
                    'skip' => [
                        'default' => false
                    ]
                ]
            ]
        ]);

        $parser->addSubcommand('liceo', [
        ]);

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
    }

    public function universita()
    {
        if (!file_exists($this->params['file'])) {
            $this->err(sprintf('File %s non esistente', $this->params['file']));
        }

        $treePaths = $this->_buildPaths();
        $csv       = array_map('str_getcsv', file($this->params['file']));
        $skip      = (int) $this->params['skip'];
        $created   = 0;

        foreach ($csv as $line => $ceils) {

            if ($skip > 0) {
                $skip--;
                $this->verbose(sprintf('Skip %d/%d', $line, $this->params['skip']));
                continue;
            }

            // Rimuove primo livello da $ceils (è sempre UNIVERSITÀ)
            // example: ['Ingegneria inf.', 'Programmazione', 'Basi dati']
            $categories = array_slice($ceils, 1);
            $categories = array_map('trim', $categories);

            // Rinomina indici $categories in modo che rispettino il numero del livello del tree
            $_categories = [];
            foreach ($categories as $index => $path) {
                $_categories[ $index + 1 ] = $path;
            }

            $categories = $_categories;
            unset($_categories);

            // Crea path da Array
            $categoryPath = implode(self::SPACER, $categories);
            $founded      = $this->_existsInTree($categoryPath);

            // Path (intero percorso) già presente nel tree
            if ($founded) {
                $this->verbose(sprintf('<info>Categoria esistente</info>: %s già esistente (#%d) ... salto', $categoryPath, $founded), true);
                continue;
            }

            // Determino quale sotto categoria non è presente nel path (e la creo)
            $chunks  = $this->_checkPath($categoryPath);
            $parent  = self::UNIVERSITY_ROOT_NODEID;
            $_exists = [];

            foreach ($chunks as $level => $matchingData) {
                $name   = $matchingData['name'];
                $exists = $matchingData['id'];

                if ($exists > 0) {
                    $this->verbose(sprintf('<exists>%s / </exists>', $name), false);

                    $_exists[] = $name;
                    $parent    = $exists;
                    continue;
                }

                $this->verbose(sprintf('<creating>%s / </creating>', $name), false);
                $entity    = $this->_createTreeNode(['name' => $name, 'parent_id' => $parent]);
                $parent    = $entity->id;

                $_exists[] = $entity->name;
                $created++;
            }
        }

        $this->out(sprintf('Inseriti: <success>%d</success>', $created));
    }

    /**
     * Genera una lista di breadcrumbs (paths)
     *
     * Restituisce un'array che ha come indice l'id categoria, e come valore il percorso della categoria
     *
     * [
     *     2 => 'Università / Informatica / Programmazione / Web / PHP',
     *     3 => 'Università / Informatica / Programmazione / Web / ASPX'
     * ]
     *
     * @return array
     */
    private function _buildPaths()
    {
        $paths = [];

        $this->loadModel('QuizCategories');

        //$treeList = $this->QuizCategories->find('treeList', ['spacer' => ''])->toArray();

        $tree = $this->QuizCategories
            ->find('children', ['for' => self::UNIVERSITY_ROOT_NODEID])
            ->find('threaded')
        ->hydrate(false)
        ->toArray();


        $Iterator = new \RecursiveIteratorIterator(
            new \App\Lib\TreeIterator($tree),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($Iterator as $category)
        {
            //$this->out(sprintf('%s %s (#%d) - level=%d', str_repeat("\t", $category['level']), $category['name'], $category['id'], $category['level']));
            $path = isset($paths[$category['parent_id']]) ? $paths[$category['parent_id']] . self::SPACER : '';
            $paths[ $category['id'] ] = $path . $category['name'];
        }

        return $paths;
    }

    /**
     * Restituisce l'id di ogni categoria
     *
     * Restituisce come indice il nome della categoria e come valore l'id della categoria (se trovata, altrimenti false)
     *
     * @param  str $path  Università/Ing.Informatica/Programmazione
     * @param  array $paths
     * @return array
     */
    private function _checkPath($path, $paths = null)
    {
        if ($paths === null) {
            $paths = $this->_buildPaths();
        }

        $pathChunks  = explode(self::SPACER, $path);
        $pathCurrent = [];
        $result      = [];

        foreach ($pathChunks as $i => $pathChunk) {
            $pathCurrent[] = $pathChunk;
            $result[$i]    = [
                'name'  => $pathChunk,
                'id'    => $this->_existsInTree( implode(self::SPACER, $pathCurrent) )
            ];
        }

        return $result;
    }

    /**
     * Verifica che $categoryPath sia presente nel database
     *
     * @param  str $categoryPath [description]
     * @param  array $paths
     * @return int ID categoria nel database
     */
    private function _existsInTree($categoryPath, $paths = null)
    {
        if ($paths === null) {
            $paths = $this->_buildPaths();
        }

        return array_search($categoryPath, $paths);
    }

    private function _createTreeNode($data)
    {
        $QuizCategory = $this->QuizCategories->newEntity($data);
        return $this->QuizCategories->saveOrFail($QuizCategory);
    }
}
