<?php

class Custom_Model_Config
{
    const SESSION_DURATION = 1800;
    
    protected $_memcache;
    
    protected $_config;
    
    public function __construct($sessionId = null)
    {
        if (!is_null($sessionId)) {
            $this->_memcache = new Memcache();
            $this->_memcache->addServer('127.0.0.1');
            $this->_config = $this->_memcache->get($sessionId);
            // Session verlaengern
            if ($this->_config !== false) {
                $success = $this->_memcache->replace($sessionId, $this->_config, null, self::SESSION_DURATION);
            }
            $this->_config = json_decode($this->_config, true);
        }
    }
    
    public function getConfig($key)
    {
        if (!isset($this->_config[$key])) {
            $conf = Frapi_Internal::getConfiguration('websitedefaults');
            $keyConfig = $conf->getByField('websitedefault', 'key', $key);
            $this->_config[$key] = $keyConfig['value']; 
        }
        return $this->_config[$key];
    }
}