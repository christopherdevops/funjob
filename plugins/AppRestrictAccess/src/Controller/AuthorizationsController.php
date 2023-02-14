<?php
namespace AppRestrictAccess\Controller;

use App\Controller\AppController as BaseController;
use AppRestrictAccess\Form\AuthorizationForm;

class AuthorizationsController extends BaseController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['authorize']);
    }


    public function authorize()
    {
        $Form = new AuthorizationForm();

        $this->viewBuilder()->setLayout('AppRestrictAccess.default');

        if (file_exists(APP .DS. 'Template' .DS. 'Layout' .DS. 'access-restriction.ctp')) {
            $this->viewBuilder()->setLayout('access-restriction');
        }

        if ($this->request->is('post')) {
            if (!$Form->validate($this->request->getData())) {
                $this->request->session()->write('AccessRestriction.authorized', true);
                return $this->redirect('/');
            }
        }

        $this->set(compact('Form'));
    }

}
