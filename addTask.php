<?php

require_once ('Menu.phtml');
require_once ('TaskDataSet.php');

?>

<body>
    <form action = "" method = "post">
        <div class = "container">
            <label for = "name" class = "logInPage">Task Name</label>
            <input type = "text" placeholder = "Enter Task Name" name = "name" id = "name" required>

            <label for = "project" class = "logInPage">Project</label>
            <input type = "text" placeholder = "Enter Project" name = "project" id = "project" required>

            <label for = "tag" class = "logInPage">Tag</label>
            <input type = "text" placeholder = "Enter Tag" name = "tag" id = "tag" required>

            <label for = "startDate" class = "logInPage">Start Date</label>
            <input type = "date" name = "startDate" id = "startDate" required>

            <label for = "endDate" class = "logInPage">End Date</label>
            <input type = "date" name = "endDate" id = "endDate" required>
            <br>
            <button type = "submit" name = "taskSubmitButton" id = "taskSubmitButton" class = "formButton">Submit</button>
        </div>
    </form>
</body>

<?php

if (isset($_POST["taskSubmitButton"])) //checks to see if the submit button has been pressed
{
    $task = new TaskDataSet();
    $task->addTask(($_SESSION["userID"]), ($_POST["name"]), ($_POST["project"]), ($_POST["tag"]), ($_POST["startDate"]), ($_POST["endDate"]));
}