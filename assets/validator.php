<?php
class Validator{
    public static $errors = null;
    public static function validate_userData($req){
        
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
            return self::$errors = $gump->get_errors_array(true);
        } else {
            return $validated_data; // validation successful
        }
    }
    public static function validate_profileUpdate($req){
        $gump = new GUMP();

        $parsedata = $gump->sanitize($req); // You don't have to sanitize, but it's safest to do so.

        $gump->validation_rules(array(
            'fname'       => 'required|max_len,30',
            'lname'      => 'required|max_len,30'
        ));

        $gump->filter_rules(array(
            'fname' => 'trim|sanitize_string',
            'lname' => 'trim|sanitize_string',
        ));

        $validated_data = $gump->run($parsedata);

        if($validated_data === false) {
            return self::$errors = $gump->get_errors_array(true);
        } else {
            return $validated_data; // validation successful
        }
    }
    public static function validate_portfolio($req){
        
        $gump = new GUMP();
    
        $parsedata = $gump->sanitize($req); // You don't have to sanitize, but it's safest to do so.
    
        $gump->validation_rules(array(
            'id_profile'       => 'required|numeric',
            'skills'       => 'required|alpha_space',
            'experience'  => 'alpha_space',
        ));
    
        $gump->filter_rules(array(
            'skills' => 'trim|sanitize_string',
            'experience' => 'trim'
        ));
    
        $validated_data = $gump->run($parsedata);
    
        if($validated_data === false) {
            return self::$errors = $gump->get_errors_array(true);
        } else {
            return $validated_data; // validation successful
        }
    }
    public static function validate_portfolio_update($req){
        
        $gump = new GUMP();
       
        $parsedata = $gump->sanitize($req); // You don't have to sanitize, but it's safest to do so.
       
        $gump->validation_rules(array(
            'id_portfolio'       => 'required|numeric',
            'skills'       => 'required|alpha_space',
            'experience'  => 'alpha_space',
        ));
       
        $gump->filter_rules(array(
            'skills' => 'trim|sanitize_string',
            'experience' => 'trim'
        ));
       
        $validated_data = $gump->run($parsedata);
       
        if($validated_data === false) {
            return  self::$errors = $gump->get_errors_array(true);
        } else {
            return $validated_data; // validation successful
        }
    }
}