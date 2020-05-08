<?php
include_once 'config/dbh.php';
include_once 'app/models/users/usermodel.php';

class Account extends Dbh{

    public $id_account;
    public $username;
    public $password;

    private $success = 200;
    private $fail = 200;

    protected function create_user_account($account_details){
        if($this->does_username_exist($account_details['username'])){ return array('error' => 'username already exist!');}
        if($this->does_email_exist($account_details['email'])){ return array('error' => 'email already exist!');}

        $sql = " INSERT INTO account (username,password) VALUES(?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$account_details['username'],$account_details['password']]);
        $stmt = null;
        //ams: this query is not efficient although it works. I should be using lastInsertId()
        //but due to some unknown reasons, it is not working. so i will revise it later
        $stmt2 = $this->connect()->prepare("SELECT id_account  FROM account WHERE username=? AND password=?");
        $stmt2->execute([$account_details['username'],$account_details['password']]);
        $result = $stmt2->fetch();
        return (new User())->create_user($account_details['name'],$account_details['birthdate'],$account_details['phone'],$account_details['email'],$account_details['address'],$account_details['nationality'],$result['id_account']);
    }
    protected function does_username_exist($username){
        $sql = "SELECT * FROM account WHERE username=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch();
        if(!$result ){
            return false;
            $stmt = null;
        }else{
            return  true;
            $stmt = null;
        }
    }
    protected function does_email_exist($email){
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        if(!$result ){
            return false;
            $stmt = null;
        }else{
            return  true;
            $stmt = null;
        }
    }
    protected function login_user($account_details){
        $res = $this->verify_this_account($account_details);
        if($res === true) return  (new User())->log_user_in($this->id_account);
        else if($res === false) return array('error' => 'password is incorrect!');
        else return $res;
    }
    protected function verify_this_account($account_details){
        $sql = "SELECT * FROM account WHERE username=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$account_details['username']]);
        $result = $stmt->fetch();
        if(!$result ){
            return array('error' => 'username doesn\'t exist!');
            $stmt = null;
        }else{
            $this->id_account = $result['id_account'];
            return password_verify($account_details['password'], $result['password']);
            $stmt = null;
        }
    }
}