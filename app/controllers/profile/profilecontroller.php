
<?php

include_once 'app/models/profile/profilemodel.php';
class ProfileController extends Profile{
   private $errors;

   public function addPortfolio($request, $response, $args){
        $portfolio = self::validate_portfolio($request->getParsedBody());
        if($this->errors != null){
        $response->getBody()->write(json_encode($this->_rename_arr_key()));
        return $response
                    ->withHeader('Content-Type', 'application/json');
        }else{
        $response->getBody()->write(json_encode($this->add_portfolio($portfolio['id_profile'],$portfolio['skills'],isset($portfolio['experience'])?$portfolio['experience']:'')));
        return $response
                ->withHeader('Content-Type', 'application/json');
        }
  }
  public function updatePortfolio($request, $response, $args){
    $portfolio = self::validate_portfolio_update($request->getParsedBody());
    if($this->errors != null){
    $response->getBody()->write(json_encode($this->_rename_arr_key($this->errors)));
    return $response
                ->withHeader('Content-Type', 'application/json');
    }else{
        $ams = isset($portfolio['experience']);
    $response->getBody()->write(json_encode($this->update_portfolio($portfolio['id_portfolio'],$portfolio['skills'],isset($portfolio['experience'])?$portfolio['experience']:null)));
    return $response
            ->withHeader('Content-Type', 'application/json');
    }
  }
  public function _rename_arr_key() {
    $key = array_keys($this->errors);
    return array('message' => $this->errors[$key[0]],'status' => 'error');
  }
  public function validate_portfolio($req){
    require "gump.class.php";
    
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
        return $this->errors = $gump->get_errors_array(true);
    } else {
        return $validated_data; // validation successful
    }
}
public function validate_portfolio_update($req){
    require "gump.class.php";
    
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
        return $this->errors = $gump->get_errors_array(true);
    } else {
        return $validated_data; // validation successful
    }
}
}