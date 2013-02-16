<?php
use Score11\Frapi as Score11;

require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Config.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Database.php';
require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'MovieImage.php';

/**
 * Action Movie 
 * 
 * Show movie with all its details
 * 
 * @link http://getfrapi.com
 * @author Frapi <frapi@getfrapi.com>
 * @link /movie/:movie_id
 */
class Action_Movie extends Frapi_Action implements Frapi_Action_Interface
{

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
        
        $movieId = $this->getParam('movie_id', Frapi_Action::TYPE_INTEGER, 0);
        $token = $this->getParam('token', Frapi_Action::TYPE_STRING, null);
        $config = new Score11\Config($token);
        $titleview = $config->getConfig('titleview');
        
        $this->data['titleview'] = $titleview;
        $this->data = array_merge($this->data, $this->getMovieDetails($movieId));
        // Als erstes alle Titel fuer den Film auslesen
        $this->data['titles'] = $this->getMovieTitles($movieId, $this->data['ori']);
        // Dann die Besetzung auslesen
        $this->data['cast'] = $this->getMovieCast($movieId);
        $this->addGenres();
        
        // Bildlink hinzufuegen
        $image = new Score11\MovieImage($this->data['ID'], $this->data['hasimage']);
        $this->data['image'] = $image->getLink();
        
        return $this->toArray();
    }
    
    private function getMovieTitles($movieId, $originalTitleId = 0)
    {
        $db = new Score11\Database();
        $query = '
        SELECT	*,
        		(ID = %d) AS ori
        FROM	title_m
        WHERE	movieID = %d
        ';
        $query = sprintf($query, $originalTitleId, $movieId);
        return $db->fetchAll($query);
    }
    
    private function getMovieCast($movieId)
    {
        $db = new Score11\Database();
        $query = '
        SELECT	*
        FROM	s11_casting
        WHERE	movieID = %d
        ORDER BY
        	cast,
        	prio DESC
        ';
        $query = sprintf($query, $movieId);
        $cast = $db->fetchAll($query);
        $groupedCast = array();
        foreach ($cast as $person) {
            if (!isset($groupedCast[$person['cast']])) {
                $groupedCast[$person['cast']] = array();
            }
            $groupedCast[$person['cast']][] = $person;
        }
        return $groupedCast;
    }
    
    private function getMovieDetails($movieId)
    {
        $db = new Score11\Database();
        $query = '
        SELECT	*,
        		image	AS	hasimage
        FROM	movie
        WHERE	ID = %d
        ';
        $query = sprintf($query, $movieId);
        return $db->fetch($query);
    }
    
    private function addGenres()
    {
        $db = new Score11\Database();
        $genreIds = array(
            $this->data['genre1'] => 'genre1',
            $this->data['genre2'] => 'genre2',
            $this->data['genre3'] => 'genre3',
            $this->data['genre4'] => 'genre4'
        );
        $query = '
        SELECT	*
        FROM	s11_genre
        WHERE	ID IN (%s)
        ';
        $query = sprintf($query, implode(',', array_keys($genreIds)));
        $this->data['genres'] = $db->fetchAll($query);
        // Einzelne Genre IDs entfernen
        foreach($genreIds as $genre) {
            unset($this->data[$genre]);
        }
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

