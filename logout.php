<?php

require_once ('User.php');

if (isset($_POST["logoutButton"])) {//checks to see if the logout button has been pressed
    $user = new User();
    $user->logout();//logs out the user
    header('Location: Log in.phtml');
}