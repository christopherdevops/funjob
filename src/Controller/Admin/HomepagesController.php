<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

use Cake\Core\Configure;
use Cake\Cache\Cache;

/**
 * Homepages Controller
 *
 * @property \App\Model\Table\HomepagesTable $Homepages
 *
 * @method \App\Model\Entity\Homepage[] paginate($object = null, array $settings = [])
 */
class HomepagesController extends AppController
{

    public $modelClass = 'HomepageSettings';

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function index() {
        $this->loadModel('HomepageSettings');

        $HomepageSettings = $this->HomepageSettings->findOrCreate([], function($entity) {
            $entity->foreground_video_embed  = null;
            $entity->foreground_video2_embed = null;
            return $entity;
        });

        $this->set(compact('HomepageSettings'));
        return $this->render('edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Homepage id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id)
    {
        $HomepageSettings = $this->HomepageSettings->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $HomepageSettings = $this->HomepageSettings->patchEntity($HomepageSettings, $this->request->getData());
            if ($this->HomepageSettings->save($HomepageSettings)) {
                $this->Flash->success(__d('backend', 'Configurazione homepage salvata'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__d('backend', 'The homepage could not be saved. Please, try again.'));
        }
        $this->set(compact('HomepageSettings'));
        $this->set('_serialize', ['HomepageSettings']);
    }

    /**
     * Categorie popolari su homepage
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function popularCategories()
    {
        $this->loadModel('HomepagePopularCategories');
        $this->request->allowMethod(['GET', 'POST', 'PUT']);

        if ($this->request->is('post', 'put')) {
            $categories = (array) $this->request->getData('category_id');
            $saved      = $this->HomepagePopularCategories->getConnection()->transactional(function () use ($categories) {
                $saved = true;
                $this->HomepagePopularCategories->deleteAll([]);

                foreach ($categories as $category_id) {
                    $entity = $this->HomepagePopularCategories->newEntity(['category_id' => $category_id]);
                    if (!$this->HomepagePopularCategories->save($entity)) {
                        $saved = false;
                        break;
                    }
                }

                return $saved;
            });

            if ($saved) {
                $caches = [];
                foreach (Configure::read('app.languages') as $locale => $value) {
                    $caches[] = 'home_popular_categories__' . $locale;
                }

                Cache::deleteMany($caches, 'home_popular_categories');

                $this->Flash->success(__d('backend', 'Categorie popolari aggiorante'));
                return $this->redirect($this->referer());
            }
        }

        $this->loadModel('QuizCategories');
        $categories         = $this->QuizCategories->find('treeList', ['spacer' => ' ']);
        $categoriesSelected = $this->HomepagePopularCategories->find('list')->hydrate(false)->toArray();

        $this->set(compact('categories', 'categoriesSelected'));
    }

    /**
     * Quizzes popolari su homepage
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function popularQuizzes()
    {
        $this->loadModel('HomepagePopularQuizzes');
        $this->loadComponent('Paginator');

        $q              = $this->HomepagePopularQuizzes->find('popular');
        $popularQuizzes = $this->Paginator->paginate($q);

        $this->set(compact('popularQuizzes'));
    }

    /**
     * Elimina quiz da home
     *
     * @param  int
     */
    public function popularQuizzesDelete($id)
    {
        $this->request->allowMethod(['POST', 'PUT']);

        $this->loadModel('HomepagePopularQuizzes');
        $entity = $this->HomepagePopularQuizzes->get($id);

        if ($this->HomepagePopularQuizzes->delete($entity)) {
            $this->Flash->success(__d('backend', 'Eliminato dalla prima pagina'));
            Cache::delete('home_popular_quizzes', 'home_popular_quizzes');
        } else {
            $this->Flash->error(__d('backend', 'Non eliminato'));
        }


        return $this->redirect($this->referer());
    }

    public function popularQuizzesAppend($quiz_id)
    {
        $this->loadModel('HomepagePopularQuizzes');

        // Se già esistente
        $entity = $this->HomepagePopularQuizzes->findByQuizId($quiz_id)->first();
        if (!empty($entity)) {
            $this->Flash->success(__d('backend', 'Aggiunto in prima pagina'));
            return $this->redirect($this->referer());
        }

        // Verifica su status quiz
        $entity = $this->HomepagePopularQuizzes->Quizzes->get($quiz_id);
        if ($entity->status != 'published') {
            $this->Flash->error(__d('backend', 'Non è ancora stato pubblicato'));
            return $this->redirect($this->referer());
        }
        if ($entity->is_disabled) {
            $this->Flash->error(__d('backend', 'Disabilitato dall\'amministratore'));
            return $this->redirect($this->referer());
        }

        $entity = $this->HomepagePopularQuizzes->newEntity(['quiz_id' => $quiz_id]);
        if ($this->HomepagePopularQuizzes->save($entity)) {
            $this->Flash->success(__d('backend', 'Aggiunto'));
            Cache::delete('home_popular_quizzes', 'home_popular_quizzes');
        } else {
            $this->Flash->error(__d('backend', 'Non elimianto'));
        }


        return $this->redirect($this->referer());
    }

}
