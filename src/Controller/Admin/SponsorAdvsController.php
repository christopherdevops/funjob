<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Form\SponsorAdvAdminSearchForm;

use Cake\Mailer\MailerAwareTrait;

/**
 * SponsorAdvs Controller
 *
 * @property \App\Model\Table\SponsorAdvsTable $SponsorAdvs
 *
 * @method \App\Model\Entity\SponsorAdv[] paginate($object = null, array $settings = [])
 */
class SponsorAdvsController extends AppController
{

    use MailerAwareTrait;

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $isSearch   = $this->request->getQuery('filter', false);
        $SearchForm = new SponsorAdvAdminSearchForm();
        $q          = $this->SponsorAdvs->find();

        // POST to GET
        if ($this->request->is('post')) {
            if ($SearchForm->validate($this->request->getData())) {
                return $this->redirect($this->request->getData());
            }
        } elseif ($isSearch) {
            $q = $this->_archiveFilters($q);
        }

        $q->contain([
            'Users' => function($q) {
                $q->select(['id', 'username']);
                return $q;
            }
        ]);
        $q->order(['SponsorAdvs.id' => 'DESC']);

        $advertisings = $this->paginate($q);
        $this->set(compact('advertisings', 'SearchForm'));
        $this->set('_serialize', ['advertisings']);
    }

    public function edit($id)
    {
        $SponsorAdv = $this->SponsorAdvs->get($id);

        if ($this->request->is('PUT')) {
            $SponsorAdv = $this->SponsorAdvs->patchEntity($SponsorAdv, $this->request->getData(), [
                'fields' => ['title', 'descr', 'active_from', 'active_to', 'impression_lefts', 'amount']
            ]);

            if ($this->SponsorAdvs->save($SponsorAdv)) {
                $this->Flash->success(__('Pubblicità modificata'));
                return $this->redirect($this->referer());
            }
        }

        $SponsorAdv = $this->SponsorAdvs->get($id);
        $this->set(compact('SponsorAdv'));
    }

    /**
     * Filtra le pubblicità in base ai filtri specificati
     *
     * @param  Query $q
     * @return Query
     */
    protected function _archiveFilters($q)
    {

        $filterByStatus = $this->request->getQuery('status', 'all');
        if ($filterByStatus == 'active') {
            $q->find('isPublished');
            $q->find('isActive');
        } elseif ($filterByStatus == 'pending') {
            $q->where(['is_published' => false]);
        } elseif ($filterByStatus == 'expiring') {
            $q->find('isPublished');
            $q->where(function($exp) {
                return $exp->lte('impression_lefts', 150);
            });
        }

        if ($this->request->getQuery('title')) {
            $q->bind(':sponsor_title', trim($this->request->getQuery('title')), 'string');
            $q->where(['MATCH(SponsorAdvs.title) AGAINST(:sponsor_title)'], ['SponsorAdvs.title' => 'string']);
        }

        if ($this->request->getQuery('billing_casual')) {
            $casual = $this->request->getQuery('billing_casual');
            if (strpos($casual, 'FUNJOB-ADV-') === FALSE) {
                $casual = 'FUNJOB-ADV-' . $casual;
            }

            $q->bind(':sponsor_billing_casual', $casual, 'string');
            $q->where(['SponsorAdvs.billing_casual = :sponsor_billing_casual'], ['SponsorAdvs.billing_casual' => 'string']);
        }

        if ($this->request->getQuery('uuid')) {
            $q->where(['uuid' => $this->request->getQuery('uuid')], ['uuid' => 'string']);
        }

        if ($this->request->getQuery('banner_type')) {
            $q->where(['SponsorAdvs.type' => $this->request->getQuery('banner_type')], ['type' => 'string']);
        }

        return $q;
    }

    /**
     * Publish method
     *
     * @param string|null $id Sponsor Adv id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function publish($id = null)
    {
        $q = $this->SponsorAdvs->findById($id);
        $q->contain(['Users']);

        $SponsorAdv = $q->firstOrFail();
        $SponsorAdv->is_published = true;

        if ($this->SponsorAdvs->save($SponsorAdv)) {
            $this->getMailer('SponsorAdv')->send('customerNotificationAfterPublish', [$SponsorAdv]);
            $this->Flash->success(
                __d('backend',  'Annuncio pubblicato: {mail} è stato avvisato.', ['mail' => $SponsorAdv->user->email])
            );
        }

        return $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * Publish method
     *
     * @param string|null $id Sponsor Adv id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function unpublish($id = null)
    {
        $SponsorAdv = $this->SponsorAdvs->findById($id)->firstOrFail();
        $SponsorAdv->is_published = false;

        if ($this->SponsorAdvs->save($SponsorAdv)) {
            $this->Flash->success(__d('backend', 'Annuncio rimosso da coda pubblicazione'));
        }

        return $this->redirect($this->referer(['action' => 'index']));
    }

}
