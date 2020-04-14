<?php
include_once 'config/dbh.php';

class Message extends Dbh{
    public $_id;
    public $message;
    public $created_at;
    public $delete_request;
    public $parent_message;
    public $creator_id;
    
}