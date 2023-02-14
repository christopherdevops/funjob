<?php
namespace Hiryu85\Traits;

/**
 * Immagine da LoremPixel
 *
 * @example $this->getLoremImage(['w' => 300, 'h' => 150, 'c' => 'sport'])
 * @example $this->getLoremImage(['w' => 300, 'h' => 150, 'c' => 'sport', 't' => 'hello'])
 */
trait LorempixelTrait {
    public $_lorempixelUrls = [
        'hw'    => '//lorempixel.com/:w/:h',
        'chw'   => '//lorempixel.com/:w/:h/:c',
        'chiw'  => '//lorempixel.com/:w/:h/:c/:i',
        //'chtw'  => '//lorempixel.com/:w/:h/:c/:t',
        'chitw' => '//lorempixel.com/:w/:h/:c/:i/:t'
    ];

    private $__defaults = [
        'w' => 300,
        'h' => 300,
        'c' => null,
        'i' => null,
        'h' => null,
        't' => null
    ];

    /**
     * Sort $settings in base alle keys per fare il matching di _lorempixelUrls
     *
     * @param  array $settings
     * @return array
     */
    private function __sortSettings($settings)
    {
        ksort($settings);
        return $settings;
    }

    /**
     * Determina quale url utilizzare in base a $settings
     *
     * Fà riferimento a _lorempixelUrls
     *
     * @param  array $settings
     * @throws Exception
     * @return str
     */
    private function __getUrlFromSettings($settings)
    {
        $templateData = $this->__sortSettings($settings);
        $templateKey  = implode('', array_keys($templateData));

        if (empty($this->_lorempixelUrls[$templateKey])) {
            throw new \Exception(sprintf('LorempixelTrait: cant get url from template %s', $templateKey));
        }

        return $this->_lorempixelUrls[$templateKey];
    }

    private function __replaceTemplateVars($settings, $url)
    {
        $templateVars = [];

        foreach ($settings as $key => $value) {
            $templateVars[':' . $key] = $value;
        }

        return str_replace(array_keys($templateVars), array_values($templateVars), $url);
    }

    /**
     * Restituisce url a image LoremPixel.com
     *
     * @param  array $settings (w -> width, h -> height, c -> category, i -> index, t -> text)
     *
     * @example $this->getLoremImage(['w' => 300, 'h' => 150, 'c' => 'sport'])
     * @example $this->getLoremImage(['w' => 300, 'h' => 150, 'c' => 'sport', 't' => 'hello'])
     * @return str
     */
    public function getLoremImage($settings = [])
    {
        $settings = array_filter(array_merge($this->__defaults, $settings));
        $url      = $this->__getUrlFromSettings($settings);

        return $this->__replaceTemplateVars($settings, $url);
    }
}
