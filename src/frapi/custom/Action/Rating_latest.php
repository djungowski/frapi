<?php
use Score11\Frapi as Score11;

require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Config.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Database.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Thumb.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'MovieImage.php';

/**
 * Action Rating_latest 
 * 
 * Shows the latest ratings
 * 
 * @link http://getfrapi.com
 * @author Frapi <frapi@getfrapi.com>
 * @link /rating/latest
 */
class Action_Rating_latest extends Frapi_Action implements Frapi_Action_Interface
{
    /**
     * Wie viel Elemente standardmaessig angezeigt werden
     *
     * @var Integer
     */
    const LIMIT = 10;

    /**
     * Ab wann standardmaessig ausgelesen wird
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
        SELECT		r.*,
			DATE_FORMAT(r.timestamp, "%s") AS day,
			r.timestamp AS date,
        			t.title 	AS movietitle,
        			t.year		AS movieyear,
        			m.ratings,
        			m.ratingsavg,
        			m.image		AS hasimage,
        			m.regie		AS director,
        			m.actor
        FROM		score_m		AS r
        INNER JOIN	movie		AS m
        ON			(m.ID = r.movieID)
        INNER JOIN	title_m		AS t
        ON			(t.ID = m.%s)
        ORDER BY	r.timestamp DESC
        LIMIT		%d, %d
        ';
        $sql = sprintf($sql, '%Y-%m-%d', $movietitle, $offset, $limit);
        $db = new Score11\Database();
        $this->data = $db->fetchAll($sql);
        $thumb = new Score11\Thumb();
        foreach($this->data as $key => $movie) {
            // Daumen berechnen
            $this->data[$key]['thumb'] = $thumb->getTrend($movie['ratings'], $movie['ratingsavg']);
            // Filmbild
            $image = new Score11\MovieImage($movie['movieID'], $movie['hasimage']);
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

