<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

use Cake\View\StringTemplateTrait;

/**
 * User helper
 */
class UserHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'avatarImage' => '<img {{attrs}} src="{{src}}" />'
        ]
    ];

    /**
     * Restituisce l'avatar dell'utente
     *
     * @param  str $src            Filename immagine
     * @param  array $attrs        attributi <img />
     * @param  array $templateVars templateVars
     * @return str
     */
    public function avatar($src, $attrs = [], $templateVars = [])
    {
        $_defaultAttrs = [
            'class' => 'img-circle',
            'alt'   => '',
            'title' => ''
        ];

        if (!empty($src)) {
            if (isset($attrs['lazy']) && $attrs['lazy']) {
                $attrs['data-src'] = $src;
                $attrs['class'] .= ' lazy';

                unset($attrs['lazy']);
            } else {
                $attrs['src'] = $src;
            }
        }

        $templater = $this->templater();
        $attrs = array_merge($_defaultAttrs, $attrs);

        $templateVars['attrs'] = $templater->formatAttributes($attrs);

        return $this->formatTemplate('avatarImage', $templateVars);
    }

}
