<?php
include_once 'config/dbhmodel.php';

class Meetings extends Dbh{
    
    public $_id;
    public $proposed_time;
    public $proposed_date;
    public $proposed_by;
    public $from_user;
    public $to_user;
    public $status;
        
}