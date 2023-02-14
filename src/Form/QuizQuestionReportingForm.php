<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * QuizQuestionReporting Form.
 */
class QuizQuestionReportingForm extends Form
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
        $validator->requirePresence(['question_id', 'reason']);

        $validator->add('quiz_id', 'isValidId', [
            'rule' => ['naturalNumber', false],
            'message' => __('Valore errato')
        ]);
        $validator->add('user_id', 'isValidId', [
            'rule' => ['naturalNumber', false],
            'message' => __('Valore errato')
        ]);
        $validator->add('question_id', 'isValidId', [
            'rule' => ['naturalNumber', false],
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
