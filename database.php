<?php

class Database {

    protected static $_dbInstance = null;

    protected $_dbHandle;

    public static function getInstance() {
        //database login details

        $username ='';
        $password = '';
        $host = '';
        $dbName = '';

        if(self::$_dbInstance === null) {//crates the instance using the db details
            self::$_dbInstance = new self($username, $password, $host, $dbName);
        }

        return self::$_dbInstance;
    }

    private function __construct($username, $password, $host, $database) {
        try {
            $this->_dbHandle = new PDO("mysql:host=$host;dbname=$database",  $username, $password);//connects to the database
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getdbConnection() {//returns the handle
        return $this->_dbHandle;
    }

    public function __destruct() {//nulls the db handle
        $this->_dbHandle = null;
    }
}
