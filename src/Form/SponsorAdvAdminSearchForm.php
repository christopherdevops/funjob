<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * SponsorAdvAdminSearch Form.
 */
class SponsorAdvAdminSearchForm extends Form
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
        $validator->allowEmpty(['status', 'uuid', 'type']);

        $validator->add('uuid', 'isValid', [
            'rule'    => 'uuid',
            'message' => 'Verifica che il valore sia quello riportato da Paypal su "Custom field"'
        ]);

        $validator->add('type', 'isValidChoice', [
            'rule'    => ['inList', ['banner', 'banner-quiz']],
            'message' => 'Valore sconosciuto'
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
}
