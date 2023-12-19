<?php

require_once ('TaskDataSet.php');

session_start();

$joinId = $_GET['joinId'];
$item = new TaskDataSet();

$task = new TaskDataSet();
$task->joinTask(($_SESSION["userID"]), $joinId);

header('Location: teamTasks.php');