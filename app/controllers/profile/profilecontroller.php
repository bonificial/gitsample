
<?php

include_once 'app/models/profile/profilemodel.php';
class ProfileController extends Profile{

    public function index(){
        
    }
    public function update($request, $response, $args){
        $response->getBody()->write(json_encode($this->update_profile($request->getParsedBody())));
        return $response
                ->withHeader('Content-Type', 'application/json');
    }
}