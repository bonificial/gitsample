<?php
include_once 'config/dbh.php';

class Job extends Dbh{
    
    public $id_job;
    public $title;
    public $tasks;
    public $start_date;
    public $end_date;
    public $update_date;
    public $creation_date;
    public $description;
    public $price;
    public $id_user;

}