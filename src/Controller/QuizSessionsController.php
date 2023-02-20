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

        $quizSession = $this->QuizSessions->newEmptyEntity();

        if ($this->request->is('post')) {
            $sessionPath = sprintf('Quiz.%d', $this->request->getData('quiz_id'));

            if (!$this->request->getSession()->check($sessionPath)) {
                throw new \BadRequestException();
            }

            $quiz        = $this->QuizSessions->Quizzes->get($this->request->getData('quiz_id'));
            $replies     = $this->request->getSession()->read($sessionPath);
            $corrects    = array_filter($replies, function($isCorrect) { return $isCorrect === true; });
            $score       = sizeof($corrects);

            $this->setRequest($this->request
                    ->withData('score', (string) sizeof($corrects))
                    ->withData('user_id', $this->Auth->user('id'))
                    ->withData('lang', $quiz->lang)
                    // TODO:
                    // Impostare livello solo per quiz funjob
                    ->withData('level', 1)
            );

            $quizSession = $this->QuizSessions->patchEntity($quizSession, $this->request->getData());

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
