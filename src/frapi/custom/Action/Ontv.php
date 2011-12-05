<?php
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Config.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Database.php';

/**
 * Action Ontv 
 * 
 * List of Movies on German TV
 * 
 * @link http://getfrapi.com
 * @author Frapi <frapi@getfrapi.com>
 * @link /ontv/list
 */
class Action_Ontv extends Frapi_Action implements Frapi_Action_Interface
{

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
        $token = $this->getParam('token', Frapi_Action::TYPE_STRING, null);
        
        $db = new Custom_Model_Database();
        $config = new Custom_Model_Config($token);
        $titleview = $config->getConfig('titleview');
        
        $query = '
        SELECT
        	tv.*,
        	DATE_FORMAT(tv.date, "%s") AS day,
        	t.title AS movietitle,
        	t.year AS movieyear
    	FROM s11_ontv_de AS tv
		INNER JOIN	movie AS m
            ON (m.ID = tv.movieID)
        INNER JOIN	title_m		AS t
            ON (t.ID = m.%s)
        WHERE
			tv.date >= NOW()
		LIMIT 10
        ';
        $query = sprintf($query, '%Y-%m-%d', $titleview);
        $movies = $db->fetchAll($query);
        foreach($movies as $movie) {
            if (!isset($this->data[$movie['day']])) {
                $this->data[$movie['day']] = array();
            }
            $this->data[$movie['day']][] = $movie;
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

