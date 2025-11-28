<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use PHPMailer\PHPMailer\PHPMailer;


function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

require __DIR__.'/classes/Database.php';
require __DIR__.'/classes/JwtHandler.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// IF REQUEST METHOD IS NOT EQUAL TO POST
if($_SERVER["REQUEST_METHOD"] != "POST"){
    $returnData = msg(1,404,'Page Not Found!');
}
// CHECKING EMPTY FIELDS
elseif(!isset($data->email) 
    || empty(trim($data->email))
    ){

    $returnData = msg(1,422,'Please Fill in all Required Fields!');
}
// IF THERE ARE NO EMPTY FIELDS THEN-
else{
    $email = trim($data->email);    

    // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $returnData = msg(1,422,'Invalid Email Address!');
    }
    // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
    else{
        try{
            
            $fetch_user_by_email = "SELECT * FROM `students` WHERE `email`=:email";
            $query_stmt = $conn->prepare($fetch_user_by_email);
            $query_stmt->bindValue(':email', $email,PDO::PARAM_STR);
            $query_stmt->execute();

            // IF THE USER IS FOUNDED BY EMAIL
            if($query_stmt->rowCount()){
                
                $token = md5($email).rand(10,9999);
                $expFormat = mktime(date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y"));
                $expDate = date("Y-m-d H:i:s",$expFormat);
                $update = "UPDATE students set reset_link_token='" . $token . "' ,exp_date='" . $expDate . "' WHERE email='" . $email . "'";
                $link = $token;

                $query_stmt = $conn->prepare($update);
                $query_stmt->execute();

                require '../../vendor/autoload.php';

                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->SMTPAuth = true;
                $mail->Username = 'kenseirkkf@gmail.com';
                $mail->Password = 'm738rkkf1234';
                $mail->setFrom('admin@rkkf.org', 'Rkkf Admin');
                //$mail->addReplyTo('test@hostinger-tutorials.com', 'Your Name');
                $mail->addAddress($email, '');
                $mail->Subject = 'Forgot Password';
                $mail->msgHTML(file_get_contents('message.html'), __DIR__);
                $mail->Body = '<h5>Token : </h5>'.$link;
                //$mail->addAttachment('test.txt');
                if (!$mail->send()) {
                    $mailbool = false;
                } else {
                    $mailbool = true;
                }
                //$row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                if($mailbool){
                    $returnData = [
                        'success' => 1,
                        'done' => 1,
                        'message' => 'We have successfully sent you password reset token to your email.'
                    ];
                }
                // IF INVALID PASSWORD
                else{
                    $returnData = msg(1,422,'Failed To send email!');
                }
            }
            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else{
                $returnData = msg(1,422,'Invalid Email Address!');
            }
        }
        catch(PDOException $e){
            $returnData = msg(1,500,$e->getMessage());
        }

    }

}

header('Content-Type: application/json');
echo json_encode($returnData);