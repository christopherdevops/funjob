<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Inflector;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;

/**
 * QuizQuestions Controller
 *
 * @property \App\Model\Table\QuizQuestionsTable $QuizQuestions
 */
class QuizQuestionsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        if ($this->request->getParam('action') == 'add') {
            $this->loadComponent('Security');

            // Disabilita quiz_answers[1-4]
            // perchè a seconda di quiz_question.type vengono disabilitati i campi quiz_answers[1-4]
            for ($i=1; $i < 4; $i++) {
                $this->Security->setConfig('unlockedFields', ['quiz_answers.' .$i. '.is_correct', 'quiz_answers.' .$i. '.answer']);
            }
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($quiz_id)
    {
        $this->loadComponent('Quiz');
        $this->loadComponent('QuizQuestion');

        $this->loadModel('Quizzes');

        // Entity su controller (utilizzata da QuizQuestionComponent)
        $this->quiz = $this->Quizzes->get($quiz_id, ['fields' => ['id', 'title', 'type', 'user_id', 'is_disabled', 'status']]);

        if ($this->Auth->user('type') !== 'admin' && $this->quiz->user_id != $this->Auth->user('id')) {
            throw new \Cake\Network\Exception\ForbiddenException(__('Permesso negato'));
        }

        $questionsCounter = $this->QuizQuestion->getQuestionsCounter();

        $quizQuestion = $this->QuizQuestions->newEmptyEntity();
        $uuid         = \Cake\Utility\Text::uuid();

        // NOTE:
        // Disabilitato perchè potrebbe essere utile poter aggiungere domande aggiuntive?
        //if ($questions >= Configure::read('app.quizQuestion.count')) {
        //    throw new ForbiddenException(__('Hai già inserito tutte le domande per questo quiz'));
        //}

        $this->QuizQuestion->autoShowAnswerByType();

        if ($this->request->is('post')) {
            $uuid = $this->request->getData('uuid');

            // Esegue inject di quiz_answers.1 se il la question è di tipo true_or_false
            // (non è previsto questo campo nel form)
            $this->Quiz->buildAnswerData();

            // A seconda del tipo di question si usa un ValidationSet differente
            $validate     = Inflector::classify(sprintf('quiz_%s', $this->request->getData('type')));

            $quizQuestion = $this->QuizQuestions->patchEntity($quizQuestion, $this->request->getData(), [
                'validate'   => $validate,
                'associated' => ['QuizAnswers' => ['validate' => $validate]]
            ]);

            if ($this->QuizQuestions->save($quizQuestion)) {
                $canPublish = $this->QuizQuestion->hasMinimiumQuestions();
                $this->set(compact('canPublish'));

                if ($this->quiz->status == 'draft' && $canPublish) {
                    $this->Flash->success(__(
                        'Domanda creata: {br} Ora puoi pubblicare il tuo gioco da {link}',
                        [
                            'link' => '<button class="btn btn-default" onclick="$(\'.js-quiz-status-modal\').trigger(\'click\')">' .__('qui'). '</button>',
                            'br'   => '<br/>'
                        ]
                    ), ['escape' => false]);
                    return $this->redirect(['action' => 'add', $quizQuestion->quiz_id]);
                }

                $this->Flash->success(__('Domanda creata'));
                return $this->redirect(['action' => 'add', $quizQuestion->quiz_id]);
            } else {
                $this->Flash->error(__('Verifica che tutti siano corretti, e ritenta'));
                debug('');
                debug($quizQuestion->errors());
            }
        }

        $this->set('quiz', $this->quiz);
        $this->set(compact('quizQuestion', 'questions', 'answerFieldsets', 'uuid'));
        $this->set('_serialize', ['quizQuestion']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Quiz Question id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $quizQuestion = $this->QuizQuestions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $quizQuestion = $this->QuizQuestions->patchEntity($quizQuestion, $this->request->getData());
            if ($this->QuizQuestions->save($quizQuestion)) {
                $this->Flash->success(__('The quiz question has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The quiz question could not be saved. Please, try again.'));
            }
        }
        $quizzes = $this->QuizQuestions->Quizzes->find('list', ['limit' => 200]);
        $this->set(compact('quizQuestion', 'quizzes'));
        $this->set('_serialize', ['quizQuestion']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Quiz Question id.
     * @return \Cake\Network\Response|null Redirects to index.
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
     * Archivio domande per quiz
     *
     * @param  int $quiz_id
     * @param  str $status
     */
    public function index($quiz_id, $status = 'published')
    {
        $this->loadModel('Quizzes');
        $Quiz = $this->Quizzes->get($quiz_id, [
            'contain' => [
                'Author' =>  function($q) {
                    $q->select(['id']);
                    return $q;
                }
            ]
        ]);

        if ($this->Auth->user('type') !== 'admin' && $Quiz->author->id !== $this->Auth->user('id')) {
            throw new ForbiddenException(__('Permesso negato'));
        }

        $q = $this->QuizQuestions->find();

        if ($status == 'published') {
            $q->find('isPublished');
        } else {
            $q->where(['is_published' => false]);
        }

        $q->contain(['QuizAnswers',]);
        $q->where(['QuizQuestions.quiz_id' => $Quiz->id]);
        $q->orderDesc('QuizQuestions.id');

        $questions = $q->all();

        $this->set(compact('quiz', 'questions'));
        $this->set('_serialize', ['quiz', 'questions']);
    }

    /**
     * Elimina flag is_published da una domanda
     *
     * In questo modo non sarà estratta dal motore delle domande
     *
     * @param  int|null $question_id
     */
    public function visibility($question_id)
    {
        $this->autoRender = false;
        $this->request->allowMethod('PUT');

        $QuizQuestion = $this->QuizQuestions->get($this->request->getData('id'), [
            'contain' => [
                'Quizzes'
            ]
        ]);

        if ($this->Auth->user('type') !== 'admin' && $QuizQuestion->quiz->id !== $this->Auth->user('id')) {
            throw new ForbiddenException(__('Permesso negato'));
        }

        $QuizQuestion = $this->QuizQuestions->patchEntity($QuizQuestion, $this->request->getData(), [
            'fieldList' => ['is_published']
        ]);

        $updated = $this->QuizQuestions->getConnection()->transactional(function () use ($QuizQuestion) {
            $firstUpdate = $this->QuizQuestions->save($QuizQuestion);
            if (!$firstUpdate) {
                $this->response->type('json');
                $this->response->body(json_encode([
                    'status'  => 'success',
                    'message' => __('Impossibile aggiornare visibilità domanda')
                ]));
                return false;
            }

            if ($QuizQuestion->is_published) {
                $this->response->body(json_encode([
                    'status'  => 'success',
                    'message' => '<i class="fa fa-check text-success"></i> '. __('Domanda abilitata')
                ]));
            } else {
                $this->loadComponent('QuizQuestion');
                $this->quiz = $QuizQuestion->quiz;

                if (!$this->QuizQuestion->hasMinimiumQuestions()) {
                    $this->response->body(json_encode([
                        'status'  => 'failure',
                        'message' => '<i class="fa fa-warning text-danger"></i> ' . __('Non puoi disabilitare le domande se non ce ne sono a sufficienza')
                    ]));

                    // Forzo
                    $QuizQuestion->is_published = true;
                    return $this->QuizQuestions->save($QuizQuestion);
                }


                $this->response->body(json_encode([
                    'status'  => 'success',
                    'message' => '<i class="fa fa-check text-danger"></i> '. __('Domanda disabilitata')
                ]));
            }

            return $firstUpdate;
        });

        $this->response->type('json');
        return $this->response;
    }
}
