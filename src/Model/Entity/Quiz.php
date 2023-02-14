<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Collection\Collection;

use Hiryu85\Traits\UploadImageTrait;

/**
 * Quiz Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\QuizQuestion[] $quiz_questions
 * @property \App\Model\Entity\QuizSession[] $quiz_sessions
 */
class Quiz extends Entity
{
    use UploadImageTrait;

    // Add the corresponding virtual field to the model
    protected $_virtual = ['category_ids', 'tag_ids'];

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*'            => true,
        'id'           => false,

        // Solo admin
        'is_disabled'  => false,

        // Tags in formato stringa
        'tag_string'   => true,
    ];


    protected function _getSlug()
    {
        return \Cake\Utility\Text::slug($this->title, '-');
    }


    /**
     * Quiz tags in formato csv
     *
     * @return str
     */
    protected function _getTagString()
    {
        if (isset($this->_properties['tag_string'])) {
            return $this->_properties['tag_string'];
        }

        if (empty($this->tags)) {
            return '';
        }

        $tags = new Collection($this->tags);
        $str = $tags->reduce(function ($string, $tag) {
            return $string . $tag->tag . ', ';
        }, '');

        return trim($str, ', ');
    }

    /**
     * Crea array di IDs di categorie associate a QuizEntity
     *
     * Utile per FormHelper::select
     *
     * @return array
     */
    protected function _getTagIds()
    {
        if (empty($this->_properties['tags'])) {
            return [];
        }

        $ids = [];

        foreach ($this->_properties['tags'] as $tag) {
            $ids[ $tag->id ] = $tag->tag;
        }

        return $ids;
    }

    /**
     * Cover quiz
     *
     * @return str
     */

    protected function _getImageSrc()
    {
        $src = $this->_properties['image__dir'] .DS. $this->_properties['image__src'];
        return $src;
    }

    protected function _getCover()
    {
        return $this->_getCoverSrc();
    }

    protected function _getCover_1400x800()
    {
        return $this->_getCoverSrc(1400, 800);
    }

    protected function _getCover_300x150()
    {
        return $this->_getCoverSrc(300, 150);
    }

    protected function _getCover_500x300()
    {
        return $this->_getCoverSrc(500, 300);
    }

    private function _getCoverSrc($w = 500, $h = 300)
    {
        $_default = 'img/default-quiz-cover.png';

        // Default image
        if (empty($this->_properties['image__src'])) {
            return $this->imageSize($_default, $w.'x'.$h);
        }

        // File non esistente
        $src = $this->_properties['image__dir'] .DS. $this->_properties['image__src'];
        if (!file_exists(ROOT .DS. $src)) {
            return $this->imageSize($_default, $w.'x'.$h);
        }

        return $this->imageSize($src, $w.'x'.$h);
    }

    protected function _getCoverSrcOriginal()
    {
        $_src       = 'holder.js/:wx:h?&bg=:color&fg=ffffff&auto=yes&text=:alt';
        $_srcParams = [
            'w'     => 500,
            'h'     => 300,
            'color' => substr($this->_properties['color'], 1),
            'alt'   => \Cake\Utility\Text::truncate($this->_properties['title'], 20, ['exact' => true])
        ];

        // Immagine da placeholder.it
        if (empty($this->_properties['image__src'])) {
            return \Cake\Utility\Text::insert($_src, $_srcParams);
        }

        $src = $this->_properties['image__dir'] .DS. $this->_properties['image__src'];
        if (!file_exists(ROOT .DS. $src)) {
            return \Cake\Utility\Text::insert($_src, $_srcParams);
        }

        $_srcParams += pathinfo($src);

        return \Cake\Utility\Text::insert(':dirname/:filename.:extension', $_srcParams);
    }

    /**
     * Url a dettaglio quiz
     */
    protected function _getUrl()
    {

        if (empty($this->_properties['title']))
        {
            throw new \RuntimeException(__('Quiz::title proprietà richiesta'));
        }

        if (empty($this->_properties['id']))
        {
            throw new \RuntimeException(__('Quiz::id proprietà richiesta'));
        }

        return \Cake\Routing\Router::url([
            '_name' => 'quiz:view',
            'id'    => $this->_properties['id'],
            'title' => $this->_getSlug()
        ]);
    }

    protected function _getColor()
    {
        // Colore standard: funjob
        // if (!empty($this->_properties['type']) && $this->_properties['type'] == 'funjob') {
        //     return '#00adee';
        // }

        if (!empty($this->_properties['color'])) {
            return $this->_properties['color'];
        }

        return '#cc3333';
    }


    /**
     * Descrizione breve quiz
     *
     * @return str
     */
    protected function _getDescrSmall()
    {
        if (!isset($this->descr)) {
            return "";
        }

        return \Cake\Utility\Text::truncate(h($this->descr), 150);
    }

    /**
     * Rimuove tutti gli elementi ad eccezione del <iframe>
     *
     * @param str
     */
    protected function _setVideoEmbed($html)
    {
        if (empty($html)) {
            return null;
        }

        $html = strip_tags($html, '<iframe><video>');
        $doc  = new \DOMDocument();

        $html = $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        // In questo modo non crea Doctype e head/body
        //$html = $doc->loadXML($html);

        $iframes = $doc->getElementsByTagName('iframe');
        if (sizeof($iframes) > 1) {
            return null;
        }

        $iframe = $iframes[0];
        if (!$iframe) {
            return null;
        }

        foreach (['width', 'height'] as $attribute) {
            if ($iframe->hasAttribute($attribute)) {
                $iframe->setAttribute($attribute, '100%');
            }
        }


        if ($iframe->hasAttribute('class')) {
            $classContent = $iframe->getAttribute('class');
            if (strpos($classContent, 'funjob-video-embed-iframe') === FALSE) {
                $iframe->setAttribute('class', $iframe->getAttribute('class') . ' funjob-video-embed-iframe');
            }
        } else {
            $iframe->setAttribute('class', 'funjob-video-embed-iframe');
        }

        return $doc->saveHTML();
    }

    /**
     * Restituisce se il quiz è visibile dal frontend
     *
     * @return bool
     */
    protected function _getVisibleInFrontend()
    {
        return $this->_properties['status'] == 'published' && $this->_properties['is_disabled'] == false;
    }

}
