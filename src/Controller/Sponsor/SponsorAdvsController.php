<?php
namespace App\Controller\Sponsor;

use App\Controller\AppController;

use Cake\Core\Configure;
use Cake\Utility\Text;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;

use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\ForbiddenException;

Class SponsorAdvsController extends AppController {
    use MailerAwareTrait;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('SponsorAdvs');
    }

    public function getAdvertising() {
        return [];
    }

    public function index()
    {
        $this->loadComponent('Paginator');

        $q = $this->SponsorAdvs->find();
        $q->where(['SponsorAdvs.user_id' => $this->Auth->user('id')]);
        $q->order(['SponsorAdvs.id' => 'DESC']);

        $byStatus = $this->request->getQuery('status', 'all');
        if ($byStatus == 'active') {
            $q->find('isPublished');
            $q->find('isActive');
        } elseif ($byStatus == 'pending') {
            $q->where(['is_published' => false]);
        } elseif ($byStatus == 'expiring') {
            $q->find('isPublished');
            $q->where(function($exp) {
                return $exp->lte('impression_lefts', 150);
            });
        }

        $advertisings = $this->Paginator->paginate($q);
        $this->set(compact('advertisings'));
        $this->set('_serialize', ['advertisings']);
    }

    public function add()
    {
        $Adv = $this->SponsorAdvs->newEmptyEntity();

        if ($this->request->is('post')) {

            $this->setRequest($this->request
                ->withData('user_id', $this->Auth->user('id'))
                ->withData('uuid', Text::uuid())
            );

            $Package = TableRegistry::get('SponsorAdvPackages')->get($this->request->getData('package_id'));

            $this->request
                ->data('type', $Package->type)
                ->data('impression_lefts', $Package->impressions)
                ->data('amount', $Package->amount)
                ->data('is_published', 0);

            // HARDCODED: aggiunge un anno dalla data attuale
            $from = \Cake\I18n\Time::now();
            $to   = \Cake\I18n\Time::now();

            $this->setRequest($this->request
                ->withData('active_from', $from)
                ->withData('active_to', $to->modify('+4 years'))
            );

            $validator = 'uploadBanner';
            switch ($Package->type) {
                case 'banner'     : $validator = 'uploadBanner';     break;
                case 'banner-quiz': $validator = 'uploadBannerQuiz'; break;
            }


            $this->SponsorAdvs->patchEntity($Adv, $this->request->getData(), ['validate' => $validator]);

            if ($this->SponsorAdvs->save($Adv)) {
                // Imposta casuale
                $Hashids = new \Hashids\Hashids('funjob-package-salt23489u23874238', 8);
                $casual  = $Hashids->encode([$Adv->id]);
                $Adv->set('billing_casual', $casual);
                $this->SponsorAdvs->saveOrFail($Adv);

                $Adv->set('user', TableRegistry::get('Users')->findById($this->Auth->user('id'))->first());

                $this->getMailer('SponsorAdv')->send('newOrderAdminNotification', [$Adv]);

                $this->Flash->success(__('Annuncio creato, procedi al pagamento tramite "Paypal" o  "Bonifico"'));
                return $this->redirect([
                    'controller' => 'SponsorAdvs',
                    'action'     => 'index',
                    '?' => [
                        'status'    => 'pending',
                        'sort'      => 'id',
                        'direction' => 'desc'
                    ]
                ]);
            }
        }

        $AccountInfo     = $this->__fetchAccountInfo();
        $SponsorPackages = TableRegistry::get('SponsorAdvPackages');
        $packages        = $SponsorPackages->find('selectOptions');

        $this->set(compact('Adv', 'packages', 'AccountInfo'));
    }

    public function view($id)
    {
        $filterMonth = $this->request->getQuery('period', date('Y-m'));
        $Adv         = $this->SponsorAdvs->find()->where(['id' => (int) $id], ['id' => 'integer'])->firstOrFail();

        if ($this->Auth->user('type') != 'admin' && $Adv->user_id != $this->Auth->user('id')) {
            throw new ForbiddenException(__('Permesso negato'));
        } elseif ($Adv->is_published == false) {
            throw new ForbiddenException(
                __('Permesso negato: {reason}', ['reason' => __('Campagna inattiva')]
            ));
        }


        // Filtro per mese (mobile)
        if ($this->request->is('post')) {
            if ($this->request->getData('period')) {
                $period = $this->request->getData('period');
                if (!empty($period['year']) && !empty($period['month'])) {
                    $filterMonth = $period['year'] .'-'. $period['month'];
                    return $this->redirect([$id, '?' => ['period' => $filterMonth]]);
                }
            }
        }

        $views    = $this->_fetchViews($Adv->id, $filterMonth);
        $clicks   = $this->_fetchClicks($Adv->id, $filterMonth);
        $calendar = new \Cake\Collection\Collection($this->_prepareCalendarDays($filterMonth));

        $this->set(compact('Adv', 'views', 'clicks', 'calendar', 'filterMonth'));
    }

    private function _prepareCalendarDays($yearAndMonth)
    {
        $Dt = \DateTime::createFromFormat('Y-m', $yearAndMonth);
        if (!$Dt) {
            throw new \Exception('Formato data non valido');
        }

        $lastDay  = $Dt->format('t');
        $days     = [];

        for ($i=1; $i < $lastDay; $i++) {
            $days[] = $Dt->format('Y-m') .'-'. str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        return $days;
    }

    private function _fetchClicks($adv_id, $filterMonth)
    {
        $q = $this->SponsorAdvs->Clicks->findBySponsorAdvId($adv_id);
        $q->enableHydration(false);
        $q->find('byMonth', ['month' => $filterMonth]);

        $createdFormat = $q->func()->date_format([
            'created'    => 'literal',
            '"%Y-%m-%d"' => 'literal'
        ]);
        $q->select([
            'dateFormatted' => $createdFormat,
            'count'         => $q->func()->count('*')
        ]);

        $q->group(['dateFormatted']);
        $clicks = $q->all();

        $clicksPerDay = $clicks->combine(
            function($dailyView) { return $dailyView['dateFormatted']; },
            function($dailyView) { return $dailyView; }
        );

        return $clicksPerDay->toArray();
    }

    private function _fetchViews($adv_id, $filterMonth)
    {
        $q = $this->SponsorAdvs->Views->findByAdvId($adv_id);
        $q->find('byMonth', ['month' => $filterMonth]);
        $q->select(['id', 'day', 'views']);
        $q->group(['day']);
        $q->orderDesc('day');
        $views = $q->all();

        $viewsPerDay = $views->combine(
            function($dailyView) { return $dailyView->day->format('Y-m-d'); },
            function($dailyView) { return $dailyView; }
        );

        return $viewsPerDay->toArray();
    }

    private function __deleteMe($Adv) {
        $Adv->_stats      = new \Cake\Collection\Collection();
        $CollectionViews  = new \Cake\Collection\Collection($Adv->views);
        $CollectionClicks = new \Cake\Collection\Collection($Adv->clicks);

        $viewsForDays = $CollectionViews->combine(
            'id',
            function ($item) { return $item; },
            function ($item) { return $item->day->format('Y-m-d'); }
        );
        $clicksForDays = $CollectionViews->combine(
            'id',
            function ($item) { return $item; },
            function ($item) { return $item->created->format('Y-m-d'); }
        );
    }


    /**
     * Parsing indirizzo da formato GooglePlaces
     *
     * @param  str $address
     * @return array
     */
    private function _parseAddress($address)
    {
        if (empty($address)) {
            return [];
        }

        list($city, $district, $cap) = explode(',', $address);
        $result = compact('city', 'district', 'cap');
        return array_map('trim', $result);
    }

    private function __fetchAccountInfo()
    {
        $accountData = [];
        $userAuth    = $this->viewBuilder()->getVars('UserAuth');
        $accountData = array_fill_keys(['name', 'phone', 'address', 'city', 'district', 'cap'], null);

        // AccountInfos
        if (in_array($this->Auth->user('type'), ['admin', 'user'])) {
            $AccountModel = TableRegistry::get('UserFields');
            $AccountInfo  = $AccountModel->find()->where(['user_id' => $this->Auth->user('id')])->first();

            $accountData['name']  = $userAuth['first_name'] .' '. $userAuth['last_name'];
            $accountData['email'] = $userAuth['email'];

            if (!empty($AccountInfo)) {
                $accountData['phone']   = $AccountInfo->phone;
                $accountData['address'] = $AccountInfo->address;
                $accountData            = array_merge($accountData, $this->_parseAddress($AccountInfo->live_city));
            }
        } else {
            $AccountModel = TableRegistry::get('CompanyFields');
            $AccountInfo  = $AccountModel->find()->where(['user_id' => $this->Auth->user('id')])->first();

            $accountData['name']  = $userAuth['name'];
            $accountData['email'] = $userAuth['email'];

            if (!empty($AccountInfo)) {
                $accountData['phone']   = $AccountInfo->phone;
                $accountData['address'] = $AccountInfo->address;
                $accountData            = array_merge($accountData, $this->_parseAddress($AccountInfo->city));
            }
        }

        return $accountData;
    }

}
