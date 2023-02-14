<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Cache\Cache;
use Hiryu85\Traits\LorempixelTrait;

class TestsController extends AppController
{
    use LorempixelTrait;

    public $autoRender = false;

    /**
     * Crea cache
    */
    public function cache()
    {
        Cache::remember('666', function() {
            return 666;
        }, 'user_credits');
        Cache::remember('667', function() {
            return 667;
        }, 'user_credits');

        debug('OK');
    }

    /**
     * Cancella i due file caches creati
     */
    public function cacheDelete()
    {
        $result = Cache::deleteMany([666, 667], 'user_credits');
        debug($result);
    }

    public function lorem() {
        dd( $this->getLoremImage(['w' => 300, 'h' => 300, 'c' => 'sports', 't' => 'ciao mirko']) );
    }

    public function htmlpurifier()
    {
        $html = '<iframe width="560" height="315" src="https://www.youtube.com/embed/BI9fP8Aq8Ys" frameborder="0" allowfullscreen></iframe><p>Ciao</p>';

        $config     = \HTMLPurifier_Config::createDefault();
        // $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true]);
        // $config->set('URI.DisableResources', true);
        // $config->set('HTML.AllowedElements', ['iframe']);
        // $config->set('HTML.SafeIframe', true);
        // $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
        // //$config->set('URI.SafeIframeRegexp', '/^https?://(www.youtube.com/embed/|player.vimeo.com/video/)/');

        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'); //allow YouTube and Vimeo
        // This line is important allow iframe in allowed elements or it will not work
        $config->set('HTML.AllowedElements', array('iframe'));// <-- IMPORTANT
        $config->set('HTML.AllowedAttributes','iframe@src,iframe@allowfullscreen');
        $config->set('Core.RemoveProcessingInstructions', true);

        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('iframe', 'allowfullscreen', 'Bool');


        $purifier   = new \HTMLPurifier($config);
        $clean_html = $purifier->purify($html);
        dd($clean_html);
    }


    public function countdown()
    {
        $start = time();
        $end   = time() + (60 * 1000);

        $this->set(compact('start', 'end'));
        $this->render('countdown');
    }
}
