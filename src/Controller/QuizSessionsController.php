<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * QuizSessions Controller
 *
 * @property \App\Model\Table\QuizSessionsTable $QuizSessions
 */
class QuizSessionsController extends AppController
{

    public function initialize(): void {
        parent::initialize();

        $this->Auth->allow(['view']);
    }

    /**
     * View method
     *
     * @param string|null $id Quiz Session id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id)
    {
        //$Hashids = new Hashids('iueuio34utiou89sdfpibvopusdfiovjioux', 25);
        //$id = $Hashids->decode($id);

        $quizSession = $this->QuizSessions->get($id, [
            'contain' => [
                'Users',
                'Quizzes',

                'LevelsPassed',
                'LevelsPassed.Replies',

                'LevelsPassed.Replies.Questions',
                'LevelsPassed.Replies.Answers'

            ]
        ]);

        $this->set('quizSession', $quizSession);
        $this->set('_serialize', ['quizSession']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post']);

        $quizSession = $this->QuizSessions->newEntity();

        if ($this->request->is('post')) {
            $sessionPath = sprintf('Quiz.%d', $this->request->data['quiz_id']);

            if (!$this->request->session()->check($sessionPath)) {
                throw new \BadRequestException();
            }

            $quiz        = $this->QuizSessions->Quizzes->get($this->request->data('quiz_id'));
            $replies     = $this->request->session()->read($sessionPath);
            $corrects    = array_filter($replies, function($isCorrect) { return $isCorrect === true; });
            $score       = sizeof($corrects);

            $this->request->data('score', (string) sizeof($corrects));
            $this->request->data('user_id', $this->Auth->user('id'));
            $this->request->data('lang', $quiz->lang);

            // TODO:
            // Impostare livello solo per quiz funjob
            $this->request->data('level', 1);

            $quizSession = $this->QuizSessions->patchEntity($quizSession, $this->request->data);

            if ($this->QuizSessions->save($quizSession)) {
                //$this->Flash->success(__('The quiz session has been saved.'));
                return $this->redirect(['controller' => 'quizzes', 'action' => 'view', 0 => $quiz->id]);
            } else {
                //$this->Flash->error(__('The quiz session could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('quizSession'));
        $this->set('_serialize', ['quizSession']);
    }

}
