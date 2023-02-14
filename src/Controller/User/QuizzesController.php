<?php
namespace App\Controller\User;

use App\Controller\AppController;

use Cake\Network\Exception\NotImplementedException;
use Cake\Network\Exception\ForbiddenException;

class QuizzesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        if ($this->request->action == 'status') {
            $this->loadComponent('Security');
        }
    }

    /**
     * I miei risultati ottenuti
     */
    public function played() {
        $this->loadModel('Users');

        $settings = ['user_id' => $this->Auth->user('id')];

        // Filtra quiz per nome
        if ($this->request->is('post')) {
            $this->redirect(['?' => $this->request->getData()]);
        }

        $q = $this->Quizzes->QuizSessions->find('quizCompleted', $settings);
        $q->select(['QuizSessions.is_hidden']);

        $visibility = $this->request->getQuery('visibility', 'all');
        if ($visibility != 'all') {
            $bool = $visibility == 'hidden';
            $q->bind(':visibility', $bool, 'boolean');
            $q->where(['QuizSessions.is_hidden = :visibility']);
        }

        // Filtri
        if ($this->request->getQuery('name')) {
            $term = trim($this->request->getQuery('name'));
            if (strpos($term, '*') === FALSE) {
                $term .= '*';
            }
            $q->bind(':name', $term);

            $_fulltext = 'MATCH(Quizzes.title) AGAINST(:name IN BOOLEAN MODE)';
            $q->select(['_match' => $_fulltext]);
            $q->where([$_fulltext]);
            $q->orderAsc('_match');
        }

        if ($this->request->getQuery('categories')) {
            $ids = $this->request->getQuery('categories', []);
            $q->matching('Quizzes.Categories', function($q) use ($ids) {
                $q->where(['Categories.id' => $ids], ['Categories.id' => 'integer[]']);
                return $q;
            });
        }


        $sessions   = $this->paginate($q, []);
        $User       = $this->Users->findById($this->Auth->user('id'))->firstOrFail();
        $categories = $this->Users->QuizSessions
            ->find('playedCategories', ['user_id' => $this->Auth->user('id')])
            ->hydrate(false)->toArray();

        $this->set(compact('sessions', 'User', 'categories'));
        //$this->set('quizzes', $sessions);

        $this->render('played');
    }

    /**
     * Aggiorna visibilità quiz utente
     */
    public function status($id = null)
    {
        $this->request->allowMethod('PUT');

        $this->loadComponent('Security');
        $this->loadComponent('QuizQuestion');

        $Quiz = $this->Quizzes->get($id, [
            'fields' => ['id', 'user_id', 'type', 'status']
        ]);

        if ($this->Auth->user('type') !== 'admin' && $Quiz->user_id != $this->Auth->user('id')) {
            throw new ForbiddenException(__('Permesso negato'));
        }

        if ($this->request->getData('status') == 'published') {
            // NOTE: $this->quiz
            // Probabilmente è richiesto da QuizQuestionComponent
            $this->quiz = $Quiz;
            $canUpdateStatus = $this->QuizQuestion->hasMinimiumQuestions();
            if (!$canUpdateStatus) {
                $this->Flash->error(
                    __('Questo gioco non rispecchia i requisiti minimi per essere pubblicato (numero domande minime)')
                );
                return $this->redirect($this->referer());
            }
        }

        $this->Quizzes->patchEntity($Quiz, $this->request->getData());

        if ($this->Quizzes->save($Quiz)) {
            $status = $this->request->getData('status');

            if ($status == 'published') {
                $message = __('Gioco pubblicato: potrà essere giocato dagli utenti');
            } elseif ($status == 'hidden') {
                $message = __('Gioco nascosto');
            } else {
                $message = __('Gioco eliminato');
            }

            $this->Flash->success($message);
            return $this->redirect($this->referer());
        }

        $this->Flash->error(__('Impossibile cambiare visibilità quiz'));
        return $this->redirect($this->referer());
    }

}
