<?php

class ThoughtsData {
    
    public $_id, $_thoughts, $_task;
    
    public function __construct($dbRow) {//deconstructs the record
        $this->_id = $dbRow['ThoughtsId'];
        $this->_task = $dbRow['TaskId'];
        $this->_thoughts = $dbRow['Thoughts'];
    }

    //V Getters V
    public function getId() {
        return $this->_id;
    }

    public function getTaskId() {
        return $this->_task;
    }

    public function getThoughts() {
        return $this->_thoughts;
    }

}
