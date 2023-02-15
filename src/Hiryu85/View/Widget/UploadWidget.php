<?php
namespace Hiryu85\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\WidgetInterface;

/**
 * UploadWidget
 * Show upload input + avatar preview
 *
 * Require templates:
 *
 * {{{
 *     $this->Form->setTemplates([
 *       'uploadWidgetContainer' => '<div class="row">{{content}}</div>',
 *       'uploadWidgetPreview'   => '<div class="col-md-4">{{content}}</div>',
 *       'uploadWidgetInput'     => '<div class="col-md-6">{{input}} <label{{attrs}}>{{text}}</label></div>'
 *     ]);
 * }}}
 */
class UploadWidget implements WidgetInterface
{

    protected $_templates;

    public function __construct($templates)
    {
        $this->_templates = $templates;
    }

    /**
     * Renderizza widget avatar
     *
     * Richiede che nell'entity ci sia una proprietà chiamata "avatarSrc"
     *
     * @param  array            $data    [description]
     * @param  ContextInterface $context [description]
     * @return [type]                    [description]
     */
    public function render(array $data, ContextInterface $context): string
    {
        $data += [
            'name' => '',
        ];

        // Campo input/entity $data['name'];
        $field    = $data['name'];
        $fieldSrc = $field . 'Src';

        if (empty($context->entity()->{$fieldSrc})) {
            throw new \RuntimeException(__('Require entity property {0}', $fieldSrc));
        }

        $outPreview = $this->_templates->format('uploadWidgetPreview', [
            'src' => $context->entity()->{$fieldSrc}
        ]);

        $outInput = $this->_templates->format('uploadWidgetInput', [
            'input' => $this->_templates->format('input', [
                'name'  => $data['name'],
                'type'  => 'file',
                'attrs' => $this->_templates->formatAttributes($data, ['name'])
            ])
        ]);

        return $this->_templates->format('uploadWidgetContainer', [
            'content' => $outPreview . $outInput
        ]);
    }

    public function secureFields(array $data): array
    {
        return [$data['name']];
    }
}
