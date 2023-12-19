<?php

class IssuesData {
    
    public $_id, $_issues, $_task;
    
    public function __construct($dbRow) {//deconstructs the record
        $this->_id = $dbRow['IssuesId'];
        $this->_task = $dbRow['TaskId'];
        $this->_issues = $dbRow['Issues'];
    }

    //V Getters V
    public function getId() {
        return $this->_id;
    }

    public function getTaskId() {
        return $this->_task;
    }

    public function getIssues() {
        return $this->_issues;
    }

}
