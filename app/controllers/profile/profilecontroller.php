
<?php

include_once 'app/models/profile/profilemodel.php';
include_once 'assets/validator.php';
class ProfileController extends Profile{
    private $validator;

    public function __construct(){
        $this->validator = new Validator();
    }
    public function index($request, $response, $args){
        $response->getBody()->write(json_encode($this->get_profiles()));
        return $response
                ->withHeader('Content-Type', 'application/json');
    }
    public function update($request, $response, $args){
        $profileInfo = $this->validator::validate_profileUpdate($request->getParsedBody());
        if($this->validator::$errors != null)
             $response->getBody()->write(json_encode($this->_rename_arr_key()));
        else
             $response->getBody()->write(json_encode($this->update_profile($profileInfo)));
        return $response
                ->withHeader('Content-Type', 'application/json');
    }
    public function show($request, $response, $args){
        $response->getBody()->write(json_encode($this->get_profile($args['id_profile'])));
        return $response
                ->withHeader('Content-Type', 'application/json');
    }
    public function _rename_arr_key() {
        $key = array_keys($this->validator::$errors);
        return array('message' => $this->validator::$errors[$key[0]],'status' => 'error');
    }
}