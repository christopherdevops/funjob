<?php
namespace App\Controller\User;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;

/**
 * QuizUserRankings Controller
 *
 * @property \App\Model\Table\QuizUserRankingsTable $QuizUserRankings
 */
class QuizUserRankingsController extends AppController
{
    public function add() {
        $this->autoRender = false;
        $this->request->allowMethod(['POST']);

        $quizUserRanking = $this->QuizUserRankings->newEntity($this->request->getData());
        if ($this->QuizUserRankings->save($quizUserRanking)) {
            $this->Flash->success(__('Grazie per aver contribuito a FunJob'));
            return $this->redirect($this->referer('/'));
        }

        return $this->redirect($this->referer('/'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Quiz User Ranking id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->autoRender = false;
        $this->request->allowMethod(['PUT']);

        $quizUserRanking = $this->QuizUserRankings->get($id);
        if ($quizUserRanking->user_id != $this->Auth->user('id')) {
            throw new ForbiddenException();
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $quizUserRanking = $this->QuizUserRankings->patchEntity($quizUserRanking, $this->request->getData());

            if ($this->QuizUserRankings->save($quizUserRanking)) {
                $this->Flash->success(__('Grazie, per aver espresso la tua preferenza'));
                return $this->redirect($this->referer('/'));
            }

            $this->Flash->error(__('The quiz user ranking could not be saved. Please, try again.'));

            if ($quizUserRanking->errors() && Configure::read('debug')) {
                dd($quizUserRanking->errors());
            }
            return $this->redirect($this->referer('/'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Quiz User Ranking id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $quizUserRanking = $this->QuizUserRankings->get($id);
        if ($this->QuizUserRankings->delete($quizUserRanking)) {
            $this->Flash->success(__('The quiz user ranking has been deleted.'));
        } else {
            $this->Flash->error(__('The quiz user ranking could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
