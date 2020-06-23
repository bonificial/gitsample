<?php
include_once 'config/dbh.php';
include_once 'app/models/users/usermodel.php';

class Account extends Dbh{

    public $id_account;
    public $username;
    public $password;

    private $success = 200;
    private $fail = 400;


}