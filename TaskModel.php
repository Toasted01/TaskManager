<?php

require_once("TaskDataSet.php");

class TaskModel
{
    protected $_user, $_loggedIn, $_userID, $_Admin;

    public function __construct()
    {

    }

    public function addThought($thought)//adds a user to the database
    {
        $newUser = new TaskDataSet();
        $idDataSet = $newUser->newThought();//runs the newUser query
        $idData = new ThoughtsData($idDataSet); //creates a data object using $idDataSet
        $newId = $idData->getTaskID() + 1;// finds the last user id and adds 1 to it for the new id
        $newUser->addThought($newId, $_SESSION["ThisItem"], $thought);//adds the user
    }

    public function addIssue($issue)//adds a user to the database
    {
        $newUser = new TaskDataSet();
        $idDataSet = $newUser->newIssue();//runs the newUser query
        $idData = new ThoughtsData($idDataSet); //creates a data object using $idDataSet
        $newId = $idData->getTaskID() + 1;// finds the last user id and adds 1 to it for the new id
        $newUser->addIssue($newId, $_SESSION["ThisItem"], $issue);//adds the user
    }

}