<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;
use BootstrapUI\View\UIViewTrait;
use Hiryu85\View\Helper\UploadWidget;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link http://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{
    use UIViewTrait;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
        // TwitterBootstrapUI plugin:
        // Don't forget to call the parent::initialize()
        parent::initialize();

        // App\View\Widget\UploadWidget
        $this->helpers = [
            'Form' => [
                'widgets' => [
                    'upload' => ['Hiryu85\View\Widget\UploadWidget', 'text', 'label', 'file']
                ],
                'className' => 'BootstrapUI.Form'
            ]
        ];

        // TwitterBootstrapUI
        $this->initializeUI(['layout' => false]);

        //$this->loadHelper('WyriHaximus/MinifyHtml.MinifyHtml');
    }
}
