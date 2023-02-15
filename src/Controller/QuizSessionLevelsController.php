<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * QuizSessionLevels Controller
 *
 * @property \App\Model\Table\QuizSessionLevelsTable $QuizSessionLevels
 */
class QuizSessionLevelsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->Auth->allow(['view']);
    }

    /**
     * Log di gioco
     *
     * Domande e risposte dell'utente
     *
     * @param string|null $id Quiz Session Level id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->helpers(['QuizAnswer']);

        $QuizSessionLevel = $this->QuizSessionLevels->get($id, [
            'contain' => [
                'QuizSessions',
                'QuizSessions.Quizzes'
            ]
        ]);

        $this->loadModel('QuizSessionLevelReplies');

        $q = $this->QuizSessionLevelReplies->find();
        $q->where(['try_seed' => $QuizSessionLevel->best_try_seed]);
        $q->contain([
            'Questions',
            'Answers'
        ]);

        $QuizSessionLevelReplies = $q->all();

        $this->set(compact('QuizSessionLevel', 'QuizSessionLevelReplies'));
    }

}
