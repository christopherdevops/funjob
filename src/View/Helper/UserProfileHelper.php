<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * UserProfile helper
 */
class UserProfileHelper extends Helper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    /**
     * Verifica che il profilo visualizzato sia il proprio
     *
     * @return bool
     */
    public function isMyProfile() {
        if (!$this->request->session()->check('Auth.User')) {
            return false;
        }

        // Usando $this->request->params['id'] non funziona in /me
        return $this->request->session()->read('Auth.User.id') == $this->_View->viewVars['User']->id;
    }

}
