<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\View\StringTemplateTrait;

/**
 * Tree helper
 */
class TreeHelper extends Helper
{
    use StringTemplateTrait;

    public $helpers = ['Url', 'Form'];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'tree-ul-start'   => '<ul style="{{style}}" class="{{class}}">{{content}}',
            'tree-ul-end'     => '</ul>',

            'tree-li-start'   => '<li class="{{class}}">{{content}}',
            //'tree-li-substart' => '<li class="tree-has-child {{class}}">{{content}}',

            'tree-li-between' => "
                {{link_edit}}
                {{link_add}}
                {{link_delete}}
            ",

            'tree-li-end'     => '</li>',
            'tree-admin-link' => '<a {{attrs}}> <i class="{{icon}}"></i> {{content}} </a>'
        ]
    ];



    /**
     * Crea albero categoria con link alla stessa
     *
     * @param  array|Collection $array
     * @param  array  $options
     *         options.url = '\App\Helper\MyCommonTreeHelper::method'
     *         options.url = ['\App\Helper\MyCommonTreeHelper', 'method']
     *         options.url = function($entity) { return .... }
     * @return void
     */
    public function treeList($array, $options = []) {

        $_options = [
            'url'          => '\App\View\Helper\TreeHelper::entity_url',
            'onlyLastNode' => true
        ];
        $options = array_merge($_options, $options);

        if (empty($array)) {
            return;
        }

        echo $this->formatTemplate('tree-ul-start', [
            'class'        => 'clt',
            'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
        ]);

        foreach ($array as $treeEntity) {
            if (isset($options['url']) && is_callable($options['url'])) {
                $url = call_user_func_array($options['url'], [$treeEntity]);
            } else {
                $url = $this->__getUrl($treeEntity);
            }

            echo $this->formatTemplate('tree-li-start', [
                'class'        => 'clt',
                'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
            ]);

            if ($options['onlyLastNode']) {
                if (empty($treeEntity->children)) {
                    echo '<a href="'. $this->Url->build($url).'">'. $treeEntity->name .'</a>';
                } else {
                    echo '<span>'. $treeEntity->name .'</span>';
                    $this->treeList($treeEntity->children, $options);
                }
            } else {
                if (empty($treeEntity->children)) {
                    echo '<a href="'. $this->Url->build($url).'">'. $treeEntity->name .'</a>';
                } else {
                    echo '<a class="tree-has-child" href="'. $this->Url->build($url).'">'. $treeEntity->name .'</a>';
                    $this->treeList($treeEntity->children, $options);
                }
            }

            echo $this->formatTemplate('tree-li-end', [
                'class'        => 'clt',
                'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
            ]);
        }

        echo $this->formatTemplate('tree-ul-end', [
            'class'        => 'clt',
            'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
        ]);
    }

    /**
     * Crea albero categoria con link alla stessa
     *
     * @param  array|Collection $array
     * @param  array  $options
     *         options.url = '\App\Helper\MyCommonTreeHelper::method'
     *         options.url = ['\App\Helper\MyCommonTreeHelper', 'method']
     *         options.url = function($entity) { return .... }
     * @return void
     */
    public function treeListExpanded($array, $options = []) {

        $_options = [
            'url'          => '\App\View\Helper\TreeHelper::entity_url',
            'onlyLastNode' => true
        ];
        $options = array_merge($_options, $options);

        if (empty($array)) {
            return;
        }

        echo $this->formatTemplate('tree-ul-start', [
            'class'        => 'clt',
            'style'        => isset($options['style']) ? $options['style'] : '',
            'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
        ]);

        foreach ($array as $treeEntity) {
            $level = (int) $treeEntity['level'];

            if (isset($options['url']) && is_callable($options['url'])) {
                $url = call_user_func_array($options['url'], [$treeEntity]);
            } else {
                $url = $this->__getUrl($treeEntity);
            }

            echo $this->formatTemplate('tree-li-start', [
                'class'        => 'clt',
                'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
            ]);

            if ($options['onlyLastNode']) {
                if (empty($treeEntity->children)) {
                    echo '<a class="tree-link" data-id="' .$treeEntity->id. '" href="'. $this->Url->build($url).'">'. $treeEntity->name .'</a>';
                } else {
                    echo '<span class="tree-link">'. $treeEntity->name .'</span>';
                    $this->treeListExpanded($treeEntity->children, $options);
                }
            } else {
                if (empty($treeEntity->children)) {
                    echo '<a class="tree-link" href="'. $this->Url->build($url).'">'. $treeEntity->name .'</a>';
                } else {
                    $js = '$(this).next().next(\'ul\').toggle();return false;';
                    echo (
                        '<a data-id="' .$treeEntity->id. '" class="tree-toggle" onclick="'.$js.'" href="#"> <i class="fa fa-arrow-circle-o-down"></i> </a>  ' .
                        '<a data-id="' .$treeEntity->id. '" class="tree-link tree-has-child" href="'. $this->Url->build($url).'"> ' . $treeEntity->name .'</a>'
                    );

                    //if ($treeEntity->level > 0) {
                    $options['style'] = 'display:none;';
                    //}

                    $this->treeListExpanded($treeEntity->children, $options);
                }
            }

            echo $this->formatTemplate('tree-li-end', [
                'class'        => 'clt',
                'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
            ]);
        }

        echo $this->formatTemplate('tree-ul-end', [
            'class'        => 'clt',
            'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
        ]);
    }


    /**
     * Tree in formato lista con link admin
     *
     * @param  array $treeArray
     * @param  array  $options   [description]
     * @return void
     */
    public function treeListAdmin($treeArray, $options = []) {


        if (empty($treeArray)) {
            return;
        }

        echo $this->formatTemplate('tree-ul-start', [
            'class'        => isset($options['ul:class']) ? $options['ul:class'] : 'app-tree-list',
            'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
        ]);

        foreach ($treeArray as $treeEntity) {
            $entitySource = $treeEntity->source();
            $entityActions = [
                'edit'   => ['controller' => $entitySource, 'action' => 'edit', $treeEntity->id],
                'add'    => ['controller' => $entitySource, 'action' => 'add', '?' => ['parent_id' => $treeEntity->id]],
                'delete' => ['controller' => $entitySource, 'action' => 'delete', $treeEntity->id]
            ];

            echo $this->formatTemplate('tree-li-start', [
                'content'      => $treeEntity->name,
                'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
            ]);

            echo $this->formatTemplate('tree-li-between', [
                'url_edit'   => $this->Url->build($entityActions['edit']),
                'url_delete' => $this->Url->build($entityActions['delete']),
                'url_add'    => $this->Url->build($entityActions['add']),

                'link_delete'     => $this->Form->postLink(
                    '<i class="fa fa-remove"></i>',
                    $entityActions['delete'],
                    ['escape' => false, 'confirm' => __('Sei sicuro? Verranno cancellate anche le sotto categorie'), 'class' => 'text-danger font-size-xs']
                ),

                'link_edit'       => $this->_link(
                    '', // __('Edit'),
                    $entityActions['edit'],
                    ['class' => 'font-size-xs'],
                    ['icon' => 'fa fa-edit']
                ),

                'link_add'     => $this->_link(
                    '<i class="fa fa-plus"></i>',
                    $entityActions['add'],
                    ['escape' => false, 'class' => 'font-size-xs']
                ),

                'templateVars'    => isset($options['templateVars']) ? $options['templateVars'] : []
            ]);

            if (!empty($treeEntity->children)) {
                // Ricorsione childrens
                $this->treeListAdmin($treeEntity->children);
            } else {

            }

            echo $this->formatTemplate('tree-li-end', [
                'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
            ]);
        }

        echo $this->formatTemplate('tree-ul-end', [
            'class'        => 'clt',
            'templateVars' => isset($options['templateVars']) ? $options['templateVars'] : []
        ]);
    }


    private function _link($title = '', $url = [], $attrs = [], $options = []) {
        $templater = $this->templater();

        $attrs['href'] = is_array($url) ? $this->Url->build($url) : $url;

        return $this->formatTemplate('tree-admin-link', [
            'attrs'   => !empty($attrs) ? $templater->formatAttributes($attrs) : '',
            'icon'    => !empty($options['icon']) ? $options['icon'] : '',
            'content' => $title
        ]);
    }

    /**
     * Restituisce URL da entity
     *
     * Richiede action view
     *
     * @param  array $treeEntity
     * @return str
     */
    static public function entity_url($treeEntity)
    {
        $url = [
            'controller' => $treeEntity->source(),
            'action'     => 'view',
            'id'         => $treeEntity->id
        ];

        if ($treeEntity->has('slug')) {
            $url['slug'] = $treeEntity->get('slug');
        } elseif ($treeEntity->has('title')) {
            $url['slug'] = \Cake\Utility\Text::slug($treeEntity->get('title'), '-');
        } elseif ($treeEntity->has('name')) {
            $url['slug'] = \Cake\Utility\Text::slug($treeEntity->get('name'), '-');
        }

        return $url;
    }

    /**
     * Restituisce URL da entity
     *
     * Richiede action view
     *
     * @param  array $treeEntity
     * @return str
     */
    protected function __getUrl($treeEntity)
    {
        return self::entity_url($treeEntity);
    }
}
