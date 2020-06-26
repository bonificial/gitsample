<?php
include_once 'config/dbh.php';
       use PHPMailer\PHPMailer\PHPMailer;
       use PHPMailer\PHPMailer\Exception;
require 'config/includes/autoloader.inc.php';
class emailHelper extends Dbh
{
    public function sendPasswordReset_Email($email,$user)
    {
        /* Create a new PHPMailer object. Passing TRUE to the constructor enables exceptions. */
        $mail = new PHPMailer(TRUE);

        /* Open the try/catch block. */
        try {
            /* Set the mail sender. */
            $mail->setFrom('dispatch.owera@gmail.com', 'Owera Dispatch');

            /* Add a recipient. */
            $mail->addAddress($email, 'Recipient');

            /* Set the subject. */
            $mail->Subject = 'Password Reset';

            /* Set the mail message body. */
            $mail->Body = 'Password Reset Email.';

            /* Finally send the mail. */
           echo $mail->send();
        }
        catch (Exception $e)
        {
            /* PHPMailer exception. */
            echo $e->errorMessage();
        }
        catch (\Exception $e)
        {
            /* PHP exception (note the backslash to select the global namespace Exception class). */
            echo $e->getMessage();
        }
    }
}