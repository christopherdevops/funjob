<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Admin;

use Cake\Event\Event;
use Cake\I18n\I18n;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends \App\Controller\AppController
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Csrf');

        \Cake\I18n\I18n::locale('it_IT');
        \Cake\I18n\Time::setDefaultLocale('it_IT');       // For any mutable DateTime
        \Cake\I18n\FrozenTime::setDefaultLocale('it_IT'); // For any immutable DateTime
        \Cake\I18n\Date::setDefaultLocale('it_IT');       // For any mutable Date
        \Cake\I18n\FrozenDate::setDefaultLocale('it_IT'); // For any immutable Date
    }

    public function prepareAdvertising()
    {
        return false;
    }
}
