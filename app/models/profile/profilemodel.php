<?php
include_once 'config/dbhmodel.php';

class Profile extends Dbh{
    
    public $id_profile;
    public $title;
    public $bio;
    public $image;
    public $ratings;
    public $hourly_price;
    public $id_user;
}