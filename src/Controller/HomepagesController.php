<?php
namespace App\Controller;

use Cake\Core\Configure;

use App\Controller\AppController;

use Cake\Cache\Cache;
use Cake\i18n\i18n;

/**
 * Homepages Controller
 */
class HomepagesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->Auth->allow();

        $this->loadComponent('Paginator');

        $this->loadModel('Quizzes');
        $this->loadModel('QuizCategories');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function home()
    {
        $key = 'home_popular_categories__'. i18n::locale();
        $categories = Cache::remember($key, function() {
            return $this->QuizCategories
                ->find('primary')
                ->limit(8)
                ->order(['RAND()'])
                ->all();
        }, 'home_popular_categories');

        $this->set(compact('categories'));
        $this->render();
    }


    public function maintenance()
    {
        $this->viewBuilder()->setLayout('frontend-maintenance');

        $maintenanceMessage = Configure::read('Maintenance.message');
        $this->set(compact('maintenanceMessage'));
    }

}
