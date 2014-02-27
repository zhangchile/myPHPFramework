<?php 

class Model extends SQLQuery
{
    protected $_model;

    function __construct()
    {
        $this->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->_model = get_class($this);
        $this->_table = strtolower(substr($this->_model, 0, -5));
    }

    function __destruct()
    {
        
    }
}