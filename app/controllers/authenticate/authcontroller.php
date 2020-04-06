<?php

include_once 'app/models/authenticate/accountmodel.php';

class AuthController extends Account{

public function login($name){
    $data = $this->login_user($name);
    return json_encode($data);
}

}