<?php
include_once 'config/dbhmodel.php';

class Topic extends Dbh{
    
    public $id_topic;
    public $name;
    public $offeredBy;
    public $charge;
    public $chargeString;
    public $id_category;
        
}