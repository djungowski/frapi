<?php
namespace Score11\Frapi\Test\Library;

class ApiCall
{
    /**
     * Die URL die aufgerufen wird fuer einen Integrationstest
     * 
     * @var unknown_type
     */
    private $_url;
    
    private $_username = 'score11';
    
    private $_password = 'bfeaf45d7fe418635d6b361f1aec34630096431d';
    
    public function __construct($url)
    {
        $this->_url = Config::API_BASE . '/' . $url;
    }
    
    public function request($method = 'GET')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_USERPWD, $this->_username . ':' . $this->_password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        // Nach 10 Sekunden ist Schluss
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $content = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($content, true);
    }
}