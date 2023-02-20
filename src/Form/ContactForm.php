<?php
namespace App\Form;

use Cake\Core\Configure;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * Contact Form.
 */
class ContactForm extends Form
{

    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): \Cake\Form\Schema
    {
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
        $validator->notEmpty(['type', 'from', 'body']);
        $validator->requirePresence(['type', 'from', 'body']);

        $validator->add('type', [
            'inConfig' => [
                'rule'    => ['inList', array_keys(Configure::read('funjob.contacts'))],
                'message' => __('Voce non valida')
            ]
        ]);

        $validator->add('from', [
            'isEmail' => [
                'rule'    => ['email'],
                'message' => __('Indirizzo non valido')
            ]
        ]);

        return $validator;
    }

    /**
     * Defines what to execute once the From is being processed
     *
     * @param array $data Form data.
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        return true;
    }



    public function setErrors($errors)
    {
        $this->_errors = $errors;
    }
}
