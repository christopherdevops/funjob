<?php
namespace App\Mailer;

use App\Model\Entity\QuizQuestion;
use App\Model\Entity\User;

use Cake\Mailer\Mailer;
use Cake\Core\Configure;

/**
 * QuizQuestionReporting mailer.
 */
class QuizQuestionReportingMailer extends Mailer
{

    /**
     * Mailer's name.
     *
     * @var string
     */
    static public $name = 'QuizQuestionReporting';

    public function newReportingAdminNotification(QuizQuestion $Question, User $User, $request)
    {
        $this->setProfile('default');
        $this->setTo(Configure::read('admin_email'), 'FunJob.it admin');
        $this->setSubject(
            __(
                '[funjob.it] Domanda segnalata da un utente (Quiz n° {quiz_id})',
                [
                    'quiz_id' => $Question->quiz->id,
                ]
            )
        );

        $this->setLayout('admin')
            ->setTemplate('QuizQuestions/Admin/new_reporting')
            ->setEmailFormat('html')
            ->viewVars(compact('Question', 'User', 'request'));
    }
}
