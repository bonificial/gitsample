<?php

include_once 'app/models/users/usermodel.php';

class UserController extends User{
    private $errors;

    public function updateUser($request, $response, $args){
        $user = self::validate_userData($request->getParsedBody());
        if($this->errors != null){
        $response->getBody()->write(json_encode($this->_rename_arr_key()));
        return $response
                    ->withHeader('Content-Type', 'application/json');
        }else{
        $response->getBody()->write(json_encode($this->update_user_info($user)));
        return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }
    public function _rename_arr_key() {
        $key = array_keys($this->errors);
        return array('message' => $this->errors[$key[0]],'status' => 'error');
      }
    public function validate_userData($req){
        require "gump.class.php";
        
        $gump = new GUMP();

        $parsedata = $gump->sanitize($req); // You don't have to sanitize, but it's safest to do so.

        $gump->validation_rules(array(
            'id_user'       => 'required',
            'name'       => 'required|alpha_space|max_len,100',
            'birthdate'  => 'required|date,Y/m/d',
            'phone'      => 'numeric|max_len,20',
            'email'      => 'required|valid_email',
            'address'      => 'required',
            'nationality'      => 'required',
        ));

        $gump->filter_rules(array(
            'name' => 'trim|sanitize_string',
            'birthdate' => 'trim',
            'phone' => 'trim',
            'email'    => 'trim|sanitize_email',
            'nationality'      => 'trim',
        ));

        $validated_data = $gump->run($parsedata);

        if($validated_data === false) {
            return $this->errors = $gump->get_errors_array(true);
        } else {
            return $validated_data; // validation successful
        }
    }
}