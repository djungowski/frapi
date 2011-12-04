<?php

/**
 * Action User_login 
 * 
 * Login the User
 * 
 * @link http://getfrapi.com
 * @author Frapi <frapi@getfrapi.com>
 * @link /user/login
 */
class Action_User_login extends Frapi_Action implements Frapi_Action_Interface
{

    /**
     * Required parameters
     * 
     * @var An array of required parameters.
     */
    protected $requiredParams = array('login', 'password');

    /**
     * The data container to use in toArray()
     * 
     * @var A container of data to fill and return in toArray()
     */
    private $data = array();

    /**
     * To Array
     * 
     * This method returns the value found in the database 
     * into an associative array.
     * 
     * @return array
     */
    public function toArray()
    {
        $this->data['login'] = $this->getParam('login', self::TYPE_OUTPUT);
        return $this->data;
    }

    /**
     * Default Call Method
     * 
     * This method is called when no specific request handler has been found
     * 
     * @return array
     */
    public function executeAction()
    {
        $valid = $this->hasRequiredParameters($this->requiredParams);
        if ($valid instanceof Frapi_Error) {
            return $valid;
        }
        
        return $this->toArray();
    }

    /**
     * Get Request Handler
     * 
     * This method is called when a request is a GET
     * 
     * @return array
     */
    public function executeGet()
    {
        throw new Frapi_Error('NO_GET');
    }

    /**
     * Post Request Handler
     * 
     * This method is called when a request is a POST
     * 
     * @return array
     */
    public function executePost()
    {
        $valid = $this->hasRequiredParameters($this->requiredParams);
        if ($valid instanceof Frapi_Error) {
            return $valid;
        }
        $db = Frapi_Database::getInstance();
        
        $login = $this->getParam('login', Frapi_Action::TYPE_STRING);
        $login = $db->quote($login);
        $password = $this->getParam('password', Frapi_Action::TYPE_STRING);
        $password = $db->quote($password);
        
        $sql = '
        SELECT		u.*,
        			SHA1(u.password) 	AS password
        FROM		user				AS u
        WHERE		u.login = %s
        AND			u.enabled = 1
        AND			SHA1(u.password) = %s
        ';
        $sql = sprintf($sql, $login, $password);
        
        $userInfo = $this->readFromDatabaseSingle($sql);
        // If the $userInfo array is empty, the login was unsuccessful
        $loginSuccessful = !empty($userInfo);
        $this->data['success'] = $loginSuccessful;
        if (!$loginSuccessful) {
            $this->increaseFailedLogins($login);
        } else {
            $this->updateLoginData($userInfo['ID']);
        }
        $this->data = array_merge($this->data, $userInfo);
        // Hier muss noch irgendwas Session-aehnliches her... Memcache evtl?
        //$_SESSION  = $this->data;
        return $this->toArray();
    }
    
    /**
     * Increases the amount of failed login attempts
     * 
     * @param String $login
     */
    protected function increaseFailedLogins($login)
    {
        $sql = '
        UPDATE	user
        SET		loginfailed = loginfailed+1
        WHERE	login = %s
        ';
        $sql = sprintf($sql, $login);
        Frapi_Database::getInstance()->query($sql);
    }
    
    /**
     * Resets the number of failed login attempts
     * and updates the last login timestamp
     * 
     * @param Integer $userId
     */
    protected function updateLoginData($userId)
    {
        $sql = '
        UPDATE	user
        SET		loginfailed = 0,
        		lastlogin = NOW()
        WHERE	ID = %d
        LIMIT	1
        ';
        $sql = sprintf($sql, $userId);
        Frapi_Database::getInstance()->query($sql);
    }

    /**
     * Put Request Handler
     * 
     * This method is called when a request is a PUT
     * 
     * @return array
     */
    public function executePut()
    {
        throw new Frapi_Error('NO_PUT');
    }

    /**
     * Delete Request Handler
     * 
     * This method is called when a request is a DELETE
     * 
     * @return array
     */
    public function executeDelete()
    {
        $valid = $this->hasRequiredParameters($this->requiredParams);
        if ($valid instanceof Frapi_Error) {
            return $valid;
        }
        
        return $this->toArray();
    }

    /**
     * Head Request Handler
     * 
     * This method is called when a request is a HEAD
     * 
     * @return array
     */
    public function executeHead()
    {
        $valid = $this->hasRequiredParameters($this->requiredParams);
        if ($valid instanceof Frapi_Error) {
            return $valid;
        }
        
        return $this->toArray();
    }


}

