<?php
namespace Hiryu85\Model\Validation;
use Cake\Validation\Validation;

class VideoEmbedValidation extends Validation
{

    static protected function parseHTML($html) {
        $doc  = new \DOMDocument();
        $html = $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        // In questo modo non crea Doctype e head/body
        //$html = $doc->loadXML($html);
        return $doc;
    }

    /**
     * Verifica che il dominio dell'<iframe> sia tra quelli abilitati
     *
     * @param  [type] $value          [description]
     * @param  [type] $allowedDomains [description]
     * @return [type]                 [description]
     */
    static public function iframeWhitelist($value, $allowedDomains)
    {
        $doc     = self::parseHTML($value);
        $iframes = $doc->getElementsByTagName('iframe');
        if ($iframes->length === 0) {
            return false;
        }

        $iframe = $iframes[0];

        if (!$iframe->hasAttribute('src')) {
            return false;
        }

        $src = $iframe->getAttribute('src');
        $src = parse_url($src);

        if (empty($src)) {
            return false;
        }

        return in_array($src['host'], $allowedDomains);
    }

    /**
     * Verifica che $value contenga un iframe HTML
     *
     * @param  [type]  $value           [description]
     * @param  [type]  $domainWhiteList [description]
     * @return boolean                  [description]
     */
    static public function iframeExists($value, $settings = [])
    {
        if (empty($value)) {
            return false;
        }

        $doc     = self::parseHTML($value);
        $iframes = $doc->getElementsByTagName('iframe');
        return $iframes->length > 0;
    }

}
