<?php
namespace App\View\Cell;

use Cake\View\Cell;

/**
 * GitHeadSha cell
 */
class GitHeadShaCell extends Cell
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
        $file = APP . DS . '..' .DS . '..' .DS. '.git' .DS. 'ORIG_HEAD';
        $sha  = null;

        if (file_exists($file)) {
            $sha = file_get_contents($file);
        }

        $this->set('version', $sha);
    }
}
