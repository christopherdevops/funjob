<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;

/**
 * HomeVideosForeground cell
 */
class HomeVideosForegroundCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    protected function _getSettings()
    {
        $this->loadModel('HomepageSettings');

        $HomepageSettings = Cache::remember('home_foreground_videos', function() {
            return $this->HomepageSettings->findOrCreate(
                [],
                function($entity) {
                    $entity->foreground_video_embed  = null;
                    $entity->foreground_video2_embed = null;
                    return $entity;
                }
            );
        }, 'xshort');


        return $HomepageSettings;
    }

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
        $settings = $this->_getSettings();
        $videos   = [];

        if ($settings['foreground_video_embed']) {
            $videos[] = [
                'title' => $settings['foreground_video_title'],
                'embed' => $settings['foreground_video_embed'],
                'href'  => $settings['foreground_video_href']
            ];
        }

        if ($settings['foreground_video2_embed']) {
            $videos[] = [
                'title' => $settings['foreground_video2_title'],
                'embed' => $settings['foreground_video2_embed'],
                'href'  => $settings['foreground_video2_href']
            ];
        }

        $this->set(compact('videos'));
    }
}
