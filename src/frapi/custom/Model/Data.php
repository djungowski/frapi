<?php
namespace Score11\Frapi;

abstract class Data
{
    private $_database;
    
    protected function getDatabase()
    {
        if (!isset($this->_database)) {
            $this->_database = new Database();
        }
        return $this->_database;
    }
    
    abstract protected function transform();
    
    abstract public function get();
}