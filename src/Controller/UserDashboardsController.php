<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * UserDashboards Controller
 *
 * @property \App\Model\Table\UserDashboardsTable $UserDashboards
 *
 * @method \App\Model\Entity\UserDashboard[] paginate($object = null, array $settings = [])
 */
class UserDashboardsController extends AppController
{

    public $modelClass = null;

    public function initialize()
    {
        parent::initialize();
        $this->Auth->deny(['index']);
    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->loadModel('Users');
        $getData = [];

        if (in_array($this->Auth->user('type'), ['user', 'admin'])) {
            $getData['contain']['AccountInfos'] = [];
        }

        $User = $this->Users->get($this->Auth->user('id'), $getData);
        $CVrequests = TableRegistry::get('CvAuthorizations')->find('pending')->where(['user_id' => $User->id])->count();

        $this->set(compact('User', 'CVrequests'));
    }
}
