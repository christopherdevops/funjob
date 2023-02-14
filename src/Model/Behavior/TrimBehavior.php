<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;

use Cake\Event\Event;
use ArrayObject;

/**
 * Trim behavior
 */
class TrimBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        foreach ($this->_config as $field => $settings) {

            if (empty($data[$field]) || !is_string($data[$field])) {
                continue;
            }

            $data[$field] = trim($data[$field]);
        }
    }
}
