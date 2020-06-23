<?php
include_once 'config/dbh.php';
include_once 'app/models/profile/profilemodel.php';
class User extends Dbh{
    public $id_user;
    public $email;
    public $password;
    public $fname;
    public $lname;

    protected function create_user_account($account_details){
        if($this->does_email_exist($account_details['email'])){ return array('message' => 'email already exist!', 'status' => 'error');}
        $hash = md5(rand(0,1000));
        $sql = " INSERT INTO users (email,password,hash,status) VALUES(?,?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $ref = $stmt->execute([$account_details['email'],$account_details['password'],$hash,'pending']);
        $stmt = null;
        //ams: this query is not efficient although it works. I should be using lastInsertId()
        //but due to some unknown reasons, it is not working. so i will revise it later
        $stmt2 = $this->connect()->prepare("SELECT id_user FROM users WHERE email=? AND password=? AND hash=?");
        $stmt2->execute([$account_details['email'],$account_details['password'],$hash]);
        $result = $stmt2->fetch();
        return (new Profile())->create_profile($result['id_user'],$account_details['fname'],$account_details['lname']);
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
        if($res === true) return  self::log_user_in($this->id_user);
        else if($res === false) return array('message' => 'password is incorrect!', 'status' => 'error');
        else return $res;
    }
    protected function verify_this_account($account_details){
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$account_details['email']]);
        $result = $stmt->fetch();
        if(!$result ){
            return array('message' => 'email doesn\'t exist!', 'status' => 'error');
            $stmt = null;
        }else{
            $this->id_user = $result['id_user'];
            if($result['status'] == 'pending') return array('message' => 'Activate your account to login. Activation link was sent to your email!', 'status' => 'error');
            else return password_verify($account_details['password'], $result['password']);
            $stmt = null;
        }
    }

    public function log_user_in($id_user){
        $sql = "SELECT users.id_user,users.email,profile.id_profile,profile.fname,profile.lname FROM users INNER JOIN profile WHERE users.id_user=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id_user]);
        $result = $stmt->fetch();
        $result['status'] = 'success';
        return $result;
        $stmt = null;
        
    }
    // protected  function update_user_info($user){
    //     $sql = isset($user['phone'])?("UPDATE users SET name=?, birthdate=?, phone=?, email=?, address=?, nationality=? WHERE id_user=?"):
    //            ("UPDATE users SET name=?, birthdate=?, email=?, address=?, nationality=? WHERE id_user=?");
    //     $stmt = $this->connect()->prepare($sql);
    //     isset($user['phone'])?($stmt->execute([$user['name'],$user['birthdate'],isset($user['phone'])?$user['phone']:'',$user['email'],$user['address'],$user['nationality'],$user['id_user']])):
    //     ($stmt->execute([$user['name'],$user['birthdate'],$user['email'],$user['address'],$user['nationality'],$user['id_user']]));
    //     return  array('message' => 'user information successfully updated', 'status' => 'success');
    //     $stmt = null;
    // }
}