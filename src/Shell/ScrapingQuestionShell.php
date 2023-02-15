<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Http\Client;
use Cake\Utility\CakeText;

//use App\Model\QuizQuestion;
//use App\Model\QuizAnswer;

/**
 * ScrapingQuestion shell command.
 */
class ScrapingQuestionShell extends Shell
{
    const API_ENDPOINT = 'https://opentdb.com/api.php';

    /**
     * Default settings
     * @var array
     */
    protected $_defaults = [
        'amount'     => 30,
        'category'   => 11,         // FILM
        'type'       => 'multiple', // MULTIPLE/TRUE_OR_FALSE
        'complexity' => 'easy'
    ];

    /**
     * CakePHP HTTP Client
     * @var \Cake\Http\Client
     */
    protected $Client;

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser(): \Cake\Console\ConsoleOptionParser
    {
        $parser = parent::getOptionParser();

        $parser->addSubcommand('fetch', [
            'help'   => 'Importa nuove domande sul database',
            'parser' => [
                'options' => [
                    'quiz' => [
                        'help'     => 'Quiz ID',
                        'required' => true,
                    ],

                    'difficulty' => [
                        'help'     => 'Difficoltà domanda',
                        'required' => false,
                        'default'  => 'medium',
                        'choices'  => ['easy', 'medium', 'hard']
                    ],

                    'amount' => [
                        'required' => false,
                        'default'  => 10,
                    ]
                ]
            ]
        ]);

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
    }

    public function fetch()
    {
        $this->loadModel('QuizQuestions');

        if (!in_array('quiz', array_keys($this->params))) {
            $this->error(__('Specifica ID quiz --quiz=5'));
        }

        $response = $this->_api($this->params);
        $imported = 0;


        $Quiz = $this->QuizQuestions->Quizzes->get((int) $this->params['quiz']);

        foreach ((array) $response->json['results'] as $question) {
            $attrs = [
                'question'   => $question['question'],
                'complexity' => $this->_getLevel( $question['difficulty'] ),
                'type'       => 'default',
                'quiz_id'    => $Quiz->id,

                'quiz_answers' => [
                    0 => [
                        'answer'     => $question['correct_answer'],
                        'is_correct' => true
                    ]
                ]
            ];

            foreach ($question['incorrect_answers'] as $answer) {
                $attrs['quiz_answers'][] = [
                    'answer'     => $answer,
                    'is_correct' => false
                ];
            }

            $QuizQuestion = $this->QuizQuestions->newEntity($attrs, [
                'contain' => 'QuizAnswer'
            ]);

            if ($this->QuizQuestions->save($QuizQuestion)) {
                $this->out(__('<success>Nuova domanda:</success> {0}', $question['question']));
                $imported++;
            } else {
                $this->err(__('Impossibile importare nuova domanda: {0}', $question['question']));
                debug($QuizQuestion->errors());
            }
        }

        $this->out(__('√ {0} nuove domande', $imported));
    }

    /**
     * Preleva domande da API
     *
     * @param  array  $queryString
     * @return \Cake\Http\Response
     */
    protected function _api($queryString = [])
    {
        $Client = new Client();
        $query  = array_merge($this->_defaults, $queryString);

        // Simple get with querystring
        $response = $Client->get(self::API_ENDPOINT, $query);

        if (!$response->isOk()) {
            $this->error($response->body());
        }

        if (empty($response->json['results'])) {
            $this->verbose($response->body());
            $this->error(__('Empty results'));
        }

        return $response;
    }

    protected function _getLevel($name)
    {
        if ($name == 'easy')
        {
            return 1;
        } elseif ($name == 'medium') {
            return 5;
        } else {
            return 9;
        }
    }
}
