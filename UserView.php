<?php

require_once("DataSet.php");

class UserView
{
    protected $_user, $_Admin, $taskCount;

    public function __construct($dbRow)
    {
        @$this->_user = $dbRow['Username'];
        @$this->_Admin = $dbRow['IsAdmin'];
        @$this->taskCount = $dbRow['COUNT(AssignedTask.UserId)'];
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function getIsAdmin()
    {
        return $this->_Admin;
    }

    public function getIsAdminText(): string
    {
        if($this->_Admin == 1)
        {
            return "Yes";
        }
        else return "No";
    }

    public function getTaskCount()
    {
        return $this->taskCount;
    }

}