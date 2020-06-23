<?php

require_once 'app/models/users/usermodel.php';

class UserController extends User{
    private $validator;

    // public function __construct(){
    //     $this->validator = new Validator();
    // }

    // public function update($request, $response, $args){
    //     $user = $this->validator::validate_userData($request->getParsedBody());
    //     if($this->validator::$errors != null){
    //     $response->getBody()->write(json_encode($this->_rename_arr_key()));
    //     return $response
    //                 ->withHeader('Content-Type', 'application/json');
    //     }else{
    //     $response->getBody()->write(json_encode($this->update_user_info($user)));
    //     return $response
    //             ->withHeader('Content-Type', 'application/json');
    //     }
    // }
    // public function _rename_arr_key() {
    //     $key = array_keys($this->validator::$errors);
    //     return array('message' => $this->validator::$errors[$key[0]],'status' => 'error');
    // }
}