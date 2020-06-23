<?php

require_once 'app/models/users/usermodel.php';

class AuthController extends User{
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
      
      $gump = new GUMP();
  
      $parsedata = $gump->sanitize($req); // You don't have to sanitize, but it's safest to do so.
  
      $gump->validation_rules(array(
        'fname'       => 'required|max_len,30',
        'fname'       => 'required|max_len,30',
        'email'       => 'required|valid_email',
        'password'    => 'required|max_len,100|min_len,8',
      ));
  
      $gump->filter_rules(array(
        'fname' => 'trim|sanitize_string',
        'lname' => 'trim|sanitize_string',
        'email'    => 'trim|sanitize_email',
        'password' => 'trim',
      ));
  
      $validated_data = $gump->run($parsedata);
  
      if($validated_data === false) {
        return $this->errors = $gump->get_errors_array(true);
      } else {
         return $validated_data; // validation successful
      }
    }
    public function validate_login_data($req){
      
      $gump = new GUMP();
  
      $parsedata = $gump->sanitize($req); // You don't have to sanitize, but it's safest to do so.
  
      $gump->validation_rules(array(
        'email'       => 'required|valid_email',
        'password'    => 'required|max_len,100|min_len,8'
      ));
  
      $gump->filter_rules(array(
         'email'    => 'trim|sanitize_email',
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