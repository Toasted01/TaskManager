<?php

require_once ('Database.php');
require_once ('TaskData.php');

class TaskDataSet
{
    protected $_dbHandle, $_dbInstance;

    public function __construct() {//loads the database
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function getTaskDetails($id): array
    {
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.Task WHERE $id = TaskId");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {//adds all rows to the array
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function editTask($taskId, $userId, $name, $project, $tag, $effort, $lastUpdated, $startDate, $endDate)
    {
        $statement = $this->_dbHandle->prepare("UPDATE hc21_20.Task SET Task.Name = ?, Task.Project = ?, Task.Category = ?, Task.StartDate = ?, Task.EndDate = ? WHERE Task.TaskId = $taskId");
        $statement2 = $this->_dbHandle->prepare("UPDATE hc21_20.CheckIn SET CheckIn.LastUpdated = ? WHERE TaskId = $taskId");
        $statement3 = $this->_dbHandle->prepare("UPDATE hc21_20.AssignedTask SET AssignedTask.Effort = ? WHERE TaskId = $taskId AND UserId = $userId");

        $statement->bindParam(1, $name);
        $statement->bindParam(2, $project);
        $statement->bindParam(3, $tag);
        $statement->bindParam(4, $startDate);
        $statement->bindParam(5, $endDate);

        $statement2->bindParam(1, $lastUpdated);

        $statement3->bindParam(1, $effort);

        $statement->execute();
        $statement2->execute();
        $statement3->execute();
    }

    public function getCurrentUserTasks($id) {//gets all the auctions replacing foreign keys with appropriate data
        $statement = $this->_dbHandle->prepare("SELECT Task.TaskId, Task.Name, Task.Project, Task.Category, AssignedTask.Effort, CheckIn.LastUpdated, Task.StartDate, Task.EndDate FROM hc21_20.Task INNER JOIN hc21_20.AssignedTask on AssignedTask.TaskId=Task.TaskId INNER JOIN hc21_20.CheckIn on CheckIn.TaskId=Task.TaskId WHERE $id=AssignedTask.UserId AND Task.StartDate <= DATE(NOW()) AND Task.EndDate >= DATE(NOW());");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {//adds all rows to the array
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function getPastUserTasks($id) {//gets all the auctions replacing foreign keys with appropriate data
        $statement = $this->_dbHandle->prepare("SELECT Task.Name, Task.Project, Task.Category, Task.StartDate, Task.EndDate FROM hc21_20.Task INNER JOIN hc21_20.AssignedTask on AssignedTask.TaskId=Task.TaskId WHERE $id=AssignedTask.UserId AND Task.EndDate < DATE(NOW());");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {//adds all rows to the array
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function getFutureUserTasks($id) {//gets all the auctions replacing foreign keys with appropriate data
        $statement = $this->_dbHandle->prepare("SELECT Task.Name, Task.Project, Task.Category, Task.StartDate, Task.EndDate FROM hc21_20.Task INNER JOIN hc21_20.AssignedTask on AssignedTask.TaskId=Task.TaskId WHERE $id=AssignedTask.UserId AND Task.StartDate > DATE(NOW());");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {//adds all rows to the array
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function getTeamTasks($id) {//gets all the auctions replacing foreign keys with appropriate data
        $statement = $this->_dbHandle->prepare("SELECT User.Username, Task.TaskId, Task.Name, Task.Project, Task.Category, AssignedTask.Effort, CheckIn.LastUpdated, Task.StartDate, Task.EndDate FROM hc21_20.Task INNER JOIN hc21_20.AssignedTask on AssignedTask.TaskId=Task.TaskId INNER JOIN hc21_20.User on User.UserId = AssignedTask.UserId INNER JOIN hc21_20.CheckIn on CheckIn.TaskId = AssignedTask.TaskId WHERE $id<>AssignedTask.UserId AND Task.EndDate >= DATE(NOW());");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {//adds all rows to the array
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function getOldTeamTasks($id) {//gets all the auctions replacing foreign keys with appropriate data
        $statement = $this->_dbHandle->prepare("SELECT Task.Name, Task.Project, Task.Category, Task.StartDate, Task.EndDate FROM hc21_20.Task INNER JOIN hc21_20.AssignedTask on AssignedTask.TaskId=Task.TaskId WHERE $id<>AssignedTask.UserId AND Task.EndDate < DATE(NOW());");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {//adds all rows to the array
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function addTask($userId, $name, $project, $tag, $startDate, $endDate)
    {
        $tasks = $this->_dbHandle->prepare("SELECT * FROM hc21_20.Task ORDER BY TaskId DESC LIMIT 1");//selects the last user by id
        $tasks->execute();
        $taskArray = $tasks->fetch();
        $taskId = $taskArray[0];
        $taskId++; //incrementing taskId so that the new task is added at the bottom of the list

        $statement = $this->_dbHandle->prepare("INSERT INTO hc21_20.Task (TaskId, Name, Project, Category, StartDate, EndDate) VALUES ($taskId,?,?,?,?,?);"); //adds a new task to the task table
        $statement2 = $this->_dbHandle->prepare("INSERT INTO hc21_20.AssignedTask (TaskId, UserId) VALUES ($taskId,?)"); //adds a new task to the assignedtask table
        $statement3 = $this->_dbHandle->prepare("INSERT INTO hc21_20.CheckIn (TaskId) VALUES ($taskId)");

        $statement2->bindParam(1, $userId);

        $statement->bindParam(1, $name);
        $statement->bindParam(2, $project);
        $statement->bindParam(3, $tag);
        $statement->bindParam(4, $startDate);
        $statement->bindParam(5, $endDate);

        $statement->execute();
        $statement2->execute();
        $statement3->execute();
    }

    public function getProject($project) {//gets the record with the imputed id
        $statement = $this->_dbHandle->prepare("SELECT Task.Name, Task.Project, Task.Category, Task.Effort, Task.StartDate, Task.EndDate FROM hc21_20.Task INNER JOIN hc21_20.AssignedTask on AssignedTask.TaskId=Task.TaskId WHERE $project=Task.Project;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function getTask($id) {//gets the record with the imputed id
        $statement = $this->_dbHandle->prepare("SELECT Task.Name, Task.Project, Task.Category, AssignedTask.Effort, CheckIn.LastUpdated, Task.StartDate, Task.EndDate FROM hc21_20.Task INNER JOIN hc21_20.AssignedTask on AssignedTask.TaskId=Task.TaskId INNER JOIN hc21_20.CheckIn ON CheckIn.TaskId = Task.TaskId WHERE $id=Task.TaskId;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function gapBetweenDates($date1, $date2)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $seconds = $date1 - $date2;
        $days = $seconds / 86400;

        return $days;
    }

    public function taskCheck($id, $taskId): bool
    {
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.AssignedTask WHERE UserId = $id AND TaskId = $taskId;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TaskData($row);
        }

        if(count($dataSet) == 0)
        {
            return false;
        }
        else return true;
    }

    public function joinTask($userId, $taskId)
    {
        $statement = $this->_dbHandle->prepare("INSERT INTO hc21_20.AssignedTask (UserId, TaskId) VALUES ($userId, $taskId);");

        $statement->execute();
    }

    public function checkIn($userId, $taskId, $effort, $date)
    {
        $statement = $this->_dbHandle->prepare("UPDATE hc21_20.AssignedTask SET Effort = ? WHERE TaskId = $taskId AND UserId = $userId");
        $statement2 = $this->_dbHandle->prepare("UPDATE hc21_20.CheckIn SET LastUpdated = ? WHERE TaskId = $taskId");

        $statement->bindParam(1, $effort);

        $statement2->bindParam(1, $date);

        $statement->execute();
        $statement2->execute();
    }

    public function getThoughts($id) {//gets the record with the imputed id
        $statement = $this->_dbHandle->prepare("SELECT Thoughts FROM hc21_20.Thoughts INNER JOIN hc21_20.Task on Task.TaskId=Thoughts.TaskId WHERE $id=Thoughts.TaskId;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new ThoughtsData($row);
        }
        return $dataSet;
    }
    public function getIssues($id) {//gets the record with the imputed id
        $statement = $this->_dbHandle->prepare("SELECT Issue FROM hc21_20.Issues INNER JOIN hc21_20.Task on Task.TaskId=Issues.TaskId WHERE $id=Issues.TaskId;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new IssuesData($row);
        }
        return $dataSet;
    }

    public function newThought() {
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.Thoughts ORDER BY TaskId DESC LIMIT 1");//selects the last user by id
        $statement->execute();//runs the query
        return $statement->fetch();//returns the records
    }

    public function newIssue() {
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.Issues ORDER BY TaskId DESC LIMIT 1");//selects the last user by id
        $statement->execute();//runs the query
        return $statement->fetch();//returns the records
    }

    public function addThought($newId, $thought, $taskId) {
        $statement = $this->_dbHandle->prepare("INSERT INTO hc21_20.Thoughts (Thoughts, TaskId, Thoughts) VALUES ($newId,$taskId,?);");// adds a new non admin user using the imputed fields
        //sql injection blocking
        $statement->bindParam(1, $thought);
        $statement->execute();//runs the query
    }

    public function addIssue($newId, $issue, $taskId) {
        $statement = $this->_dbHandle->prepare("INSERT INTO hc21_20.Issues (IssueId, TaskId, Issue) VALUES ($newId,$taskId,?);");// adds a new non admin user using the imputed fields
        //sql injection blocking
        $statement->bindParam(1, $issue);
        $statement->execute();//runs the query
    }

    public function getTaskName($name) {//gets the record with the imputed id
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.Task WHERE '$name'=Task.Name;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function getTaskNameFromUser($name) {//gets the record with the imputed id
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.Task INNER JOIN hc21_20.AssignedTask ON AssignedTask.TaskId = Task.TaskId INNER JOIN hc21_20.User ON User.UserId = AssignedTask.UserId WHERE '$name'=User.Username;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function getTaskNameFromProject($name) {//gets the record with the imputed id
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.Task WHERE '$name'=Task.Project;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function getTaskNameFromTag($name) {//gets the record with the imputed id
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.Task WHERE '$name'=Task.Category;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function getTaskNameFromStartDate($name) {//gets the record with the imputed id
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.Task WHERE '$name'=Task.StartDate;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }

    public function getTaskNameFromEndDate($name) {//gets the record with the imputed id
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.Task WHERE '$name'=Task.EndDate;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }
}