<?php
use Score11\Frapi as Score11;

require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Config.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Database.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Thumb.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'MovieImage.php';

/**
 * Action Movie_startlist 
 * 
 * Shows a list with all moviestarts from now on
 * 
 * @link http://getfrapi.com
 * @author Frapi <frapi@getfrapi.com>
 * @link /list/moviestart
 */
class Action_Movie_startlist extends Frapi_Action implements Frapi_Action_Interface
{
    /**
     * Standard: Kommentare pro Seite
     * 
     * @var Integer
     */
    const LIMIT = 10;
    
    /**
     * Standard-Anfang
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
        
        $db = new Score11\Database();
        $config = new Score11\Config($token);
        $titleview = $config->getConfig('titleview');
        
        $query = '
        SELECT
        	ms.movieID as ID,
        	ms.date,
        	DATE_FORMAT(ms.date, "%s") AS day,
        	t.title AS movietitle,
        	t.year AS movieyear,
        	m.image AS hasimage,
        	m.ratings,
        	m.ratingsavg,
        	m.regie AS director,
        	m.actor
    	FROM moviestart AS ms
		INNER JOIN	movie AS m
            ON (m.ID = ms.movieID)
        INNER JOIN	title_m		AS t
            ON (t.ID = m.%s)
        WHERE
			ms.date >= NOW()
		LIMIT %d, %d
        ';
        $query = sprintf($query, '%Y-%m-%d', $titleview, $offset, $limit);
        $movies = $db->fetchAll($query);
        // Calculate thumb for each movie
        $thumb = new Score11\Thumb();
        foreach($movies as $movie) {
            $movie['thumb'] = $thumb->getTrend($movie['ratings'], $movie['ratingsavg']);
            $image = new Score11\MovieImage($movie['ID'], $movie['hasimage']);
            $movie['image'] = $image->getLink();
            
            $this->data[] = $movie;
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

