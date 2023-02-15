<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * QuizSessionStartForm Form.
 */
class QuizSessionStartForm extends Form
{
    /**
     * Builds the schema for the modelless form
     *
     * @param Schema $schema From schema
     * @return $this
     */
    protected function _buildSchema(Schema $schema): \Cake\Form\Schema
    {
        $schema
            ->addField('adv', 'string')
            ->addField('quiz_id', ['type' => 'integer'])
            ->addField('level', ['type' => 'integer']);

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
        $validator->requirePresence(['quiz_id']);
        $validator
            ->requirePresence(['level'])
            ->add('level', [

                'guest' => [
                    'message' => __('Registrazione richiesta'),
                    'rule'    => function($value, $context) {
                        //if ($context['data']['_quiz']->type == 'funjob') {
                            // Utente non loggato
                            return !$context['data']['_user'] == null;
                        //}

                        return true;
                    },
                ],


                'unlocked' => [
                    'message' => __('Non hai ancora sbloccato questo livello'),
                    'rule'    => function($level, $context) {
                        $user_id = $context['data']['_user']['id'];
                        $quiz    = $context['data']['_quiz'];

                        //if ($quiz->type == 'funjob') {
                            $QuizSessions = \Cake\ORM\TableRegistry::get('QuizSessions');
                            $Session = $QuizSessions->find()
                                ->where(['user_id' => $user_id, 'is_deleted' => false])
                                ->where(['quiz_id' => $context['data']['_quiz']['id']])
                                ->contain(['LevelsPassed'])
                                ->first();

                            if (empty($Session)) {
                                return $level == 1;
                            }

                            // Livello richiesto 1: nessun punteggio (lascio procedere)
                            if ($level == 1) {
                                return true;
                            }

                            // Verifica che l'utente abbia superato i livelli precedenti
                            $passed = \Cake\Utility\Hash::combine($Session['levels_passed'], '{n}.level', '{n}.score');
                            $return = true;

                            for ($i=1; $i < $level; $i++) {
                                if (!isset($passed[$i])) {
                                    $return = false;
                                }
                            }

                            return $return;
                        //}

                        return true;
                    },
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



    private function _iterateLevelFields($level)
    {
        foreach (range(1, $level - 1) as $_level) {
            $score = $Session->{'level_' . $_level};
            //debug(sprintf('Livello => %d  (punteggio => %d/%d)', $_level, $score, $minScore));

            if ($score === null || $score < $minScore) {
                return false;
            }
        }

        return true;
    }
}
