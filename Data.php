<?php

class Data {
    
    public $_id, $_userName, $_password, $_isAdmin;
    
    public function __construct($dbRow) {//deconstructs the record
        @$this->_id = $dbRow['UserId'];
        @$this->_userName = $dbRow['Username'];
        @$this->_password = $dbRow['Password'];
        @$this->_isAdmin = $dbRow['IsAdmin'];
    }

    //V Getters V
    public function getUserID() {
        return $this->_id;
    }
   
    public function getUserName() {
       return $this->_userName;
    }
    
    public function getPassword() {
       return $this->_password;
    }
    
    public function getIsAdmin() {
       return $this->_isAdmin;
    }

}


