<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Routing\Router;

use Cake\Datasource\Exception;
use Cake\Datasource\Exception\RecordNotFoundException;

use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\ForbiddenException;

use App\Form\QuizUserReportingForm;

use Cake\Utility\Text;

/**
 * Quizzes Controller
 *
 * @property \App\Model\Table\QuizzesTable $Quizzes
 */
class QuizzesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->Auth->allow(['index', 'popular', 'view', 'browse', 'search', 'rand']);

        // Csrf attivato da tutte le parti
        // TODO: eliminare e testare
        if ($this->request->getParam('action') == 'edit') {
            // $this->loadComponent('Csrf');
        }

        if (in_array($this->request->getParam('action'), ['view', 'play'])) {
            $Quiz = $this->Quizzes->get($this->request->getParam('pass')[0]);

            if (empty($Quiz)) {
                throw new \Cake\Network\Exception\NotFoundException(__('Quiz non trovato'));
            }

            if ($Quiz->status !== 'published') {
                throw new ForbiddenException(__('Non è ancora stato pubblicato'));
            }

            // Csrf attivato da tutte le parti
            // TODO: eliminare e testare
            // $this->loadComponent('Csrf');
            //$this->Security->disableFields = ['_secs'];
        }

        // Loading Game components
        if (in_array($this->request->getParam('action'), ['play', 'reply', 'score'])) {
            $this->loadComponent('QuizGame');
            $this->loadComponent('QuizSession');
            $this->loadComponent('QuizScore');
        }
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        if (in_array($this->request->getParam('action'), ['play', 'reply', 'score'])) {
            $quiz_id = $event->getSubject()->request->getParam('pass.0');

            $this->QuizGame->config('quiz_id', (int) $quiz_id);
            $this->QuizSession->config('quiz_id', (int) $quiz_id);
            $this->QuizScore->config('quiz_id', (int) $quiz_id);
        }
    }

    public function getAdvertising() {
        // Pubblicità disattivata su schermata di gioco (richiesta da Giuseppe)
        if ($this->request->getParam('action') == 'play') {
            return false;
        }

        return parent::getAdvertising();
    }

    /**
     * Quiz randomici
     *
     * NON UTILIZZATO
     *
     * @return \Cake\Network\Response|null
     */
    private function _rand()
    {
        // TODO: config
        $minQuizzes = 3;

        // Estraggo 5 categorie in modo randomico che contengono minimo $minQuizzes
        $categoriesq = TableRegistry::get('CategoriesQuizzes')->find();
        $categoriesq
            ->limit(6)
            ->select([
                'Categories.name',
                'Categories.id',
                'count_quizzes' => 'COUNT(CategoriesQuizzes.category_id)'
            ])
            ->contain(['Categories'])
            ->group(['CategoriesQuizzes.category_id'])
            ->having(['count_quizzes >' => $minQuizzes]);

        $categories   = $categoriesq->all();
        $category_ids = $categories->extract('category.id')->toArray();

        // Estraggo quiz per ogni categoria in $categories
        $quizzes_q = $this->Quizzes->find('published');
        $quizzes_q->matching('Categories', function($q) use ($category_ids) {
            $q->where(['Categories.id IN' => $category_ids]);
            // TODO: Giuseppe vorrebbe che siano mostrati i quiz funjob e utente in box distinti
            return $q;
        });

        $quizzes    = $quizzes_q->all();
        $categories = [];

        // Crea array quizzes per categoria
        foreach ($quizzes as $quiz) {
            foreach ($quiz->categories as $ci => $category) {

                // TranslateBehavior crea _locale come key dell'array
                if (!is_numeric($ci)) {
                    continue;
                }

                if (empty($categories[ $category->id ])) {
                    $categories[ $category->id ]['category'] = $category;
                }

                $categories[ $category->id ]['quizzes'][] = $quiz;
            }
        }

        $this->set(compact('categories'));
        $this->set('_serialize', ['categories']);
    }

    /**
     * Archivio quiz (ultimi inseriti)
     */
    public function index()
    {
        $this->loadComponent('Paginator');

        $filterCategory = $this->request->getQuery('category');
        $filterTitle    = $this->request->getQuery('term');
        $filterType     = $this->request->getQuery('type');
        $sortMethod     = $this->request->getQuery('sort_by', 'created');
        
        if ($this->request->is('post')) {
            $_url = $this->_postToGet([
                'categories._ids' => 'category',
                'sort_by'         => 'sort_by',
                'term'            => 'term',
                'type'            => 'type',
                'do'              => 'do'
            ]);
            return $this->redirect($_url);
        }

        $q = $this->Quizzes->find('published');
        $q->contain([
            'Author'  => function($q) {
                $q->select(['id', 'username', 'avatar', 'is_bigbrain']);
                return $q;
            }
        ]);

        if ($sortMethod == 'rank') {
            $q->leftJoinWith('UserRankings', function($q) {
                $q->select(['UserRankings.quiz_id', '_avg' => $q->func()->avg('rank')]);
                return $q;
            });
            $q->group(['UserRankings.quiz_id']);
            $q->orderDesc('_avg');
        } if ($sortMethod == 'created') {
            $q->orderDesc('Quizzes.created');
        }

        // Filtro: nome & tags
        if (!empty($filterTitle)) {
            $q->find('searchByTerm', [
                'term' => $filterTitle,
                'tags' => true
            ]);
        }

        if (!empty($filterType)) {
            if ($filterType == 'bigbrain') {
                $q->matching('Author', function($q) {
                    $q->where(['is_bigbrain' => true]);
                    return $q;
                });
            } else {
                $q->bind(':quiztype', $filterType);
                $q->where(['Quizzes.type = :quiztype']);
            }
        }

        $q->group(['Quizzes.id']);
        
        $limit_for_client = $this->request->is('mobile') ? 5 : 16;
        $quizzes = $this->Paginator->paginate($q, ['limit' => $limit_for_client]);
        
        // $categories = $this->Quizzes->Categories->find('treeList', [
        //     'spacer' => ' '
        // ]);

        // $this->set(compact('quizzes', 'categories'));
        $this->set(compact('quizzes'));
    }

    private function _postToGet($convertFields = [])
    {
        $queryParameters = [];
        foreach ($convertFields as $key => $value) {
            $queryParameters[$value] = $this->request->getData($key);
        }

        return $queryParameters;
    }


    /**
     * Quiz popolari
     */
    protected function popular()
    {
        $this->loadComponent('Paginator');
        $q = $this->Quizzes->UserRankings->find('popular');

        $q->contain([
            'Quizzes.Author' => function($q) {
                return $q->select(['id', 'username', 'avatar']);
            }
        ]);

        // FUTURE?
        // Codice funzionante
        // $q->contain([
        //     'Quizzes.QuizSessions' => function($q) {
        //         $q->select([
        //             'QuizSessions.quiz_id',
        //             '_players' => $q->func()->count('quiz_id')
        //         ]);
        //         $q->group(['quiz_id']);
        //         return $q;
        //     }
        // ]);

        $populars = $this->Paginator->paginate($q);
        $this->set(compact('populars'));
    }

    /**
     * View method
     *
     * @param string|null $id Quiz id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($quizID)
    {
        $this->request->allowMethod(['GET', 'POST']);
        $UserReportForm = new QuizUserReportingForm();

        $q = $this->Quizzes->find();
        $q->where(['Quizzes.id' => (int) $quizID], ['Quizzes.id' => 'integer']);
        // $q->find('withUserRankingAVG');
        // $q->contain([
        //     'Author' => function($q) {
        //         return $q->select(['id', 'avatar', 'first_name', 'last_name', 'username', 'is_disabled', 'is_bigbrain']);
        //     },
        //     'QuizSessions' => function ($q) {
        //         return $q->where([
        //             'user_id'    => (int) $this->Auth->user('id'),
        //             'is_deleted' => false
        //         ]);
        //     },
        //     'QuizSessions.LevelsPassed' => function($q) {
        //         //$q->orderAsc('score');
        //         //$q->orderDesc('created');
        //         return $q;
        //     }
        // ]);
        
        // var_dump($quizID); die();
        
        $quiz = $q->firstOrFail();
        
        

        if ($quiz->is_disabled) {
            $this->Flash->error(__('Spiacenti! Gioco non esistente'));
            return $this->redirect($this->referer());
        }


        $QuizSessionForm = new \App\Form\QuizSessionStartForm();
        

        if ($this->request->is('post')) {
            $data = array_merge($this->request->data, ['_quiz' => $quiz, '_user' => $this->Auth->user()]);

            if ($QuizSessionForm->execute($data)) {
                $sessionPath = sprintf('Quiz.%d', $this->request->data('quiz_id'));

                // Reset Sessione di gicoo
                if ($this->request->getSession()->check($sessionPath)) {
                    $this->request->getSession()->delete($sessionPath);
                }

                $useAdv = (bool) $this->request->getData('_adv', true);
                if ($quiz->author->id == $this->Auth->user('id')) {
                    $useAdv = false;
                    $this->Flash->info(__('Spiacenti, per i tuoi quiz la funzionalità di guadagno PIX tramite pubblicità è disabilitata'));
                }

                $this->request->getSession()->write($sessionPath. '._adv', $useAdv);

                $route = [
                    '_name' => 'quiz:play',
                    'id'    => $quiz->id,
                    'title' => $quiz->slug,
                    'level' => (int) $this->request->data['level'],
                    'step'  => 1
                ];

                return $this->redirect($route);
            } else {
                $errors = $QuizSessionForm->getErrors();
                $errkey = array_shift($errors);
                $errmsg = array_shift($errkey);
                $this->Flash->error($errmsg);
            }
        }

        $this->set(compact('quiz', 'QuizSessionForm', 'UserReportForm'));
        $this->set('_serialize', ['quiz']);
    }

    /**
     * Gioco
     *
     * NOTA 1:
     *
     * Per verificare se l'utente ha risposto prima dello scadere del tempo
     * su richieste POST (quando invia la risposta) fare affidamento su
     * $this->request->data['_secs'] che invia un numero intero che rappresenta
     * quanti secondi sono passati dall'inizio del quiz.
     *
     * Questo perchè la risposta viene inviata dopo aver visualizzato la pubblicità
     * (e l'utente potrebbe aver visto il sito dello sponsor) percui potrebbe essere
     * maggiore di CakeSession::read('Quiz.X.N.expire_at').
     *
     *
     * @param  int $quizId
     * @param  int $step
     */
    public function play($quiz_id, $step = 1)
    {
        $this->viewBuilder()->setLayout('frontend--game');
        $quiz = $this->Quizzes->get($quiz_id);

        if ($quiz->is_disabled) {
            $this->Flash->error(__('Spiacenti! Gioco non esistente'));
            return $this->redirect($this->referer());
        }

        //$this->loadComponent('QuizGame', compact('quiz_id'));
        $this->QuizGame->play();

        $gameSessionPath = $this->QuizGame->getSessionPath();

        // Verifica sessione quiz
        if (!$this->request->getSession()->check($gameSessionPath)) {
            $this->Flash->error(__('Sessione di quiz scaduta (non esistente)'));
            $this->QuizGame->restart();
            return $this->redirect($quiz->url);
        }

        if ($this->QuizGame->isRefreshed()) {
            $this->Flash->error(__('Sessione di quiz scaduta (refresh)'));
            $this->request->getSession()->delete($gameSessionPath);
            return $this->redirect(['_name' => 'quiz:view', 'id' => $quiz_id, 'title' => $this->_quiz->slug]);
        }

        // Verifica validità quiz solo su GET
        // Se l'utente ha refreshato la pagina del quiz dopo la fine del conto alla rovescia
        if ($this->QuizGame->isExpired()) {
            $this->Flash->error(__('Sessione di quiz scaduta'));
            $this->QuizGame->restart();
            return $this->redirect($quiz->url);
        }

        $QuizSessionReply = new \App\Form\QuizSessionReplyForm();
        $this->set(compact('title', 'QuizSessionReply', 'gameSessionPath'));
        $this->set('_serialize', ['quiz', 'QuizSessionReply']);
    }

    /**
     * Risposte quiz durante gioco
     *
     * @param  int $quiz_id
     */
    public function reply($quiz_id)
    {
        $this->request->allowMethod('POST');
        $this->autoRender = false;

        // Viene utilizzato da QuizGameComponent, QuizSessionComponent, QuizScoreComponent
        // $this->request->data('quiz_id', $quiz_id);

        $QuizSessionReply = new \App\Form\QuizSessionReplyForm();
        if (!$QuizSessionReply->validate($this->request->getData())) {
            $errors = $QuizSessionReply->getErrors();
            $first  = array_shift($errors);
            $errmsg = $first[ key($first) ];

            $this->response->body(json_encode([
                'status'  => 'failure',
                'message' => __('Richiesta non valida'),
                'invalid' => $errmsg
            ]));
            return null;
        }

        $this->loadComponent('QuizGame');
        $this->QuizGame->config('quiz_id', $quiz_id);
        $this->QuizGame->play();

        $sessionPath = $this->QuizGame->getSessionPath();

        // Verifica sessione quiz e validità expire
        if (!$this->request->getSession()->check($sessionPath) || $this->QuizGame->isExpired()) {
            $this->QuizGame->restart();
            $this->response->body(json_encode([
                'status'  => 'failure',
                'message' => __('Sessione scaduta')
            ]));
            return null;
        }

        $this->QuizGame->replyCheck();
        return null;
    }

    public function save($quiz_id)
    {
        $this->loadComponent('QuizGame', ['quiz_id' => $quiz_id]);
        $quizSessionPath = $this->QuizGame->getSessionPath();

        $this->loadComponent('QuizSession', [
            'quizSessionPath' => $quizSessionPath
        ]);

        $this->loadComponent('QuizScore', [
            'quizSessionPath' => $quizSessionPath
        ]);

        if (!$this->QuizGame->isCompleted()) {
            $quiz = $this->Quizzes->get($quiz_id);
            return $this->redirect([
                '_name' => 'quiz:view',
                'id'    => $quiz->id,
                'title' => $quiz->slug,
            ]);
        }

        $data     = $this->request->getSession()->read($this->QuizGame->getSessionPath());
        $qsession = $this->QuizSession->store($data);

        $Hashids         = new \Hashids\Hashids('funjob_hashids_salt__923848237yc7a873124', 10);
        $qsessioncrypted = $Hashids->encode($qsession['qsessid'], $qsession['level']);

        $params = [
            'id'    => (int) $this->request->getSession()->read($this->QuizGame->getSessionPath('quiz.id')),
            'title' => Text::slug( $this->request->getSession()->read($this->QuizGame->getSessionPath('quiz.title')), '-'),
            // 'level' => $this->request->getSession()->read($this->QuizGame->getSessionPath('level'))
        ];

        $url = array_merge(['_name' => 'quiz:score', '?' => ['qsessid' => $qsessioncrypted]], $params);

        // FUTURE: In futuro cancellare sessione e far leggere i dati dal database (score)
        // Elimina sessione di gioco
        //$this->request->getSession()->delete($quizSessionPath);

        return $this->redirect(Router::url($url, true));
    }

    /**
     * Mostra all'utente risultato quiz appena svolto
     *
     * @param  int $quizID
     */
    public function score($quizID)
    {
        try {
            $quiz = $this->Quizzes->get($quizID, [
                'contain' => [
                    'UserRankings' => function($q) {
                        $q->where(['user_id' => $this->Auth->user('id')]);
                        return $q;
                    }
                ]
            ]);
        } catch(RecordNotFoundException $e) {
            $this->Flash->error(__('Sessione di gioco scaduta'));
            return $this->redirect(['_name' => 'quiz:index']);
        }

        if ($this->request->getQuery('qsessid')) {
            $Hashids = new \Hashids\Hashids('funjob_hashids_salt__923848237yc7a873124', 10);
            $qsessid = $Hashids->decode($this->request->getQuery('qsessid'));

            $this->loadModel('QuizSessions');

            $q = $this->QuizSessions->find();
            $q->bind(':sessid', $qsessid[0], 'integer');
            $q->where(['id = :sessid', 'user_id' => $this->Auth->user('id')]);
            $q->contain([
                'Levels' => function($q) use ($qsessid) {
                    $q->where(['id' => $qsessid[1]]);
                    return $q;
                }
            ]);
            try {
                $quizSession = $q->firstOrFail();
            } catch(RecordNotFoundException $e) {
                $this->Flash->error(__('Impossibile mostrare risultato, sessione di gioco scaduta'));
                return $this->redirect($quiz->url);
            }
        }

        $this->loadComponent('QuizGame');
        $this->QuizGame->config('quiz_id', $quizID);

        $QuizUserRankings = $this->Quizzes->UserRankings->newEntity();
        if (!empty($quiz->user_rankings[0])) {
            $QuizUserRankings = $quiz->user_rankings[0];
        }


        $quizSessionPath = $this->QuizGame->getSessionPath();
        $this->loadComponent('QuizScore');
        $this->QuizScore->config('quizSessionPath', $quizSessionPath);

        $replies     = $this->QuizScore->getReplies();
        $min_score   = Configure::readOrFail('app.quiz.minScoreRequired');
        $score       = $this->QuizScore->getScore(); // domande corrette (senza bonus)
        $score_extra = $this->QuizScore->getScoreWithBonus();
        $is_passed   = $this->QuizScore->isPassed();

        //$pix_earned = $this->QuizScore->getAuthorPix();
        $pix_earned = $this->QuizScore->getPlayerPix();

        $this->set(compact(
            'quiz', 'replies', 'session', 'pix_earned', 'quizSession', 'quizSessionPath', 'is_passed', 'corrects_n',
            'score', 'score_extra', 'min_score',

            'QuizUserRankings'
        ));
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $quiz       = $this->Quizzes->newEntity($this->request->getData(), ['associated' => ['Categories']]);

        if ($this->request->is('post')) {
            $this->request->data('user_id', $this->Auth->user('id'));

            // Trasformo categories._ids da stringa ad array
            // Viene inviato come campo di testo semplice (jstree)
            $this->request->data('categories._ids', explode(' ', $this->request->getData('categories._ids')));
            $quiz = $this->Quizzes->patchEntity($quiz, $this->request->getData(), ['associated' => ['Categories']]);

            if ($quiz->type == 'funjob') {
                $quiz->color = '#00adee';
            }

            if ($this->Quizzes->save($quiz)) {
                $this->Flash->success(__('Quiz creato: inserisci le domande prima di pubbliarlo'));
                return $this->redirect(['controller' => 'quiz_questions', 'action' => 'add', 0 => $quiz->id]);
            }
        }

        $colors = Configure::read('funjob.quizColors');
        $this->set(compact('quiz', 'colors'));
        $this->set('_serialize', ['quiz']);
    }

    /**
     * Quiz per categoria
     *
     * NOTE:
     * Non più utilizzata
     *
     * @param  integer $quziCategoryID
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function browse($quizCategoryID)
    {
        throw new ForbiddenException();

        $this->loadModel('QuizCategories');
        $this->loadComponent('Paginator');


        $Query = $this->Quizzes
            ->find('archive')
            ->find('byCategory', ['category_id' => $quizCategoryID]);

        // Filtra quiz in base a tipologia (quiz utenti+funjob o solo quiz funjob)
        if (
            isset($this->request->query['filter']['type']) &&
            in_array($this->request->query['filter']['type'], ['funjob'])
        ) {
            $this->request->data['filter']['type'] = $this->request->query['filter']['type'];
            $Query->find('byType', ['type' => $this->request->data['filter']['type']]);
        }

        $quizzes  = $this->Paginator->paginate($Query, ['limit' => 25]);
        $crumbs   = $this->QuizCategories->find('path', ['for' => $quizCategoryID]);
        $category = $crumbs->last();

        $this->set(compact('quizzes', 'crumbs', 'category'));
        $this->set('_serialize', ['quizzes']);
    }

    /**
     * Quiz per categoria
     *
     * @param  integer $quziCategoryID
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function search()
    {
        throw new ForbiddenException();

        if (!$this->request->is('ajax')) {
            throw new ForbiddenException();
        }

        if (empty($this->request->query['term'])) {
            throw new \Cake\Network\Exception\BadRequestException();
        }

        $Query = $this->Quizzes->find()->enableHydration(false);
        $term  = trim($this->request->query['term']);

        $Query->leftJoinWith('Tags');
        $Query->orWhere(['Quizzes.title LIKE' => '%'. $term .'%']);
        $Query->orWhere(['Tags.tag LIKE' => '%'. $term . '%']);

        // FIX: Sarebbe più corretto usare find('published'), ma non usa l'andWhere
        $Query->andWhere(['status' => 'published', 'is_hidden' => false]);

        $results = $Query->distinct('Quizzes.id')->limit(10)->select(['name' => 'title', 'id'])->all();

        $this->set(compact('results'));
        $this->set('_serialize', ['results']);

        $this->render();
    }

    /**
     * Edit method
     *
     * @param string|null $id Quiz id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $quiz = $this->Quizzes->get($id, [
            'contain' => ['Tags']
        ]);

        // Necessario per componente
        $this->loadComponent('QuizQuestion');
        $this->quiz = $quiz;
        $this->set('canPublish', $this->QuizQuestion->hasMinimiumQuestions());

        if ($this->Auth->user('type') !== 'admin' && $quiz->user_id != $this->Auth->user('id')) {
            throw new ForbiddenException(__('Accesso negato'));
        }

        if ($this->request->is(['patch', 'post', 'put'])) {

            $quiz = $this->Quizzes->patchEntity($quiz, $this->request->data);
            if ($this->Quizzes->save($quiz)) {
                $this->Flash->success(__('Quiz aggiornato'));

                return $this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Impossibile aggiornare, controlla che i dati inseriti siano corretti'));
            }
        }

        $colors = Configure::read('funjob.quizColors');
        $this->set(compact('quiz', 'categories', 'colors'));
        $this->set('_serialize', ['quiz']);
    }

    /**
     * Mostra dettaglio su visibilità quiz
     *
     * @param  int $id
     */
    public function status($id)
    {
        $Quiz = $this->Quizzes->get($id);

        // Necessario per componente
        $this->loadComponent('QuizQuestion');
        $this->quiz = $Quiz;
        $hasMinimiumQuestions = $this->QuizQuestion->hasMinimiumQuestions();
        $canPublish = $hasMinimiumQuestions;
        $this->set(compact('Quiz', 'canPublish', 'hasMinimiumQuestions'));
    }

}
