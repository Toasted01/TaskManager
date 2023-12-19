<?php

require_once("User.php");

$view = new stdClass();
$view->loggedin = "not logged in";

if (isset($_POST["loginButton"])) {//checks to see if the login button is pressed
    $user = new User();
    if($user->Authenticate(($_POST["username"]),($_POST["password"]))) {//checks that the username and password are correct
        $_SESSION["loggedin"] = true; //logs in the user
        header('Location: Tasks.php');
        exit();
    }
    else{
        $_SESSION["loggedin"] = false;
        if ($view->loggedin = "not logged in") {
            echo "<p>Username or password is incorrect</p>";
        }
    }
}

if (isset($_SESSION["loggedin"]))
{
    $view->loggedin = "logged in";//tells view the user is logged in
}
require_once('Log in.phtml');