<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Core\Configure;

/**
 * Contact mailer.
 */
class ContactMailer extends Mailer
{

    /**
     * Mailer's name.
     *
     * @var string
     */
    static public $name = 'Contact';


    public function defaultMailer($formData)
    {
        $this->setProfile('default');
        $this->setTo($formData['to'], 'FunJob.it');

        //$this->setFrom($formData['from']);
        $this->setReplyTo($formData['from']);

        $this->setSubject(sprintf('[funjob.it] %s', $formData['subject']));

        $this->setEmailFormat('html')
            ->viewBuilder()
            ->setTemplate('Contacts/default')
            ->setVars(compact('formData'));
    }

}
