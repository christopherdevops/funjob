<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\View\StringTemplateTrait;

class UiHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'popover'     => '<a {{attrs}}>{{content}}</a>',
            'helpPopover' => '',
            'icon'        => '<i {{attrs}}></i>'
        ]
    ];

    public $helpers = ['Url'];

    /**
     * Icone tramite CSS (font awesome o fontello)
     *
     * @param  array  $attrs
     * @return str
     */
    public function icon($attrs = []) {
        $_defaults = [
            'aria-hidden' => 'true',
        ];

        $templater = $this->templater();
        $attrs     = $templater->formatAttributes(array_merge($_defaults, $attrs));

        return $templater->format('icon', compact('attrs'));
    }

    /**
     * Twitter boostrap popover
     *
     * @param  array  $settings
     * @return str
     */
    public function popover($settings = []) {
        $templater = $this->templater();

        $_defaults = [
            'text'    => $this->icon(['class' => 'fa fa-question-circle']),
            // Popover data
            'html'    => true,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
            'trigger' => 'hover click',
            'toggle'  => 'popover'
        ];

        $settings = array_merge($_defaults, $settings);
        $content  = $settings['text'];
        unset($settings['text']);

        $attrs        = $templater->formatAttributes($this->_popoverSettings($settings));
        $templateVars = [];

        return $templater->format('popover', compact('attrs', 'content', 'templateVars'));
    }

    /**
     * Twitter boostrap popover con icona di help
     *
     * @param  array  $settings
     * @return str
     */
    public function helpPopover($settings = []) {
        $_defaults = [
            'icon'    => 'fa fa-question-circle',
            'text'    => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit...',
            'trigger' => 'hover click'
        ];

        $settings = array_merge($_defaults, $settings);

        // Swap options
        // text è il contenuto del popover, ma sarebbe più corretto chiamarlo content
        $settings['content'] = $settings['text'];
        $settings['text']    = $this->icon(['class' => $settings['icon']]);

        if (isset($settings['class'])) {
            $settings['class'] .= ' ui-question-popover';
        } else {
            $settings['class'] = 'ui-question-popover';
        }

        unset($settings['icon']);

        return $this->popover($settings);
    }


    /**
     * Imposta attributi di Twitter bootstrap popover
     *
     * @param  array &$settings
     * @return &array
     */
    protected function _popoverSettings(&$settings) {
        foreach (['content', 'trigger', 'html', 'placement', 'toggle'] as $key) {
            if (empty($settings[$key])) {
                continue;
            }

            if (!empty($settings['data-' .$key])) {
                unset($settings[$key]);
                continue;
            }

            $settings['data-' . $key] = $settings[$key];
            unset($settings[$key]);
        }

        return $settings;
    }

}
