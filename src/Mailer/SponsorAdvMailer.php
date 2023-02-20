<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Core\Configure;

use App\Model\Entity\SponsorAdv;


/**
 * SponsorAdv mailer.
 */
class SponsorAdvMailer extends Mailer
{

    /**
     * Mailer's name.
     *
     * @var string
     */
    static public $name = 'SponsorAdv';


    public function newOrderAdminNotification(SponsorAdv $SponsorAdv)
    {
        $this->setProfile('default');
        $this->setTo(Configure::read('admin_email'), 'FunJob.it admin');
        $this->setSubject(
            __(
                '[funjob.it] Annuncio creato da {user} (N° {adv_id})',
                [
                    'user'   => $SponsorAdv->user->username,
                    'adv_id' => $SponsorAdv->id
                ]
            )
        );

        $this->setEmailFormat('html')
            ->viewBuilder()
            ->setLayout('admin')
            ->setTemplate('Advs/Admin/new_adv')
            ->setVars(compact('SponsorAdv'));
    }


    public function customerNotificationAfterPublish(SponsorAdv $SponsorAdv)
    {
        $this->setProfile('default');
        $this->setTo($SponsorAdv->user->email, $SponsorAdv->user->userame);
        $this->setSubject(
            __(
                '[funjob.it] Il tuo annuncio pubblicitario è stato pubblicato (N° {adv_id})',
                [
                    'adv_id' => $SponsorAdv->id
                ]
            )
        );

        $this->setEmailFormat('html')
            ->viewBuilder()
            ->setLayout('admin')
            ->setTemplate('Advs/Sponsor/adv_after_publication')
            ->setVars(compact('SponsorAdv'));
    }
}
