<?php
namespace App\Controller\User;

use App\Controller\AppController;

/**
 * QuizSessions Controller
 *
 * @property \App\Model\Table\QuizSessionsTable $QuizSessions
 */
class QuizSessionsController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->deny(['edit', 'delete']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Quiz Session id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->autoRender = false;
        $this->request->allowMethod('PUT', 'POST');

        $quizSession = $this->QuizSessions->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $quizSession = $this->QuizSessions->patchEntity($quizSession, $this->request->getData(), [
                'fieldList' => [
                    'is_hidden'
                ]
            ]);

            if ($quizSession->user_id !== $this->Auth->user('id')) {
                throw new ForbiddenException();
            }

            if ($this->QuizSessions->save($quizSession)) {
                $this->Flash->success(__('Visibilità aggiornata'));
                return $this->redirect($this->referer('/'));
            } else {
                $this->Flash->error(__('Visibilità NON aggiornata'));
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Quiz Session id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->autoRender = false;

        $quizSession = $this->QuizSessions->get($id, [
            'contain' => [
                'Levels',
            ]
        ]);

        if ($quizSession->user_id !== $this->Auth->user('id')) {
            throw new ForbiddenException();
        }

        $levels  = \Cake\Utility\Hash::extract($quizSession->levels, '{n}.id');
        $deleted = $this->QuizSessions->connection()->transactional(function($connection) use ($levels, $quizSession) {
            $connection->begin();

            try {
                $this->QuizSessions->deleteOrFail($quizSession, ['dependent' => true]);
                if (!empty($levels)) {
                    $repliesDeleteCount = $this->QuizSessions->Levels->Replies->deleteAll(['quiz_session_level_id IN' => $levels]);
                }
            } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                $connection->rollback();
            }

            $connection->commit();
        });

        if ($deleted) {
            $this->Flash->success(__('Sessione di gioco eliminata correttamente'));
        }

        return $this->redirect($this->referer('/'));
    }
}
