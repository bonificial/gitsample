<?php
include_once 'config/dbh.php';

class Klass extends Dbh{
    
    public $id_class;
    public $title;
    public $start_date;
    public $end_date;
    public $creation_date;
    public $update_date;
    public $price;
    public $number_lessons;
    public $rating;
    public $id_user;
     
}