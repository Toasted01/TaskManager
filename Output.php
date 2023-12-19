<?php
require_once("User.php");
require_once("TaskDataSet.php");

$search = new TaskDataSet();
$check = $_POST['choice'];
$row=null;

switch($check) {
    case "uname":
        $row = $search->getTaskNameFromUser($_POST['search']);
        break;
    case "task":
        $row = $search->getTaskName($_POST['search']);
        break;
    case "project":
        $row = $search->getTaskNameFromProject($_POST['search']);
        break;
    case "category":
        $row = $search->getTaskNameFromTag($_POST['search']);
        break;
    case "startDate":
        $row=$search->getTaskNameFromStartDate($_POST['search']);
        break;
    case "endDate":
        $row=$search->getTaskNameFromEndDate($_POST['search']);
        break;
    default:
        header('Location: Search.php');
        exit();
}

require_once('Output.phtml');