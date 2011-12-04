<?php

/**
 * Database connector as we need it
 * 
 * @author djungowski
 */
class Custom_Model_Database
{
    /**
     * Database connection
     * 
     * @var PDO
     */
    protected $_db;
    
    public function __construct()
    {
        $this->_db = Frapi_Database::getInstance();
    }
    
    /**
     * Pass everything through that isn't defined here
     *
     * @param String $name
     * @param Array $arguments
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->_db, $name), $arguments);
    }
    
	/**
     * Sent a Query to the default database connection
     * and return it UTF8 encoded (multiple values returned)
     * 
     * @param String $query
     */
    public function fetchAll($query)
    {
        $statement = $this->_db->query($query);
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $data = $this->encodeUtf8Recursive($data);
        return $data;
    }
    
	/**
     * Sent a Query to the default database connection
     * and return it UTF8 encoded (one value returned)
     * 
     * @param String $query
     */
    public function fetch($query)
    {
        $statement = $this->_db->query($query);
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        // if there is no result, use an empty array
        if(!$data) {
            $data = array();
        }
        $data = $this->encodeUtf8Recursive($data);
        return $data;
    }
    
    /**
     * Encode a whole array to UTF8
     * 
     * @param Array $param
     */
    protected function encodeUtf8Recursive($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->encodeUtf8Recursive($value);
            } else {
                $array[$key] = utf8_encode($value);
            }
        }
        return $array;
    }
}