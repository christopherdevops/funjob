<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

use Cake\Mailer\MailerAwareTrait;

/**
 * AdminOrderMessage Form.
 */
class AdminOrderMessageForm extends Form
{
    use MailerAwareTrait;

    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): \Cake\Form\Schema
    {
        $schema->addField('to', 'string')
            ->addField('subject', ['type' => 'string'])
            ->addField('body', ['type' => 'text']);

        return $schema;
    }

    /**
     * Form validation builder
     *
     * @param \Cake\Validation\Validator $validator to use against the form
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator->requirePresence(['to', 'body']);
        $validator->notEmpty(['to', 'subject', 'body']);
        $validator->email('to');

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
        $this->getMailer('Order')->send('orderUpdateNotification', [$data]);
        return true;
    }
}
