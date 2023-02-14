<?php
namespace App\Model\Validation;
use Cake\Validation\Validation;

class UrlValidation extends Validation
{

    static public function domainWhitelist($value, $domainWhiteList)
    {
        $host = parse_url($value, PHP_URL_HOST);

        // Without domainList patterns
        if (in_array($host, $domainWhiteList)) {
            return true;
        }

        // Patterns
        foreach ($domainWhiteList as $pattern) {
            $pattern = str_replace('*', '.*', $pattern);

            if (preg_match('/' .$pattern. '/i', $host)) {
                return true;
            }
        }

        return false;
    }

}
