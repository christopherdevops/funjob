<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * QuizUserReporting Form.
 */
class QuizUserReportingForm extends Form
{
    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        $schema->addField('quiz_id', ['type' => 'integer']);
        $schema->addField('user_id', ['type' => 'integer']);
        $schema->addField('reason', ['type' => 'text']);

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
        $validator->requirePresence(['quiz_id', 'reason']);

        $validator->add('quiz_id', 'isValidQuizId', [
            'rule'    => ['naturalNumber', false],
            'message' => __('Valore errato')
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
