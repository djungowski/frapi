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
    
    /**
     * Der cURL handle
     * 
     * @var curl
     */
    private $_ch;
    
    public function __construct($url)
    {
        $this->_url = Config::API_BASE . '/' . $url . '.' . Config::API_FORMAT;
        $this->initCurl();
    }
    
    private function initCurl()
    {
        $this->_ch = curl_init();
        $this->setOption(CURLOPT_URL, $this->_url);
        $this->setOption(CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        $this->setOption(CURLOPT_USERPWD, $this->_username . ':' . $this->_password);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        // Nach 10 Sekunden ist Schluss
        $this->setOption(CURLOPT_TIMEOUT, 10);
    }
    
    public function setOption($curlOption, $value)
    {
        if (!curl_setopt($this->_ch, $curlOption, $value)) {
            throw new \InvalidArgumentException('Unknown cURL Option "' . $curlOption . '"');
        }
    }
    
    public function setPostParameters(Array $params)
    {
        $this->setOption(CURLOPT_POSTFIELDS, $params);
    }
    
    public function request($method = 'GET')
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, $method);
        $content = curl_exec($this->_ch);
        curl_close($this->_ch);
        
        return json_decode($content, true);
    }
}