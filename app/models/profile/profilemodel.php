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

    public function create_profile($id_user,$fname,$lname){
        $sql = "INSERT INTO profile (id_user,fname,lname) VALUES(?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id_user,$fname,$lname]);
        return array('message' => 'account successfully created!','status' => 'success');
        $stmt = null;
    }
    protected function update_profile($data){
        $Details = $this->get_profile($data['id_profile']);
        $valid_extensions = array('jpeg', 'jpg', 'png');
        $path = 'uploads/';
        $img = isset($_FILES["image"]["name"])?$_FILES["image"]["name"]: null; 
        $tmp = isset($_FILES["image"]["tmp_name"])?$_FILES["image"]["tmp_name"]:null; 
        $errorimg = isset($_FILES["image"]["error"])?$_FILES["image"]["error"]: null;
        if($img== null) ;
        else{
            $final_image = strtolower(rand(1000,1000000).$img);
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            if(in_array($ext, $valid_extensions)){ 
               if($Details['image'] !==""){
                  if($Details['image'] !== null)
                  unlink($path.$Details['image']);
               }
                $path = $path.strtolower($final_image);
                move_uploaded_file($tmp,$path);
            }else{
                return array('message' => 'Invalid Image', 'status' => 'error');
            }
           }

        $sql = ($img== null)?"UPDATE profile SET title=?, bio=?, hourly_price=? WHERE id_profile=?":
                          "UPDATE profile SET title=?, bio=?, image=?, hourly_price=? WHERE id_profile=?";
        $stmt = $this->connect()->prepare($sql);
        ($img== null)?$stmt->execute([isset($data['title'])?$data['title']:'',isset($data['bio'])?$data['bio']:'',isset($data['hourly_price'])?$data['hourly_price']:0,$data['id_profile']]):
                   $stmt->execute([isset($data['title'])?$data['title']:'',isset($data['bio'])?$data['bio']:'',$final_image,isset($data['hourly_price'])?$data['hourly_price']:0,$data['id_profile']]);
        return array('message' => 'profile successfully updated!','status' => 'success');
        $stmt = null;
    }
    protected function get_profile($id_profile){
        $sql = "SELECT profile.* FROM profile WHERE id_profile=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id_profile]);
        $result = $stmt->fetch();
        return $result;
        $stmt = null;
    }
    protected function get_profiles(){
        $sql = "SELECT * FROM profile";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
        $stmt = null;
    }

}