<?php

require_once 'app/models/authenticate/accountmodel.php';

class AuthController extends Account{
    private $errors;

    public function signup($request, $response, $args){
      $validated_data = self::validate_data($request->getParsedBody());
      if($this->errors != null){
        $response->getBody()->write(json_encode($this->_rename_arr_key()));
        return $response
                   ->withHeader('Content-Type', 'application/json');
      }else{
      $validated_data['password'] = password_hash($validated_data['password'], PASSWORD_DEFAULT);
      $response->getBody()->write(json_encode($this->create_user_account($validated_data)));
      return $response
                ->withHeader('Content-Type', 'application/json');
      }
    }
    public function login($request, $response, $args){
      $validated_data = self::validate_login_data($request->getParsedBody());
      if($this->errors != null){
        $response->getBody()->write(json_encode($this->_rename_arr_key()));
        return $response
                   ->withHeader('Content-Type', 'application/json');
      }else{
        $response->getBody()->write(json_encode($this->login_user($validated_data)));
        return $response
                  ->withHeader('Content-Type', 'application/json');
      }
    }
    /**
     * ams: Helper function to rename array keys.
    */
    public function _rename_arr_key() {
      $key = array_keys($this->errors);
      return array('message' => $this->errors[$key[0]],'status' => 'error');
    }

    public function validate_data($req){
      require "gump.class.php";
      
      $gump = new GUMP();
  
      $parsedata = $gump->sanitize($req); // You don't have to sanitize, but it's safest to do so.
  
      $gump->validation_rules(array(
        'username'   => 'required|alpha_numeric|max_len,100|min_len,6',
        'password'    => 'required|max_len,100|min_len,8',
        'email'       => 'required|valid_email',
        'name'       => 'required|alpha_space|max_len,100',
        'birthdate'       => 'required|date,Y/m/d',
        'phone'       => 'numeric|max_len,20',
      ));
  
      $gump->filter_rules(array(
        'username' => 'trim|sanitize_string',
        'password' => 'trim',
        'email'    => 'trim|sanitize_email',
        'name' => 'trim|sanitize_string',
        'birthdate' => 'trim',
        'phone' => 'trim',
      ));
  
      $validated_data = $gump->run($parsedata);
  
      if($validated_data === false) {
        return $this->errors = $gump->get_errors_array(true);
      } else {
         return $validated_data; // validation successful
      }
    }
    public function validate_login_data($req){
      require "gump.class.php";
      
      $gump = new GUMP();
  
      $parsedata = $gump->sanitize($req); // You don't have to sanitize, but it's safest to do so.
  
      $gump->validation_rules(array(
        'username'   => 'required|alpha_numeric|max_len,100|min_len,6',
        'password'    => 'required|max_len,100|min_len,8'
      ));
  
      $gump->filter_rules(array(
         'username' => 'trim|sanitize_string',
         'password' => 'trim'
      ));
  
      $validated_data = $gump->run($parsedata);
  
      if($validated_data === false) {
        return $this->errors = $gump->get_errors_array(true);
      } else {
         return $validated_data; // validation successful
      }
    }
}