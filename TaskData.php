<?php


class TaskData
{
    protected $taskId, $name, $project, $tag, $effort, $lastUpdated, $startDate, $endDate, $user;

    public function __construct($dbRow) //deconstructs the record
    {
        @$this->taskId = $dbRow['TaskId'];
        @$this->name = $dbRow['Name'];
        @$this->project = $dbRow['Project'];
        @$this->tag = $dbRow['Category'];
        @$this->effort = $dbRow['Effort'];
        @$this->lastUpdated = $dbRow['LastUpdated'];
        @$this->startDate = $dbRow['StartDate'];
        @$this->endDate = $dbRow['EndDate'];
        @$this->user = $dbRow['Username'];
    }

    //V Getters V
    public function getTaskId() {
        return $this->taskId;
    }

    public function getName() {
        return $this->name;
    }

    public function getProject() {
        return $this->project;
    }

    public function getCategory() {
        return $this->tag;
    }

    public function getEffort() {
        return $this->effort;
    }

    public function getLastUpdated() {
        return $this->lastUpdated;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function getUser()
    {
        return $this->user;
    }
}