<?php
require_once('TaskDataSet.php');

session_start();

$id = $_GET['id'];
$item = new TaskDataSet();
@$row=$item->getTaskDetails($id);

?>

<body>
<form action = "" method = "post" class = "submissionForm">
    <div class = "container">

<?php echo
'
            <label for = "name" class = "logInPage">Task Name</label>
            <input type = "text" placeholder = "Enter Task Name" name = "name" id = "name" value = "'.$row[0]->getName().'" required>
    
            <label for = "project" class = "logInPage">Project</label>
            <input type = "text" placeholder = "Enter Project" name = "project" id = "project" value = "'.$row[0]->getProject().'" required>
    
            <label for = "tag" class = "logInPage">Tag</label>
            <input type = "text" placeholder = "Enter Tag" name = "tag" id = "tag" value = "'.$row[0]->getCategory().'" required>
    
            <label for = "%effort" class = "logInPage">% Effort</label>
            <input type = "number" step = "5" min = "5" max = "100" placeholder = "Enter The Percentage Of The Daily Hours This Task Will Take" name = "effort" id = "effort" value = "'.$row[0]->getEffort().'" required>
    
            <label for = "startDate" class = "logInPage">Start Date</label>
            <input type = "date" name = "startDate" id = "startDate" value = "'.$row[0]->getStartDate().'" required>
    
            <label for = "endDate" class = "logInPage">End Date</label>
            <input type = "date" name = "endDate" id = "endDate" value = "'.$row[0]->getEndDate().'" required>
    
            <button type = "submit" name = "editSubmitButton" id = "editSubmitButton">Submit</button>';
?>
    </div>
</form>
</body>

<?php

if (isset($_POST["editSubmitButton"])) //checks to see if the submit button has been pressed
{
    $task = new TaskDataSet();
    $date = date('Y/m/d');
    $task->editTask($row[0]->getTaskId(), ($_SESSION["userID"]), ($_POST["name"]), ($_POST["project"]), ($_POST["tag"]), ($_POST["effort"]), ($date), ($_POST["startDate"]), ($_POST["endDate"]));
    header('Location: Tasks.php');
}