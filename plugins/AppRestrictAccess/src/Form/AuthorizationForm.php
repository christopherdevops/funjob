<?php
namespace AppRestrictAccess\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

use Cake\Core\Configure;

/**
 * AuthorizationForm Form.
 */
class AuthorizationForm extends Form
{
    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema)
    {
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
        $validator->requirePresence(['pwd']);
        $validator->notEmpty(['pwd']);

        $validator->add('pwd', [
            'equalTo' => [
                'rule'    => ['equalTo', Configure::read('RestrictionAccess.pwd')],
                'message' => __('Password non valida')
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
    protected function _execute(array $data)
    {
        return true;
    }
}
