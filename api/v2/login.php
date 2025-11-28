<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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
$baseUrl = $db_connection->getBaseURl();

$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// IF REQUEST METHOD IS NOT EQUAL TO POST
if($_SERVER["REQUEST_METHOD"] != "POST"){
    $returnData = msg(1,404,'Page Not Found!');
}
// CHECKING EMPTY FIELDS
elseif(!isset($data->email) 
    || !isset($data->password)
    || !isset($data->playerId)
    || empty(trim($data->email))
    || empty(trim($data->password))
    || empty(trim($data->playerId))
    ){

    $fields = ['fields' => ['email','password']];
    $returnData = msg(1,422,'Please Fill in all Required Fields!',$fields);
}
// IF THERE ARE NO EMPTY FIELDS THEN-
else{
    $email = trim($data->email);
    $password = trim($data->password);

    // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $returnData = msg(1,422,'Invalid Email Address!');
    }
    // IF PASSWORD IS LESS THAN 8 THE SHOW THE ERROR
    elseif(strlen($password) < 8){
        $returnData = msg(1,422,'Your password must be at least 8 characters long!');
    }
    // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
    else{
        try{
            
            $fetch_user_by_email = "SELECT * FROM `users` WHERE `email`=:email AND `role`=2";
            $query_stmt = $conn->prepare($fetch_user_by_email);
            $query_stmt->bindValue(':email', $email,PDO::PARAM_STR);
            $query_stmt->execute();

            // IF THE USER IS FOUNDED BY EMAIL
            if($query_stmt->rowCount()){
                $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                $check_password = strcmp($password, $row['password']);
            
                // VERIFYING THE PASSWORD (IS CORRECT OR NOT?)
                // IF PASSWORD IS CORRECT THEN SEND THE LOGIN TOKEN
                if($check_password == 0){

                    $jwt = new JwtHandler();
                    $token = $jwt->_jwt_encode_data(
                        'http://localhost/php_auth_api/',
                        array("user_id"=> $row['user_id'])
                    );
                    $row['profile_img'] = $baseUrl."images/profile/ins_img.jpg";
                    $newToken = "Bearer ".$token;
                    $returnData = [
                        'success' => 1,
                        'message' => 'You have successfully logged in.',
                        'token' => $newToken,
                        'role' => 2,
                        'userdata' => $row
                    ];
                }
                // IF INVALID PASSWORD
                else{
                    $returnData = msg(1,422,'Invalid Password!');
                }
            }
            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else{
                
                // $fetch_user_by_email = "SELECT s.*, br.name as branch_name FROM students s , branch br WHERE s.email=:email AND s.active = 1 AND s.branch_id = br.branch_id";
                $fetch_user_by_email = "SELECT s.*, br.name as branch_name FROM students s , branch br WHERE s.email=:email AND s.branch_id = br.branch_id";
                $query_stmt = $conn->prepare($fetch_user_by_email);
                $query_stmt->bindValue(':email', $email,PDO::PARAM_STR);
                $query_stmt->execute();

                // IF THE USER IS FOUNDED BY EMAIL
                if($query_stmt->rowCount()){
                    $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                    $verify = password_verify($password, $row['password']); 

                    // VERIFYING THE PASSWORD (IS CORRECT OR NOT?)
                    // IF PASSWORD IS CORRECT THEN SEND THE LOGIN TOKEN
                    $deviceListed = false;
                    
                    $player = $data->playerId;
                    if($verify) {
                        if ($player != 'web' ) {
                            $queryCheck = "SELECT * from devices WHERE player_id = '".$player."'";
                        $query_stmt = $conn->prepare($queryCheck);
                        $query_stmt->execute();
                        if($query_stmt->rowCount() == 0) {
                            $insert = "INSERT INTO `devices` (`student_id`, `player_id`, `device_type`, `is_active`) VALUES ('".$row['student_id']."', '".$player."', 'Android','1');";
                            $query_stmt = $conn->prepare( $insert );
                            if ( $query_stmt->execute() ) {
                                $deviceListed = true;
                            } else {
                                $deviceListed = false;
                            }
                        } else {
                            $update = "UPDATE devices set is_active = 1 , student_id = '".$row['student_id']."' WHERE player_id = '".$player."'";
                            $query_stmt = $conn->prepare( $update );
                            if ( $query_stmt->execute() ) {
                                $deviceListed = true;
                            } else {
                                $deviceListed = false;
                            }
                        }
                        }
                        
                        $jwt = new JwtHandler();
                        $token = $jwt->_jwt_encode_data(
                            'http://localhost/php_auth_api/',
                            array("user_id"=> $row['student_id'])
                        );
                        $newToken = "Bearer ".$token;
                        if (empty($row['profile_img'])) {
                            $row['profile_img'] = $baseUrl."images/profile/ins_img.jpg";
                        } else {
                            $row['profile_img'] = $baseUrl."images/profile/" . $row['profile_img'];
                        }
                        unset($row['password']);
                        unset($row['gender']);
                        unset($row['active']);
                        unset($row['reset_link_token']);
                        unset($row['exp_date']);
                        $returnData = [
                            'success' => 1,
                            'deviceListed' => $deviceListed,
                            'message' => 'You have successfully logged in.',
                            'token' => $newToken,
                            'role' => 3,
                            'userdata' => $row
                        ];
                    }
                    // IF INVALID PASSWORD
                    else{
                        $returnData = msg(1,422,'Invalid Password!');
                    }
                }
                else{
                $returnData = msg(1,422,'Invalid Email Address!',['checkBaroda' => true]);
                }
            }
        }
        catch(PDOException $e){
            $returnData = msg(1,500,$e->getMessage());
        }

    }

}

header('Content-Type: application/json');
echo json_encode($returnData);