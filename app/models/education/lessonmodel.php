<?php
include_once 'config/dbh.php';

class Lesson extends Dbh{
    
    public $id_lesson;
    public $title;
    public $description;
    public $content;
    public $creation_date;
    
}