<?php
/**
 * Hier muss dann noch Userconfig rein, wenn der User eingeloggt ist
 * 
 * @author djungowski
 *
 */
class Custom_Model_Config
{
    public function getConfig($key)
    {
        $conf = Frapi_Internal::getConfiguration('websitedefaults');
        $keyConfig = $conf->getByField('websitedefault', 'key', $key);
        return $keyConfig['value'];
    }
}