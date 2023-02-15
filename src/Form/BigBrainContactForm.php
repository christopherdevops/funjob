<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Core\Configure;

/**
 * BigBrainContact Form.
 */
class BigBrainContactForm extends Form
{
    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): \Cake\Form\Schema
    {
        $schema->addField('fullname', 'string')
            ->addField('email', ['type' => 'string'])
            ->addField('descr', ['type' => 'text'])
            ->addField('ip', ['type' => 'string'])
            ->addField('username', ['type' => 'string']);


        return $schema;
    }

    /**
     * Form validation builder
     *
     * @param \Cake\Validation\Validator $validator to use against the form
     * @return \Cake\Validation\Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator->requirePresence(['fullname', 'email', 'descr', 'ip']);
        $validator->notEmpty(['fullname', 'email', 'descr', 'ip']);

        $validator->email('email', false, __('Non è un indirizzo email valido'));
        $validator->minLength('descr', 100, __('Richiede minimo 100 caratteri'));

        return $validator;
    }

    /**
     * Defines what to execute once the From is being processed
     *
     * @param array $data Form data.
     * @return bool
     */
    protected function _execute(array $data)
    {
        $text = [];
        $text[0] = $data['descr'];
        $text[1] = str_repeat('-', 80);
        $text[2] = 'Profilo  = ' . Router::url([
            '_name'    => 'user:profile',
            'id'       => $data['user_id'],
            'username' => $data['user_name']
        ], true);
        $text[3] = 'IP       = ' . $data['ip'];

        $email = new Email();
        $email
            ->replyTo($data['email'])
            ->from($data['email'], $data['fullname'])

            ->to(Configure::read('admin_email'), 'FunJob.it')
            ->addBcc(Configure::read('developer_email'), 'FunJob.it develper')
            ->subject(__('BigBrain: nuovo candidato {0}', $data['fullname']))
            ->emailFormat('both')
            ->send(nl2br( implode("\n", $text) ));

        return true;
    }
}
