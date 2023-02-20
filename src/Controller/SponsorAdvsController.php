<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Log\Log;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;


/**
 * SponsorAdvs Controller
 *
 * @property \App\Model\Table\SponsorAdvsTable $SponsorAdvs
 */
class SponsorAdvsController extends AppController
{
    const QUIZ_ADV_TYPE = 'banner-quiz';

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');

        $this->Auth->allow(['image', 'index', 'track']);
        //$this->eventManager()->off($this->Csrf);
    }

    /**
     * Lista delle pubblicità attive
     * Utilizzato esclusivamente nel quiz
     */
    public function index()
    {
        $this->loadComponent('Advertising');
        $_advs = $this->Advertising->get(1, self::QUIZ_ADV_TYPE);
        $_adv  = $_advs->first();

        if (empty($_adv)) {
            $this->response->header('X-Sponsor', 'None');
            throw new NotFoundException();
        }

        // Trasformo oggetto in array
        $adv = [
            'id'    => $_adv->id,
            'title' => $_adv->title,
            'href'  => $_adv->href,
            'track'    => [
                'image' => $_adv->image_track_src,
                'click' => $_adv->href_track_src,
            ]
        ];

        $this->set(compact('adv'));
        $this->set('_serialize', ['adv']);
    }

    /**
     * Renderizza immagine associata all'annuncio
     *
     * Viene utiulizzata da tutte le pagina attraverso un <img src=..../>
     *
     * @param  int $adv_id
     * @return \Cake\
     */
    public function image($adv_uuid)
    {
        $adv = $this->SponsorAdvs->findByUuid($adv_uuid)->first();

        if (empty($adv)) {
            throw new NotFoundException();
        }

        // TODO:
        // Sostituirla con $this->SponsorAdvs->Views->findOrCreate([])
        // Salva impressione su `sponsor_adv_views`
        $ViewsDay = $this->SponsorAdvs->Views
            ->find('byDate', [
                'date'   => date('Y-m-d'),
                'adv_id' => $adv->id
            ])->first();
        if (!$ViewsDay) {
            $ViewsDay = $this->SponsorAdvs->Views->newEntity([
                'adv_id' => $adv->id,
                'day'    => date('Y-m-d'),
                'views'  => 0,
            ]);
            $this->SponsorAdvs->Views->save($ViewsDay);
        }

        $this->viewBuilder()->enableAutoLayout(false);

        //if (strpos($adv->banner__img, 'http') === 0) {
        //    // remote file
        //    $src = $adv->banner__img;
        //} else {
            $thumb = $adv->banner__img;
            $image = $adv->imageSize($thumb, '400x400');
            $src  = \Cake\Routing\Router::url($adv->banner__dir .DS. $image, true);
        //}

        //Log::debug(sprintf('MaximumMemories::image %s', $src));
        $arrContextOptions = [
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];


        $src = file_get_contents($src, false, stream_context_create($arrContextOptions));
        if (!$src) {
            throw new \RunTimeException();
        }

        list($filename, $ext) = explode('/', $adv->banner__mime);

        $this->response->cache('-1 minute', '+1 days');
        $this->response->type($ext);
        $this->response->body($src);

        try {
            $adv->impression_lefts -= 1;
            $ViewsDay->views       += 1;
            $adv->views = [$ViewsDay];

            $this->SponsorAdvs->saveOrFail($adv, [
                'associated' => ['Views']
            ]);

            Log::info(
                __('Impressione su {adv} : {url}', [
                    'adv' => $adv->id,
                    'url' => $this->referer($this->request->getAttribute('here')),
                ]), ['scope' => ['adv']]
            );
        } catch (Exception $e) {
            Log::critical($e->getMessage());
        }

        return $this->response;
    }


    /**
     * Traccia click pubblicitario
     *
     * @param  int $uuid
     */
    public function track($uuid)
    {
        $Adv = $this->SponsorAdvs->findByUuid($uuid)->first();
        if (!$Adv) {
            throw new \NotFoundException();
        }

        $this->autoRender = false;

        $url = parse_url($Adv->href);

        if (empty($url['query'])) {
            $url['query'] = '';
        }

        // Aggiungo codice tracking google Analytics
        $url['query'] .= '&campaign=funjob&term=test';

        $url = sprintf(
            'Location: %s://%s%s%s%s',
            $url['scheme'],
            $url['host'],
            !empty($url['path'])     ? $url['path'] : '/',
            !empty($url['query'])    ? '?' . $url['query']    : '',
            !empty($url['fragment']) ? '#' . $url['fragment'] : ''
        );

        $this->loadModel('SponsorAdvClicks');
        $AdvClick = $this->SponsorAdvClicks->newEntity([
            'sponsor_adv_id' => $Adv->id,
            'user_id'        => $this->Auth->user('id'),
            'ip'             => $this->request->clientIp(),
            'href'           => $url
        ]);

        $this->SponsorAdvClicks->save($AdvClick);
        Log::info(
            __('click su pubblicità {adv} {url}', [
                'adv' => $Adv->id,
                'url' => $this->referer($this->request->getAttribute('here')),
            ]), ['scope' => ['adv']]
        );

        $this->response->header($url);
    }

    public function viewed() {
        $this->request->allowMethod('POST');

        if (!$this->request->is('json')) {
            throw new ForbiddenException();
        }

        if (!$this->request->is('ajax')) {
            throw new ForbiddenException();
        }

        if (!$this->request->getData('quiz_id')) {
            throw new BadRequestException('Quiz id missing');
        }

        $quizSessionPath = sprintf('Quiz.%d', $this->request->getData('quiz_id'));
        if (!$this->request->getSession()->check($quizSessionPath)) {
            throw new ForbiddenException();
        }

        if ($this->request->getSession()->read($quizSessionPath. '._adv') === false) {
            return null;
        }

        if (!$this->request->getSession()->check($quizSessionPath . '._advViewed')) {
            $this->request->getSession()->write($quizSessionPath . '._advViewed', 0);
        }

        $count = $this->request->getSession()->read($quizSessionPath . '._advViewed');
        $count++;

        if ($count > 10) {
            throw new ForbiddenException('Quiz adv counter > 10');
        }

        $this->request->getSession()->write($quizSessionPath . '._advViewed', $count);
    }
}
