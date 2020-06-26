<?php
include_once 'config/dbh.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

class Profile extends DBh{
    
    public $id_profile;
    public $fname;
    public $lname;
    public $country;
    public $languages;
    public $bio;
    public $image;
    public $id_user;

    private $baseUrl = 'http://127.0.0.1:8000'; // @ams => change this when we go live

    public function create_profile($id_user,$fname,$lname,$email,$hash){
        $sql = "INSERT INTO profile (id_user,fname,lname) VALUES(?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $ref = $stmt->execute([$id_user,$fname,$lname]);
        if($ref) return $this->send_activation_email($email,$hash,$fname);
        else return array('message' => 'account creation not successful!','status' => 'error');
        $stmt = null;
    }
    protected function send_activation_email($email,$hash,$fname){
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'amsjr20@gmail.com';                     // SMTP username
            $mail->Password   = 'ams@test1';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        
            //@ams-> This needs to removed
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Recipients
            $mail->setFrom('amsjr20@gmail.com', 'Owera');
            $mail->addAddress($email, $fname);     // Add a recipient
            $mail->addReplyTo('amsjr20@gmail.com', 'Owera');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');
        
        
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Account activation';
            $mail->Body    = '<p>Hello, Welcome to Owera!<br><br>Click 
            <a href="'.$this->baseUrl.'/account/activate/'.$hash.'" target="_blank">here</a> to activate your <b>account!</b><br><br>
             We are happy to have you on board.</p>';
            $mail->AltBody = 'Hello, welcome to Owera, copy and paste '.$this->baseUrl.'/account/activate/'.$hash.' into a web url to activate your account.';
        
            $mail->send();
            return array('message' => 'Account successfully created. An activation link has been sent to your email!','status' => 'success');
        } catch (Exception $e) {
            echo "Activation email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
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
                return array('message' => 'Invalid Image type. Only jpeg,jpg and png images types are allowed', 'status' => 'error');
            }
           }

        $sql = ($img== null)?"UPDATE profile SET fname=?, lname=?, country=?, languages=?, bio=? WHERE id_profile=?":
                          "UPDATE profile SET fname=?, lname=?, country=?, languages=?, bio=?, image=? WHERE id_profile=?";
        $stmt = $this->connect()->prepare($sql);
        ($img== null)?$stmt->execute([$data['fname'],$data['lname'],isset($data['country'])?$data['country']:'',isset($data['languages'])?$data['languages']:'',isset($data['bio'])?$data['bio']:'',$data['id_profile']]):
                   $stmt->execute([$data['fname'],$data['lname'],isset($data['country'])?$data['country']:'',isset($data['languages'])?$data['languages']:'',isset($data['bio'])?$data['bio']:'',$final_image,$data['id_profile']]);
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