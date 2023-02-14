<?php
namespace Hiryu85\Traits;

class TableFindMissingSettingException extends \Exception {}

Trait TableFindsTrait {

    /**
     * Verifica che un sia stato passata una configurazione al metodo find* tramite $settings
     *
     * @param  str $field
     * @throws Exception
     * @return boolean
     */
    public function requireSetting($settings, $field)
    {
        $_settings = array_keys($settings);

        if (!in_array($field, $_settings)) {
            //$_errmsg = !empty($errmsg) ? $errmsg : $this->FIND_PARAMETER_MISSING_ERRMSG;

            throw new TableFindMissingSettingException(
                __('{method} richiede il parametro: {param}', ['method' => __METHOD__, 'param' => $field])
            );
        }
    }

}
