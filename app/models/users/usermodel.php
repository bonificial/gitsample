<?php
include_once 'config/dbh.php';

class User extends Dbh{
    public $id_user;
    public $name;
    public $birthdate;
    public $phone;
    public $email;
    public $adress;
    public $nationality;
    public $id_account;

    public function create_user($name,$birthdate,$phone,$email,$address,$nationality,$id_account){
        $sql = "INSERT INTO users (name,birthdate,phone,email,address,nationality,id_account) VALUES(?,?,?,?,?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$name,$birthdate,$phone,$email,$address,$nationality,$id_account]);

        return array('success' => 'account successfully created!');
        $stmt = null;
    }
    public function log_user_in($id_account){
        $sql = "SELECT * FROM users WHERE id_account=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id_account]);
        $result = $stmt->fetch();
        return $result;
        $stmt = null;
        
    }
}