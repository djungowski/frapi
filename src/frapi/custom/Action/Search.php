<?php
use Score11\Frapi as Score11;

require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Config.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Database.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Thumb.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'MovieImage.php';

/**
 * Action Search 
 * 
 * Search for movies by query
 * 
 * @link http://getfrapi.com
 * @author Frapi <frapi@getfrapi.com>
 * @link /search/:query
 */
class Action_Search extends Frapi_Action implements Frapi_Action_Interface
{

    /**
     * Required parameters
     * 
     * @var An array of required parameters.
     */
    protected $requiredParams = array('query');

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
        $this->data['query'] = $this->getParam('query', self::TYPE_OUTPUT);
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
    	$queryParam = $this->getParam('query', Frapi_Action::TYPE_STRING, null);
        $token = $this->getParam('token', Frapi_Action::TYPE_STRING, null);
        
        $db = new Score11\Database();
        $config = new Score11\Config($token);
        $titleview = $config->getConfig('titleview');
        
        $query = '
        SELECT
        	t.movieID,
        	t.version,
        	t.title,
        	m.ratings,
        	m.ratingsavg,
        	m.image AS hasimage
        FROM
        	title_m AS t
        INNER JOIN
        	movie AS M
        	ON m.ID = t.movieID
        WHERE
        	title LIKE "%s"
        ';
        $queryParam = '%' . $queryParam . '%';
        $query = sprintf($query, mysql_real_escape_string($queryParam));
        $movies = $db->fetchAll($query);
        $this->data['movies'] = array();
	    // Calculate thumb for each movie
        $thumb = new Score11\Thumb();
        foreach($movies as $movie) {
            $movie['thumb'] = $thumb->getTrend($movie['ratings'], $movie['ratingsavg']);
            $image = new Score11\MovieImage($movie['movieID'], $movie['hasimage']);
            $movie['image'] = $image->getLink();
            
            $this->data['movies'][] = $movie;
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

