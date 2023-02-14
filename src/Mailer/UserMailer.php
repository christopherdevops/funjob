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

        $this->setTemplate('Users/confirm_account')
            ->setEmailFormat('html')
            ->viewVars(compact('User'));
    }


    public function resetToken(User $User)
    {
        $this->setProfile('default');
        $this->setTo($User->email);
        $this->setSubject(__('[funjob.it] ripristino account'));

        $this->setTemplate('Users/reset_account')
            ->setEmailFormat('html')
            ->viewVars(compact('User'));
    }
}
