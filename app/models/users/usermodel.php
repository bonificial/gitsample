<?php
include_once 'app/Helpers/Helpers.php';
include_once 'app/models/profile/profilemodel.php';

class User extends dbHelper
{
    public $id_user;
    public $email;
    public $password;
    public $fname;
    public $lname;

    protected function updateAccountPassword($user_id, $data){
        $sql = "UPDATE users SET password=? WHERE users.id_user=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$data['password'],$user_id]);
        $stmt = null;

        return array('message' => 'Password Reset Successful', 'status' => 'success');
    }

    protected function create_user_account($account_details)
    {
        if ($this->does_email_exist($account_details['email'])) {
            return array('message' => 'email already exist!', 'status' => 'error');
        }
        $hash = md5(rand(0, 1000));
        $sql = " INSERT INTO users (email,password,hash,status) VALUES(?,?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$account_details['email'], $account_details['password'], $hash, 'pending']);
        $stmt = null;
        //ams: this query is not efficient although it works. I should be using lastInsertId()
        //but due to some unknown reasons, it is not working. so i will revise it later
        $stmt2 = $this->connect()->prepare("SELECT id_user FROM users WHERE email=? AND password=? AND hash=?");
        $stmt2->execute([$account_details['email'], $account_details['password'], $hash]);
        $result = $stmt2->fetch();
        return (new Profile())->create_profile($result['id_user'], $account_details['fname'], $account_details['lname'], $account_details['email'], $hash);
    }

    protected function does_email_exist($email)
    {
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        if (!$result) {
            return false;
            $stmt = null;
        } else {
            return true;
            $stmt = null;
        }
    }

    protected function create_password_reset_request($email)
    {
        $emailer = new emailHelper();
        $user= $this->retrieve_fields_by_col_val('email', $email,'users');
          // return $user;
        $resetToken = sha1((new miscHelper())->generateRandomString());
        $resetTokenExpiry = time() + (2*60*60); //Token is valid for two hours only
        $sql = "UPDATE users SET accessResetToken=?, accessResetTokenExpiry=? WHERE users.id_user=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$resetToken, $resetTokenExpiry, $user['id_user']]);

        $stmt = null;
        return array('message' => 'Reset Request Successful', 'status' => 'success');
       // $emailer->sendPasswordReset_Email($email,$user);            //Disabled until Mailing function is enabled
    }
protected function isTokenExpired($tokenExpiry){
        return time()>$tokenExpiry; // If true, then current time is ahead of token expiry time, so its expired.
}


    protected function login_user($account_details)
    {
        $res = $this->verify_this_account($account_details);
        if ($res === true) return self::log_user_in($this->id_user);
        else if ($res === false) return array('message' => 'password is incorrect!', 'status' => 'error');
        else return $res;
    }

    protected function verify_this_account($account_details)
    {
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$account_details['email']]);
        $result = $stmt->fetch();
        if (!$result) {
            $stmt = null;
            return array('message' => 'email doesn\'t exist!', 'status' => 'error');

        } else {
            $this->id_user = $result['id_user'];
            $stmt = null;
            if ($result['status'] == 'pending') return array('message' => 'Activate your account to login. An activation link was sent to your email!', 'status' => 'error');
            else return password_verify($account_details['password'], $result['password']);

        }
    }

    public function log_user_in($id_user)
    {
        $sql = "SELECT users.id_user,users.email,profile.id_profile,profile.fname,profile.lname FROM users INNER JOIN profile ON users.id_user=profile.id_user WHERE users.id_user=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id_user]);
        $result = $stmt->fetch();
        $result['status'] = 'success';
        return $result;
        $stmt = null;

    }

    protected function activate_account($hash)
    {
        $sql = "SELECT id_user,status FROM users WHERE users.hash=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$hash]);
        $result = $stmt->fetch();
        if (!$result) return array('message' => 'This link is not valid!', 'status' => 'error');
        if ($result['status'] == 'active') return array('message' => 'Your acount has already been activated!', 'status' => 'error');
        if ($result['status'] == 'pending') return $this->change_status($result['id_user']);
        $stmt = null;
    }

    protected function change_status($id_user)
    {
        $hash = md5(rand(0, 1000));
        $sql = "UPDATE users SET status=?, hash=? WHERE users.id_user=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['active', $hash, $id_user]);
        $stmt = null;
        return array('message' => 'Your account has been successfully activated!', 'status' => 'success');

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