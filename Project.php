<?php
require_once('TaskDataSet.php');
require_once ("User.php");

$view = new stdClass();
$_SESSION["ThisItem"]=0;

if (isset($_POST["ViewButton"])) {
    @$_SESSION["ThisItem"]=$_POST["Project"];//gets the project of the item from Tasks.php you clicked. for some reason when i remove the +1 the entire page breaks so here it will stay.
}

require_once("Project.phtml");
