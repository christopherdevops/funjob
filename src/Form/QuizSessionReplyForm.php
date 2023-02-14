<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * QuizSessionReply Form.
 */
class QuizSessionReplyForm extends Form
{
    /**
     * Builds the schema for the modelless form
     *
     * @param Schema $schema From schema
     * @return $this
     */
    protected function _buildSchema(Schema $schema)
    {
        $schema->addField('reply', ['type' => 'integer']);
        $schema->addField('secs', ['type' => 'integer']);

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
        $validator->requirePresence(['quiz_id', 'reply', 'secs']);
        $validator->notEmpty(['reply', 'secs']);

        $validator->add('quiz_id', 'isID', [
            'rule'    => ['naturalNumber', false],
            'message' => __('ID non valido')
        ]);

        $validator->add('reply', [
            'isID' => [
               'rule'    => ['naturalNumber', false], // allowZero = true
               'message' => __('Risposta sconosciuta')
            ]
        ]);

        $validator->add('secs', [
            'notExpired' => [
                'rule'    => ['comparison', '>', 0],
                'message' => __('Tempo scaduto')
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
