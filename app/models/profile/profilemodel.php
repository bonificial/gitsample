<?php
include_once 'config/dbh.php';

class Profile extends Dbh{
    
    public $id_profile;
    public $title;
    public $bio;
    public $image;
    public $ratings;
    public $hourly_price;
    public $id_user;

    public function create_profile($id_user){
        $sql = "INSERT INTO profile (id_user) VALUES(?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id_user]);
        return array('message' => 'account successfully created!','status' => 'success');
        $stmt = null;
    }
    protected function add_portfolio($id_profile,$skills,$experience=''){
        $sql = "INSERT INTO portfolio (skills,experience,id_profile) VALUES(?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$skills,$experience,$id_profile]);
        return array('message' => 'portfolio successfully added!','status' => 'success');
        $stmt = null;
    }
}