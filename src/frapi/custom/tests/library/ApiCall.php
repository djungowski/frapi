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
    
    private $_username = Config::API_USER;
    
    private $_password = Config::API_KEY;
    
    public function __construct($url)
    {
        $this->_url = Config::API_BASE . '/' . $url . '.' . Config::API_FORMAT;
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