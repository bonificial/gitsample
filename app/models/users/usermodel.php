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

        return array('message' => 'account successfully created!','status' => 'success');
        $stmt = null;
    }
    public function log_user_in($id_account){
        $sql = "SELECT * FROM users WHERE id_account=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id_account]);
        $result = $stmt->fetch();
        $result['status'] = 'success';
        return $result;
        $stmt = null;
        
    }
    protected  function update_user_info($user){
        $sql = isset($user['phone'])?("UPDATE users SET name=?, birthdate=?, phone=?, email=?, address=?, nationality=? WHERE id_user=?"):
               ("UPDATE users SET name=?, birthdate=?, email=?, address=?, nationality=? WHERE id_user=?");
        $stmt = $this->connect()->prepare($sql);
        isset($user['phone'])?($stmt->execute([$user['name'],$user['birthdate'],isset($user['phone'])?$user['phone']:'',$user['email'],$user['address'],$user['nationality'],$user['id_user']])):
        ($stmt->execute([$user['name'],$user['birthdate'],$user['email'],$user['address'],$user['nationality'],$user['id_user']]));
        return  array('message' => 'user information successfully updated', 'status' => 'success');
        $stmt = null;
    }
}