<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Form\QuizQuestionReportingForm;

use Cake\Mailer\MailerAwareTrait;
use Cake\Core\Configure;

/**
 * QuizQuestionReportings Controller
 *
 * @property \App\Model\Table\QuizQuestionReportingsTable $QuizQuestionReportings
 *
 * @method \App\Model\Entity\QuizQuestionReporting[] paginate($object = null, array $settings = [])
 */
class QuizQuestionReportingsController extends AppController
{
    use MailerAwareTrait;

    public function initialize(): void
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
        $QuizUserReporting = new QuizQuestionReportingForm();

        if ($this->request->is('post')) {
            if ($QuizUserReporting->validate($this->request->getData())) {
                $this->loadModel('QuizQuestions');
                $this->loadModel('Users');

                $Question = $this->QuizQuestions->get($this->request->getData('question_id'), [
                    'contain' => [
                        'Quizzes',
                        'QuizAnswers' => function($q) {
                            return $q->where(['is_correct' => true]);
                        }
                    ]
                ]);

                $User    = $this->Users->findById($this->Auth->user('id'))->firstOrFail();
                $request = $this->request->getData();

                $this->getMailer('QuizQuestionReporting')->send('newReportingAdminNotification', [
                    $Question,
                    $User,
                    $request
                ]);

                $this->Flash->success(__('Un amministratore è stato avvisato della tua segnalazione. Grazie!'));
                return $this->redirect($this->referer('/'));
            }
        }

        return $this->redirect($this->referer('/'));
    }
}
