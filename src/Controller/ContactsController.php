<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Form\ContactForm;

use Cake\Mailer\MailerAwareTrait;
use Cake\Core\Configure;

/**
 * ContactForms Controller
 *
 * @property \App\Model\Table\ContactFormsTable $ContactForms
 *
 * @method \App\Model\Entity\ContactForm[] paginate($object = null, array $settings = [])
 */
class ContactsController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index']);

        $this->loadComponent('Recaptcha.Recaptcha', [
            'enable'  => true,    // true/false
            'type'    => 'image', // image/audio
            'theme'   => 'light', // light/dark

            'sitekey' => Configure::read('recaptca_keys.key'),
            'secret'  => Configure::read('recaptca_keys.secret'),
            'lang'    => Configure::read('lang')
        ]);
    }


    public function index()
    {
        $Form = new ContactForm();
        $this->set(compact('Form'));

        if ($this->request->is('post')) {
            if (!$this->Recaptcha->verify()) {
                $this->Flash->error(__('Verifica reCaptcha fallita'));
                $Form->setErrors(['g-recaptcha-response' => 'fuuuuu']);
                return null;
            }

            if (!$Form->validate($this->request->getData())) {
                $this->Flash->error(__('Controlla di aver compilato correttamente i campi e riprova'));
                return null;
            }

            switch($this->request->getData('_mailer')) {
                default:
                    $mailerClass = 'defaultMailer';
            }

            $formData         = [];
            $formData['IP']   = $this->request->clientIp();
            $formData['to']   = Configure::readOrFail(sprintf('funjob.contacts.%s.to', $this->request->getData('type')));
            $formData         = array_merge($formData, $this->request->getData());

            $sent = $this->getMailer('Contact')->send($mailerClass, [$formData]);

            if (!$sent) {
                $this->Flash->error(__('Impossibile inviare e-mail'));
                return null;
            }

            $this->Flash->success(__('E-mail inoltrata'));
            return $this->redirect($this->referer());
        }
    }
}
