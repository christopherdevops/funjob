<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * UserJobOffers Form.
 */
class UserJobOffersForm extends Form
{
    /**
     * Builds the schema for the modelless form
     *
     * @param Schema $schema From schema
     * @return $this
     */
    protected function _buildSchema(Schema $schema)
    {
        $schema
            ->addField('fullname', 'string')
            ->addField('username', 'string')
            ->addField('role', 'string')
            ->addField('age_from', ['type' => 'integer'])
            ->addField('age_to', ['type' => 'integer'])
            ->addField('city', ['type' => 'string'])
            ->addField('city_uuid', ['type' => 'string'])
            ->addField('skills', ['type' => 'string']);

        return $schema;
    }

    /**
     * Form validation builder
     *
     * @param Validator $validator to use against the form
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator->allowEmpty(['fullname', 'age_from', 'age_to', 'role', 'city', 'city_id']);

        //$validator->requirePresence('city_id');
        //$validator->notEmpty('role');

        // $validator->add('fullname', 'firstNameAndLastNameExists', [
        //     'rule'    => function($value, $settings) {
        //         $fullname = trim($value);
        //         $fullname = explode(' ', $fullname, 2);
        //         return sizeof($fullname) > 1;
        //     },
        //     'message' => __('Richiede sia nome che cognome'),
        //     'on' => function ($context) {
        //         return !empty($context['data']['fullname']);
        //     },
        // ]);

        $validator->add('age_from', 'isNumeric', [
            'rule'    => ['numeric'],
            'message' => __('Richiede un valore numerico')
        ]);

        $validator->add('age_to', 'isNumeric', [
            'rule'    => ['numeric'],
            'message' => __('Richiede un valore numerico')
        ]);

        $validator->add('age_to', 'isGreaterThenFrom', [
            'on'      => function($context) {
                return !empty($context['data']['age_from']);
            },
            'rule'    => function($value, $context) {
                return (int) $value > (int) $context['data']['age_from'];
            },
            'message' => __('Questo valore deve essere più grande dell\'età minima specificata')
        ]);

        // Verifica che city_id sia impostato (quindi è stato usato l'autocomplete di typeahead)
        $validator->add('city_id', 'selectAutocomplete', [
            'on' => function ($context) {
                return !empty($context['data']['city']);
            },
            'rule' => function($value, $context) {
                return !empty($value);
            },
            'message' => __('Compila il campo, e seleziona un risultato')
        ]);

        return $validator;
    }

    public function setErrors($errors = [])
    {
        $this->_errors = $errors;
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
