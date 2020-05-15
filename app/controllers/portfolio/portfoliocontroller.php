<?php

require_once 'app/models/profile/portfoliomodel.php';
require_once 'assets/validations.php';
class PortfolioController extends Portfolio{
    private $validator;

    public function __construct(){
        $this->validator = new Validator();
    }
    public function add($request, $response, $args){
        $portfolio = $this->validator::validate_portfolio($request->getParsedBody());
        if($this->validator::$errors != null){
        $response->getBody()->write(json_encode($this->_rename_arr_key()));
        return $response
                    ->withHeader('Content-Type', 'application/json');
        }else{
        $response->getBody()->write(json_encode($this->add_portfolio($portfolio['id_profile'],$portfolio['skills'],isset($portfolio['experience'])?$portfolio['experience']:'')));
        return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }
    public function update($request, $response, $args){
        $portfolio = $this->validator::validate_portfolio_update($request->getParsedBody());
        if($this->validator::$errors != null){
        $response->getBody()->write(json_encode($this->_rename_arr_key()));
        return $response
                    ->withHeader('Content-Type', 'application/json');
        }else{
        $response->getBody()->write(json_encode($this->update_portfolio($portfolio['id_portfolio'],$portfolio['skills'],isset($portfolio['experience'])?$portfolio['experience']:null)));
        return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }
    public function delete($request, $response, $args){
        /*
        * $args['id_portfolio'] and getAttribute('id_portfolio')
        * both return the value
        **/
        $response->getBody()->write(json_encode($this->delete_portfolio($args['id_portfolio'])));
        return $response
                ->withHeader('Content-Type', 'application/json');

    }
    public function _rename_arr_key() {
        $key = array_keys($this->validator::$errors);
        return array('message' => $this->validator::$errors[$key[0]],'status' => 'error');
    }

}