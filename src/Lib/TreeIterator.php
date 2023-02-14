<?php
namespace App\Lib;

/**
 * Tree Iterator
 *
 * @usage
 * $tree = new RecursiveIteratorIterator(new TreeIterator($treeArray), RecursiveIteratorIterator::SELF_FIRST);
 */
class TreeIterator extends \ArrayIterator implements \RecursiveIterator {

    private function _getChildrenKey() {
        $current = $this->current();
        $key     = false;

        if (isset($current['children'])) {
            return 'children';
        } elseif (isset($current['nodes'])) {
            return 'nodes';
        }

        throw new \Exception();
    }

    // Get a recursive iterator over the children of this item.
    public function getChildren() {
        $current = $this->current();
        return new TreeIterator((array) $current[ $this->_getChildrenKey() ]);
    }

    // Does this item have children?
    public function hasChildren() {
        $current = $this->current();
        return !empty((array) $current[ $this->_getChildrenKey() ]);
    }
}
