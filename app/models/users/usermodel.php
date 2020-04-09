<?php
include_once 'config/dbh.php';

class User extends Dbh{
    public $_id;
    public $account_id;
    public $name;
    public $birthdate;
    public $phone;
    public $email;
    public $adress;
    public $nationality;

    public function create_user($name,$birthdate,$phone,$email,$address,$nationality,$id_account){
        $sql = "INSERT INTO users (name,birthdate,phone,email,address,nationality,id_account) VALUES(?,?,?,?,?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$name,$birthdate,$phone,$email,$address,$nationality,$id_account]);

        return array('success' => 'account successfully created!');
        $stmt = null;
    }
}