<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Mailer\MailerAwareTrait;
use Cake\Utility\Text;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;

/**
 * UserRegistrations Controller
 *
 * @property \App\Model\Table\UserRegistrationsTable $UserRegistrations
 *
 * @method \App\Model\Entity\UserRegistration[] paginate($object = null, array $settings = [])
 */
class UserRegistrationsController extends AppController
{

    use MailerAwareTrait;

    public function initialize(): void
    {
        parent::initialize();
        $this->Auth->deny();

        $this->Auth->allow(['confirmation', 'recovery', 'reset']);
    }

    /**
     * Mostra all'utente i campi obbligatori e permette di impostarli
     */
    public function requirements()
    {
        $this->loadModel('Users');

        $User = $UserSrc = $this->Users->get($this->Auth->user('id'));

        if ($this->request->is('put')) {
            $User = $this->Users->patchEntity($User, $this->request->getData(), [
                'validate'  => 'registrationFields'
            ]);

            if ($this->Users->save($User)) {
                $this->request->getSession()->write('Auth.User._skipAccountRequirementsSkip', true);

                // Aggiornamento sessione con i nuovi dati
                $this->Auth->setUser($User->toArray());

                $this->Flash->success(__('Grazie per aver aggiornato i tuoi dati'));
                return $this->redirect(['_name' => 'me:dashboard']);
            }
        }

        $this->set(compact('UserSrc', 'User'));
    }


    /**
     * Permette di ripristinare la password
     */
    public function recovery() {
        $this->loadModel('Users');

        $User = $this->Users->newEntity();

        if ($this->request->is('post')) {
            $User = $this->Users->findByEmail($this->request->getData('email'))->first();
            if (!$User) {
                $this->Flash->error(__('Nessun account è associato a questa email'));
                return null;
            }

            $User->recovery_token = Text::uuid();

            if ($this->Users->save($User, ['fieldList' => 'recovery_token'])) {
                $this->getMailer('User')->send('resetToken', [$User]);
                $this->Flash->success(__('Ti è stata inviata un email per il ripristino'));
                return $this->redirect($this->referer());
            }
        }

        $this->set(compact('User'));
    }

    public function reset($uuid)
    {
        $this->loadModel('Users');

        $User = $this->Users->find('byResetToken', ['token' => $uuid])->select(['id', 'username'])->first();
        if (!$User) {
            $this->Flash->error(__('Codice di reset non valido'));
            $this->response->withStatus(404);
            return $this->redirect($this->referer());
        }

        if ($this->request->is('put')) {
            $User->recovery_token = null;
            $User = $this->Users->patchEntity($User, $this->request->getData(), [
                'fieldList' => ['password', 'recovery_token'],
                'validate'  => 'accountReset'
            ]);

            if ($this->Users->save($User)) {
                $this->Flash->success(__('Esegui l\'accesso con i tuoi nuovi dati'));
                return $this->redirect(['_name' => 'auth:login']);
            }
        }

        $this->set(compact('User'));
    }

    /**
     * Permette di confermare l'account utilizzando il link fornito nell'email
     *
     * @param  str $uuid
     */
    public function confirmation($uuid)
    {
        $this->request->allowMethod('GET');
        $this->loadModel('Users');

        $User = $this->Users->find('byConfirmationToken', ['token' => $uuid])->firstOrFail();
        $User = $this->Users->patchEntity($User, ['is_verified_mail' => true, 'can_logon' => true, 'confirmation_token' => null]);

        if ($this->Users->save($User)) {

            // Potrebbe essere aperto tramite un browser differente, ma questo dovrebbe permettere
            // all'utente di proseguire con /requirements per poter impostare le password.
            if (!$this->Auth->user()) {
                //$xx = $this->Auth->identify(['username' => $User->username, 'password' => $User->password]);
                $this->Auth->setUser($User->toArray());
            }

            $this->Flash->success(__('Grazie per aver completato la registrazione'));
            return $this->redirect(['_name' => 'me:dashboard']);
        }

        return $this->redirect($this->referer('/'));
    }

    /**
     * Invia nuovamente e-mail con codice di conferma email
     */
    public function confirmationResend()
    {
        $this->request->allowMethod('POST');
        $this->loadModel('Users');

        $User = $this->Users->get((int) $this->request->getData('id'), ['fields' => ['id', 'username', 'email', 'confirmation_token']]);
        if ($User->is_verified_mail || empty($User->confirmation_token)) {
            $this->Flash->success(__('Account già verificato, Grazie'));
            return $this->redirect($this->referer());
        }

        $this->getMailer('User')->send('emailConfirmation', [$User]);
        $this->Flash->success(__('E-mail di conferma inviata: controlla anche nella tua spambox'));

        return $this->redirect($this->referer());
    }

}
