<?php
namespace App\Mailer;

use App\Model\Entity\User;

use Cake\Mailer\Mailer;
use Cake\Core\Configure;

/**
 * User mailer.
 */
class UserMailer extends Mailer
{

    /**
     * Mailer's name.
     *
     * @var string
     */
    static public $name = 'User';


    /**
     * Email di verifica account
     *
     * @param  User   $User
     */
    public function emailConfirmation(User $User)
    {
        $this->setProfile('default');
        $this->setTo($User->email);
        $this->setSubject(__('[funjob.it] Verifica account'));

        $this->setEmailFormat('html')
            ->viewBuilder()
            ->setTemplate('Users/confirm_account')
            ->setVars(compact('User'));
    }


    public function resetToken(User $User)
    {
        $this->setProfile('default');
        $this->setTo($User->email);
        $this->setSubject(__('[funjob.it] ripristino account'));

        $this->setEmailFormat('html')
            ->viewBuilder()
            ->setTemplate('Users/reset_account')
            ->setVars(compact('User'));
    }
}
