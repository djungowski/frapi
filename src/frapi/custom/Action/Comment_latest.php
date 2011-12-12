<?php
use Score11\Frapi as Score11;

require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Config.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Database.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Thumb.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'MovieImage.php';

/**
 * Action Comment_latest 
 * 
 * Show the latest comments
 * 
 * @link http://getfrapi.com
 * @author Frapi <frapi@getfrapi.com>
 * @link /comment/latest
 */
class Action_Comment_latest extends Frapi_Action implements Frapi_Action_Interface
{
    /**
     * Default limit for latest comments
	 *
     * @var Integer
     */
    const LIMIT = 5;
    
    /**
     * Default startpoint for latest comments
     * 
     * @var Integer
     */
    const OFFSET = 0;

    /**
     * Required parameters
     * 
     * @var An array of required parameters.
     */
    protected $requiredParams = array();

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
        $limit = $this->getParam('limit', FRAPI_ACTION::TYPE_INTEGER, self::LIMIT);
        $offset = $this->getParam('offset', FRAPI_ACTION::TYPE_INTEGER, self::OFFSET);
        $token = $this->getParam('token', Frapi_Action::TYPE_STRING, null);
        
        $config = new Score11\Config($token);
        $movietitle = $config->getConfig('titleview');
        
        $sql = '
        	SELECT		c.*,
        				u.login 		AS user,
        				t.title			AS movietitle,
        				t.year			AS movieyear,
        				m.regie			AS director,
        				m.actor,
        				m.image			AS hasimage,
        				m.ratings,
        				m.ratingsavg
        	FROM		comment2 		AS c
        	INNER JOIN	user			AS u
        	ON 			(u.ID = c.userID)
        	INNER JOIN	movie		AS m
            ON			(m.ID = c.refID)
            INNER JOIN	title_m		AS t
            ON			(t.ID = m.%s)
        	ORDER BY	c.timestamp DESC
        	LIMIT 		%d, %d
        ';
        $sql = sprintf($sql, $movietitle, $offset, $limit);
        
        $db = new Score11\Database();
        $this->data = $db->fetchAll($sql);
        // Calculate thumb for each movie
        $thumb = new Custom_Model_Thumb();
        foreach ($this->data as $key => $comment) {
            $this->data[$key]['thumb'] = $thumb->getTrend($comment['ratings'], $comment['ratingsavg']);
            $image = new Custom_Model_MovieImage($comment['refID'], $comment['hasimage']);
            $this->data[$key]['image'] = $image->getLink();
        }
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
        return $this->toArray();
    }


}

