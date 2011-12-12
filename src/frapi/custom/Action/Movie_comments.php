<?php
use Score11\Frapi as Score11;

require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Config.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Database.php';

/**
 * Action Movie_comments 
 * 
 * Get all the comments for a movie
 * 
 * @link http://getfrapi.com
 * @author Frapi <frapi@getfrapi.com>
 * @link /movie/:movie_id/comments
 */
class Action_Movie_comments extends Frapi_Action implements Frapi_Action_Interface
{
    /**
     * Standard: Kommentare pro Seite
     * 
     * @var Integer
     */
    const LIMIT = 25;
    
    /**
     * Standard-Anfang
     * 
     * @var Integer
     */
    const OFFSET = 0;
    
    /**
     * Time in minutes comments can be edited
     * 
     * @var INTEGER
     */
    const EDIT_TIMEOUT = 30;

    /**
     * Required parameters
     * 
     * @var An array of required parameters.
     */
    protected $requiredParams = array('movie_id');

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
        $valid = $this->hasRequiredParameters($this->requiredParams);
        if ($valid instanceof Frapi_Error) {
            return $valid;
        }
        
        $token = $this->getParam('token', Frapi_Action::TYPE_STRING, null);
        $limit = $this->getParam('limit', FRAPI_ACTION::TYPE_INTEGER, self::LIMIT);
        $offset = $this->getParam('offset', FRAPI_ACTION::TYPE_INTEGER, self::OFFSET);
        $movieId = $this->getParam('movie_id', Frapi_Action::TYPE_INTEGER, 0);
        
        $config = new Score11\Config($token);
        $userId = $config->getConfig('userID');
        
        $db = new Score11\Database();
        $query = '
        SELECT
        	c.*,
        	u.login AS username,
        	MD5(ud.email) AS gravatar,
        	(c.userID = %d && c.timestamp >= NOW() - INTERVAL %d MINUTE) as editable
        FROM
        	comment2 AS c
        INNER JOIN
        	user AS u
        	ON (u.ID = c.userID)
        INNER JOIN
        	user_data AS ud
        	ON (ud.userID = c.userID)
        WHERE
        	c.refID = %d
        ORDER BY
        	c.timestamp DESC
        LIMIT
        	%d, %d
        ';
        $query = sprintf($query, $userId, self::EDIT_TIMEOUT, $movieId, $offset, $limit);
        $this->data = $db->fetchAll($query);
        return $this->toArray();
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
        
        return $this->toArray();
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
        $valid = $this->hasRequiredParameters($this->requiredParams);
        if ($valid instanceof Frapi_Error) {
            return $valid;
        }
        
        return $this->toArray();
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

