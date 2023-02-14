<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Quizzes Controller
 *
 * @property \App\Model\Table\QuizzesTable $Quizzes
 *
 * @method \App\Model\Entity\Quiz[] paginate($object = null, array $settings = [])
 */
class QuizzesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        // POST 2 GET
        if ($this->request->is('post')) {
            $pass      = $this->request->getParam('pass');
            $pass['?'] = $this->request->getData();
            return $this->redirect($pass);
        }

        $q = $this->Quizzes->find();
        $q->find('withQuestionsCounter');

        // if ($this->request->getQuery('author')) {
        //     $q->find('byAuthor',  ['user_id' => $this->request->getQuery('author')]);
        // }

        if ($this->request->getQuery('sort')) {
            switch ($this->request->getQuery('sort')) {
                case 'ranking':
                    $q->orderDesc('_avg');
                break;

                default:
            }
        }

        if ($this->request->getQuery('status')) {
            $q->find('byStatus', ['status' => $this->request->getQuery('status')]);
        } else {
            $q->find('published');
        }

        if ($this->request->getQuery('type')) {
            $q->find('byType', ['type' => $this->request->getQuery('type')]);
        }

        if ($this->request->getQuery('term')) {
            $q->find('searchByTerm', [
                'term' => $this->request->getQuery('term'),
                'tags' => true
            ]);
        }


        $q->contain([
            'Author' => function($q) {
                $q->select(['id', 'username', 'type']);
                return $q;
            },
            'Categories'
        ]);

        $q->find('withRanking');

        $q->select(['Quizzes.id', 'Quizzes.type', 'Quizzes.title', 'Quizzes.user_id', 'Quizzes.is_disabled', 'Quizzes.status']);
        $q->orderDesc('Quizzes.id');

        $quizzes  = $this->paginate($q, ['limit' => 30]);

        $this->set(compact('quizzes'));
        $this->set('_serialize', ['quizzes']);
    }

    /**
     * View method
     *
     * @param string|null $id Quiz id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $quiz = $this->Quizzes->get($id, [
            'contain' => ['Author', 'Categories', 'QuizQuestions', 'QuizSessions', 'Tags', 'UserRankings']
        ]);

        $this->set('quiz', $quiz);
        $this->set('_serialize', ['quiz']);
    }

    /**
     * Disabilita quiz (ban)
     *
     * @param string|null $id Quiz id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function disable($id = null)
    {
        $this->request->allowMethod('PUT');
        $quiz = $this->Quizzes->get($id);

        $this->Quizzes->patchEntity($quiz, $this->request->getData(), ['fieldList' => ['is_disabled']]);

        if ($this->Quizzes->save($quiz)) {
            if ($quiz->is_disabled) {
                $message = __('Quiz disabilitato');
                $class   = 'success';
            } else {
                $message = __('Quiz non più disabilitato');
                $class   = 'success';
            }

            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                $this->response->type('json');
                $this->response->body(json_encode(compact('message', 'class')));
                return null;
            }

            $this->Flash->success($message);
            return $this->redirect($this->referer());
        } else {
            $this->Flash->error(__('Impossibile aggiornare flag "disabled" quiz'));
        }

        return $this->redirect($this->referer());
    }


    /**
     * Aggiunge quiz su homepage
     */
    public function sendToHome()
    {
        $this->request->allowMethod('POST');
        $this->loadModel('HomePopularQuizzes');

        $PopularQuiz = $this->HomePopularQuizzes->newEntity(['quiz_id' => $this->request->getData('id')]);

        if ($this->HomePopularQuizzes->save($PopularQuiz)) {
            $this->Flash->success(__('Aggiunto a homepage'));
        }

        return $this->redirect($this->referer());
    }
}
