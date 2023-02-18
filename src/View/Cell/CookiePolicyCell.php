<?php
namespace App\View\Cell;

use Cake\View\Cell;

/**
 * CookiePolicy cell
 */
class CookiePolicyCell extends Cell
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
        $is_accepted = $this->request->getCookie('cookie_policy_accept');
        $this->set(compact('is_accepted'));
    }
}
