<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Form\QuizUserReportingForm;

use Cake\Mailer\MailerAwareTrait;
use Cake\Core\Configure;

/**
 * QuizUserReportings Controller
 *
 * @property \App\Model\Table\QuizUserReportingsTable $QuizUserReportings
 *
 * @method \App\Model\Entity\QuizUserReporting[] paginate($object = null, array $settings = [])
 */
class QuizUserReportingsController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->Auth->deny(['add']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->autoRender  = false;
        $QuizUserReporting = new QuizUserReportingForm();

        if ($this->request->is('post')) {
            if ($QuizUserReporting->validate($this->request->getData())) {
                $this->loadModel('Quizzes');
                $this->loadModel('Users');

                $Quiz    = $this->Quizzes->findById($this->request->getData('quiz_id'))->firstOrFail();
                $User    = $this->Users->findById($this->Auth->user('id'))->firstOrFail();
                $request = $this->request->getData();

                $this->getMailer('QuizUserReporting')->send('newReportingAdminNotification', [$Quiz, $User, $request]);
                $this->Flash->success(__('Un amministratore è stato avvisato della tua segnalazione. Grazie!'));
                return $this->redirect($this->referer('/'));
            }
        }

        return $this->redirect($this->referer('/'));
    }

}
