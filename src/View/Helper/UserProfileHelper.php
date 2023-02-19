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
        if (!$this->getView()->getRequest()->getSession()->check('Auth.User')) {
            return false;
        }

        // Usando $this->request->getParam('id') non funziona in /me
        return $this->getView()->getRequest()->getSession()->read('Auth.User.id') == $this->_View->viewVars['User']->id;
    }

}
