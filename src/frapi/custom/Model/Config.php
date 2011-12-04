<?php

class Custom_Model_Config
{
    protected $_memcache;
    
    protected $_config;
    
    public function __construct($sessionId = null)
    {
        if (!is_null($sessionId)) {
            $this->_memcache = new Memcache();
            $this->_memcache->addServer('127.0.0.1');
            $this->_config = json_decode($this->_memcache->get($sessionId), true);
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