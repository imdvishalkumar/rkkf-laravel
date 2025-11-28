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
elseif(!isset($data->playerId)
    || empty(trim($data->playerId))
    ){

    $fields = ['fields' => ['playerId']];
    $returnData = msg(1,422,'Please Fill in all Required Fields!',$fields);
}
// IF THERE ARE NO EMPTY FIELDS THEN-
else{
    $playerId = trim($data->playerId);

    // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
    if(false){
    }
    // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
    else{
        try{
            
            $fetch_user_by_email = "UPDATE `devices` SET `is_active` = '0' WHERE `devices`.`player_id` =:playerId";
            $query_stmt = $conn->prepare($fetch_user_by_email);
            $query_stmt->bindValue(':playerId', $playerId,PDO::PARAM_STR);
            

            // IF THE USER IS FOUNDED BY EMAIL
            if($query_stmt->execute()){
                
                    $returnData = [
                        'success' => 1,
                        'done' => 1,
                        'message' => 'You have successfully logged out.'
                    ];
                
            }
            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else{
                    $returnData = msg(1,422,'Invalid Password!');
                }
        }
        catch(PDOException $e){
            $returnData = msg(1,500,$e->getMessage());
        }

    }

}

header('Content-Type: application/json');
echo json_encode($returnData);