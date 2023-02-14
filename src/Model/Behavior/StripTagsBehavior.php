<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;

use Cake\Event\Event;
use ArrayObject;

/**
 * StripTags behavior
 *
 * {{{
 *     $this->addBehavior('StripTags', [
 *         'my_field' => [
 *             'allowable_tags' => ['a', 'abbr']
 *         ],
 *     ])
 * }}}
 */
class StripTagsBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
    ];


    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        foreach ($this->_config as $field => $settings) {

            if (empty($data[$field]) || !is_string($data[$field])) {
                continue;
            }

            if (empty($settings['allowable_tags'])) {
                $settings['allowable_tags'] = [];
            }

            if (!is_array($settings['allowable_tags'])) {
                trigger_error(__('{0} allowable_tags should be array', __METHOD__));
                continue;
            }

            $tags = [];

            foreach ($settings['allowable_tags'] as $tag) {
                $tags[] = '<' .$tag. '>';
            }

            $data[$field] = strip_tags($data[$field], implode('', $tags));
        }
    }

}
