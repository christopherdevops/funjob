<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\BadRequestException;

/**
 * QuizQuestions Controller
 *
 * @property \App\Model\Table\QuizQuestionsTable $QuizQuestions
 *
 * @method \App\Model\Entity\QuizQuestion[] paginate($object = null, array $settings = [])
 */
class QuizQuestionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        if (!$this->request->is('ajax')) {
            throw new ForbiddenException();
        }

        if ($this->request->is('post')) {
            return $this->redirect(['?' => $this->request->getData()]);
        }

        $q = $this->QuizQuestions->find();
        $q->find('isPublished');

        $q->contain([
            'Quizzes' => function($q) {
                $q->select(['id', 'title', 'user_id', 'type']);
                return $q;
            },
            'Quizzes.Author' => function($q) {
                $q->select(['id', 'username', 'first_name', 'last_name', 'name', 'avatar']);
                return $q;
            },
            'QuizAnswers'
        ]);

        if ($this->request->getQuery('term')) {
            $q->find('searchByTerm', [
                'term'       => $this->request->getQuery('term'),
                'in_answers' => true
            ]);
        }

        if ($this->request->getQuery('category')) {
            if (is_array($this->request->getQuery('category'))) {
                $ids = $this->request->getQuery('category');
            } else {
                $ids = explode(',', $this->request->getQuery('category'));
            }

            $q->find('byCategory', [
                'category_id' => $ids
            ]);
        }

        if ($this->request->getQuery('complexity')) {
            $complexityGroup = $this->request->getQuery('complexity');
            $complexityValuesMap = [
                'easy'   => [1,2,3],
                'medium' => [4,5,6,7],
                'hard'   => [8,9,10]
            ];
            $complexityValues = $complexityValuesMap[$complexityGroup];
            $q->find('questionsByLevel', ['complexity' => $complexityValues]);
        }

        $questions  = $this->paginate($q);
        $term       = $this->request->getQuery('term');
        $categories = $this->QuizQuestions->Quizzes->Categories->find('treeList', [
            'spacer' => ' '
        ]);

        //$categories = $this->_treeForSelect2Tree();

        $this->set(compact('questions', 'term', 'categories'));
        $this->set('_serialize', ['questions']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $quizQuestion = $this->QuizQuestions->newEntity();
        if ($this->request->is('post')) {
            $quizQuestion = $this->QuizQuestions->patchEntity($quizQuestion, $this->request->getData());
            if ($this->QuizQuestions->save($quizQuestion)) {
                $this->Flash->success(__('The quiz question has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The quiz question could not be saved. Please, try again.'));
        }
        $quizzes = $this->QuizQuestions->Quizzes->find('list', ['limit' => 200]);
        $this->set(compact('quizQuestion', 'quizzes'));
        $this->set('_serialize', ['quizQuestion']);
    }

    /**
     * Clone method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function import($question_id = null)
    {
        $this->request->allowMethod(['PUT']);
        $this->autoRender = false;

        // Validazione
        if (!$this->request->getData('id')) {
            throw new BadRequestException();
        }

        if (!$this->request->getData('quiz_id')) {
            throw new BadRequestException();
        }

        // Verifica che la domanda da importare non sia stata già importata precedentemente
        $alreadyInQuestions  = $this->QuizQuestions->find('alreadyInQuiz', [
            'quiz_id'     => (int) $this->request->getData('quiz_id'),
            'question_id' => (int) $this->request->getData('id')
        ]);

        $occurrencies = $alreadyInQuestions->all()->count();
        if ($occurrencies > 0) {
            $this->response->statusCode(500);
            $this->response->body(json_encode([
                'message'  => __d('backend', 'Domanda giù utilizzata per questo quiz'),
                'style'    => 'error',
                'entities' => $occurrencies
            ]));
            return $this->response;
        }

        $QuizQuestion = $this->QuizQuestions->get($this->request->getData('id'), [
            'contain' => [
                'QuizAnswers'
            ]
        ]);

        $QuizQuestion->is_published = true;
        $QuizQuestion->quiz_id      = $this->request->getData('quiz_id');
        $QuizQuestion->cloned_by    = $this->request->getData('id');

        // Rimuove IDs
        $QuizQuestion->id      = null;
        $QuizQuestion->quiz_id = $this->request->getData('quiz_id');
        $QuizQuestion->isNew(true);

        foreach ($QuizQuestion->quiz_answers as $answer) {
            $answer->cloned_by = $answer->id;

            $answer->isNew(true);
            $answer->quiz_question_id = null;
            $answer->id               = null;
        }

        $saveSettings = [
            'associated' => ['QuizAnswers']
        ];

        if ($this->QuizQuestions->save($QuizQuestion, $saveSettings)) {
            $this->response->type('json');
            $message = __d('backend', 'Domanda importata correttamente');
            $this->response->body(json_encode(['message' => $message, 'style' => 'success']));
        } else {
            $this->response->type('json');
            $message = __d('backend', 'Impossibile importata domanda');
            $this->response->body(json_encode(['message' => $message, 'style' => 'error']));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Quiz Question id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $Question = $this->QuizQuestions->get($id, [
            'contain' => [
                'QuizAnswers',
                'Quizzes'
            ]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $Question = $this->QuizQuestions->patchEntity($Question, $this->request->getData(), [
                'associated' => [
                    'QuizAnswers'
                ]
            ]);

            $updated = $this->QuizQuestions->getConnection()->transactional(function () use ($Question) {
                if ($this->QuizQuestions->save($Question)) {
                    $this->Flash->success(__('Aggiornamento completato'));

                    if ($Question->is_banned) {
                        $this->loadComponent('QuizQuestion');
                        $this->quiz = $Question->quiz;

                        if (!$this->QuizQuestion->hasMinimiumQuestions()) {
                            $this->Flash->error(
                                '<i class="text-danger fa fa-warning"></i> ' .
                                __('Impossibile bannare domanda: non ci sono domande a sufficienza')
                            , ['escape' => false]);
                            $Question->is_banned = false;
                            return $this->QuizQuestions->save($Question);
                        }
                    }
                    return true;
                }

                return false;
            });

            if ($updated) {
                return $this->redirect($this->referer());
            }

            $this->Flash->error(__('Aggiornamento fallito'));
        }

        $this->set(compact('Question'));
        $this->set('_serialize', ['Question']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Quiz Question id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $quizQuestion = $this->QuizQuestions->get($id);
        if ($this->QuizQuestions->delete($quizQuestion)) {
            $this->Flash->success(__('The quiz question has been deleted.'));
        } else {
            $this->Flash->error(__('The quiz question could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Crea <select /> per FormHelper compatibile con select2Tree (https://github.com/lonlie/select2tree)
     * Attualmente la versione corrente di select2Tree non è performante con grandei tree
     *
     * @return array
     */
    private function _treeForSelect2Tree()
    {
        $quizCategories = $this->QuizQuestions->Quizzes->Categories->find('threaded');
        $tree           = [];

        $Iterator = new \RecursiveIteratorIterator(
            new \App\Lib\TreeIterator($quizCategories->toArray()),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $categories = [];
        foreach ($Iterator as $category) {
            $attrs  = ['value' => $category->id, 'text' => $category->name];

            if (!empty($category->parent_id)) {
                $attrs['parent'] = $category->parent_id;
            }

            $categories[] = $attrs;
        }

        return $categories;
    }


    /**
     * Esporta utenti attivi in CSV
     */
    public function export() {
        $this->viewBuilder()->className('CsvView.Csv');

        $q = $this->QuizQuestions->find();
        $q->select(['QuizQuestions.question', 'QuizQuestions.id', 'Quizzes.title']);
        $q->contain(['QuizAnswers']);
        $q->matching('Quizzes', function($q) {
            $q->find('published');
            return $q;
        });

        $q->where(['QuizQuestions.is_published' => true]);

        $questions = $q->hydrate(false)->all();

        $_header        = [
            'Quiz',
            'Identificativo', 'Domanda', 'Risposta corretta',
            'Risposta scorretta', 'Risposta scorretta', 'Risposta Scorretta', 'Risposta Scoretta'
        ];

        $_delimiter     = ';';
        $_extract       = [
            function($row) {
                return $row['_matchingData']['Quizzes']['title'];
            },
            'id',
            'question',
            function($row) {
                $reply = null;
                foreach ($row['quiz_answers'] as $key => $data) {
                    if ($data['is_correct']) {
                        return $data['answer'];
                    }
                }
                return $reply;
            },

            function($row) {
                $reply = null;
                if (!empty($row['quiz_answers'][1])) {
                    $reply = $row['quiz_answers'][1]['answer'];
                }
                return $reply;
            },

            function($row) {
                $reply = null;
                if (!empty($row['quiz_answers'][2])) {
                    $reply = $row['quiz_answers'][2]['answer'];
                }
                return $reply;
            },

            function($row) {
                $reply = null;
                if (!empty($row['quiz_answers'][3])) {
                    $reply = $row['quiz_answers'][3]['answer'];
                }
                return $reply;
            },

        ];

        $_serialize = ['questions'];
        $this->set(compact('questions', '_header', '_extract', '_delimiter', '_serialize'));
    }
}
