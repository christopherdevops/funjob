<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Core\Configure;

use App\Model\Entity\Quiz;
use App\Model\Entity\User;

/**
 * QuizUserReporting mailer.
 */
class QuizUserReportingMailer extends Mailer
{

    /**
     * Mailer's name.
     *
     * @var string
     */
    static public $name = 'QuizUserReporting';


    public function newReportingAdminNotification(Quiz $Quiz, User $User, $request)
    {
        $this->setProfile('default');
        $this->setTo(Configure::read('admin_email'), 'FunJob.it admin');
        $this->setSubject(
            __(
                '[funjob.it] Quiz reportato da un utente (Quiz n° {quiz_id})',
                [
                    'quiz_id' => $Quiz->id,
                ]
            )
        );

        $this->setLayout('admin')
            ->setTemplate('Quizzes/Admin/new_reporting')
            ->setEmailFormat('html')
            ->viewVars(compact('Quiz', 'User', 'request'));
    }
}
