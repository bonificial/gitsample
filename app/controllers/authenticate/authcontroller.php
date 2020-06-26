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

    public function reset  ($request, $response, $args){
       $token =  $args['token'];
$tokenFetch = $this->does_value_exist('accessResetToken','users',$token);

        if($tokenFetch){

            if($this->isTokenExpired($tokenFetch['accessResetTokenExpiry'])){
                $response->getBody()->write(json_encode(array('message' => 'Token Expired', 'status' => 'error')));
            }else{
                $response->getBody()->write(json_encode(array('message' => 'Token Valid', 'status' => 'success')));
            }
        }else{
            $response->getBody()->write(json_encode(array('message' => 'Token Invalid', 'status' => 'error')));
        }
        return $response  ->withHeader('Content-Type', 'application/json');
    }

    public function forgotPassword($request, $response, $args){
        $validated_data = self::validate_email($request->getParsedBody());

        if($this->errors != null){
            $response->getBody()->write(json_encode($this->_rename_arr_key()));
            return $response
                ->withHeader('Content-Type', 'application/json');
        }else{
            if($this->does_email_exist($validated_data['email'])){
                $response->getBody()->write(json_encode($this->create_password_reset_request($validated_data['email'])));
                    //Send Password Reset Email
            }else{
                //Nullify Password Reset Request
                $response->getBody()->write(json_encode('Email Not Found'));
            }
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }

    public function updateAuth($request, $response){
        $validated_data = self::validate_email_and_password($request->getParsedBody());
        $fetchedDataSet = $this->does_value_exist('email','users',$validated_data['email']);

        if($this->errors != null){
            $response->getBody()->write(json_encode($this->_rename_arr_key()));
            return $response
                ->withHeader('Content-Type', 'application/json');
        }else{
            $validated_data['password'] = password_hash($validated_data['password'], PASSWORD_DEFAULT);
            $response->getBody()->write(json_encode($this->updateAccountPassword($fetchedDataSet['id_user'],$validated_data)));
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
    public function activate($request, $response, $args){
      $response->getBody()->write(json_encode($this->activate_account($args['hash'])));
      return $response
                 ->withHeader('Content-Type', 'application/json');
    }
    /**
     * ams: Helper function to rename array keys.
    */
    public function _rename_arr_key() {
      $key = array_keys($this->errors);
      return array('message' => $this->errors[$key[0]],'status' => 'error');
    }
    public function validate_email($req){

        $gump = new GUMP();

        $parsedata = $gump->sanitize($req); // You don't have to sanitize, but it's safest to do so.

        $gump->validation_rules(array(
                         'email'       => 'required|valid_email',

        ));

        $gump->filter_rules(array(

            'email'    => 'trim|sanitize_email',

        ));

        $validated_data = $gump->run($parsedata);

        if($validated_data === false) {
            return $this->errors = $gump->get_errors_array(true);
        } else {
            return $validated_data; // validation successful
        }
    }
    public function validate_email_and_password($req){

        $gump = new GUMP();

        $parsedata = $gump->sanitize($req); // You don't have to sanitize, but it's safest to do so.

        $gump->validation_rules(array(
            'password'    => 'required|max_len,100|min_len,8',
            'email'       => 'required|valid_email',
        ));

        $gump->filter_rules(array(
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