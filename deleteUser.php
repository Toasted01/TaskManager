<?php

require_once ('Menu.phtml');
require_once ('User.php');

$name = $_GET['userId'];
$user = new DataSet();

$user->deleteUser($name);

header('Location: viewUsers.php');