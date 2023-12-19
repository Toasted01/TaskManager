<?php
require_once('TaskDataSet.php');
require_once ('Menu.phtml');

$id = $_GET['id'];

?>

<body>
    <form action = "" method = "post" class = "submissionForm">
        <div class = "container">

            <label for = "%effort" class = "logInPage">Effort For The Task Today</label>
            <input type = "number" step = "5" min = "5" max = "100" placeholder = "Enter The Percentage Of The Daily Hours This Task Will Take" name = "effort" id = "effort" required>
    
            <button type = "submit" name = "checkInButton" id = "checkInButton">Submit</button>
        </div>
    </form>
</body>

<?php

if (isset($_POST["checkInButton"])) //checks to see if the submit button has been pressed
{
    $task = new TaskDataSet();
    $date = date('Y/m/d');
    $task->checkIn(($_SESSION["userID"]), $id, ($_POST["effort"]), ($date));
    header('Location: Tasks.php');
}