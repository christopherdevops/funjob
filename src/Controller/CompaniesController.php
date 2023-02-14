<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;

/**
 * Companies Controller
 *
 * @property \App\Model\Table\CompaniesTable $Companies
 */
class CompaniesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index', 'view']);
    }

    /**
     * Archivio aziende
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->loadModel('Companies');

        $filterName = $this->request->getQuery('name');
        $filterCat  = $this->request->getQuery('category');
        $filterCity = $this->request->getQuery('city');

        $isSearch   = $this->request->getQuery('search', false);

        $q = $this->Companies->find('company');
        $q->select(['id', 'username', 'avatar', 'title']);
        $q->find('categories');
        $q->find('withAccountInfo');

        if ($isSearch !== false) {
            if ($filterName) {
                $q->find('searchCompanyByName', ['name' => $filterName, 'exact' => false]);
            }

            if ($filterCat) {
                $ids = $filterCat;
                $q->find('filterByCategory', ['category_ids' => $ids]);
            }

            if ($filterCity) {
                $filterCity = array_keys($filterCity);
                $q->find('filterByCity', ['city_id' => $filterCity]);
            }
        }

        $companies  = $this->paginate($q);
        $categories = $this->Companies->Categories->find('treeList', ['spacer' => ' ']);

        $this->set(compact('companies', 'isSearch', 'categories'));
        $this->set('_serialize', ['companies']);
    }


    /**
     * Profilo utente (pubblico)
     *
     * @param  int $id ID utente
     */
    public function view($id = null)
    {
        if (empty($id)) {
            throw new \Cake\Network\Exception\BadRequestException();
        }

        $User = $this->Companies->get($id, [
            'contain' => [
                'Categories',
                'AccountInfos',
                'ProfileBlocks',
                'Friends' => function($q) {
                    $q->where(['is_accepted' => true]);
                    return $q->limit(9);
                },
                'Friends.Users' => function($q) {
                    $q->select(['id', 'username', 'avatar']);
                    return $q;
                }
            ]
        ]);

        if ($User->is_disabled) {
            throw new NotFoundException(__('Questo utente non esiste'));
        }

        // Amicizie
        if ($this->Auth->user()) {
            $user_id  = $this->Auth->user('id');
            $isFriend = null;

            if ($user_id != $id) {
                $isFriend = $this->Companies->Friends
                    ->find('isFriendWith', [
                        'user_id' => $this->Auth->user('id'), 'friend_id' => $id
                    ])
                    ->autoFields(false)
                    ->first();
            }
        }

        $this->set(compact('User', 'isFriend'));
        $this->set('_serialize', ['User']);
    }

}
