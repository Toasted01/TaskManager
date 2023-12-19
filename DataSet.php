<?php

require_once ('Database.php');
require_once ('Data.php');
require_once ('UserView.php');

class DataSet {
    protected $_dbHandle, $_dbInstance;
        
    public function __construct() {//loads the database
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function authenticate($_user, $password ) {
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.User WHERE Username = ? AND Password = ?"); //finds any records that have the imputed username and password
        //sql injection blocking
        $statement->bindParam(1, $_user);
        $statement->bindParam(2, $password);
        $statement->execute();//runs the query

        return $statement->fetch();//returns the records
    }

    public function checkName($_user) {
        $statement = $this->_dbHandle->prepare("SELECT Username FROM hc21_20.User WHERE Username = ?"); //selects all records that match the username imputed
        //sql injection blocking
        $statement->bindParam(1, $_user);
        $statement->execute();//runs the query

        return $statement->fetch();//returns the records
    }

    public function newUser() {
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.User ORDER BY UsersId DESC LIMIT 1"); //selects the last user by id
        $statement->execute();//runs the query
        return $statement->fetch();//returns the records
    }

    public function checkFreeName($name): bool
    {
        $statement = $this->_dbHandle->prepare("SELECT COUNT * FROM hc21_20.User WHERE Username = ?");
        $statement->bindParam(1, $name);
        $statement->execute();
        $nameCount = $statement->fetch();
        if ($nameCount > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function addUser($name, $pass, $isAdmin) {
        $generateId = $this->_dbHandle->prepare("SELECT UserId FROM hc21_20.User ORDER BY UserId DESC LIMIT 1");//selects the last user by id
        $generateId->execute();
        $idArray = $generateId->fetch();
        $generateId = $idArray[0];
        $generateId++;
        $statement = $this->_dbHandle->prepare("INSERT INTO hc21_20.User (UserId, Username, Password, IsAdmin) VALUES ($generateId,?,?,?);");// adds a new user using the inputted fields
        //sql injection blocking
        $pass = sha1($pass);
        $statement->bindParam(1, $name);
        $statement->bindParam(2, $pass);
        $statement->bindParam(3, $isAdmin);
        $statement->execute();//runs the query
    }

    public function getUsers(): array
    {
        $statement = $this->_dbHandle->prepare("SELECT User.Username, User.IsAdmin, COUNT(AssignedTask.UserId) FROM hc21_20.User LEFT JOIN hc21_20.AssignedTask on AssignedTask.UserId=User.UserId GROUP BY User.UserId");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {//adds all rows to the array
            $dataSet[] = new UserView($row);
        }
        return $dataSet;
    }

    public function deleteUser($name)
    {
        $statement = $this->_dbHandle->prepare("DELETE User.*, AssignedTask.* FROM hc21_20.User INNER JOIN hc21_20.AssignedTask on AssignedTask.UserId=User.UserId WHERE User.Username = $name");
        $statement->execute();
    }

    public function currentUser($id, $name)
    {
        $statement = $this->_dbHandle->prepare("SELECT Username FROM hc21_20.User WHERE UserId = $id");
        $statement->execute();

        while ($row = $statement->fetch()) {//adds all rows to the array
            $dataSet[] = new UserView($row);
        }

        $rowId = $dataSet[0]->getUser();

        if(strcmp($name, $rowId) == 0)
        {
            return true;
        }
        else return false;
    }

    public function getUser($name)
    {
        $statement = $this->_dbHandle->prepare("SELECT * FROM hc21_20.User WHERE '$name'=User.Username;");
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TaskData($row);
        }
        return $dataSet;
    }


}

