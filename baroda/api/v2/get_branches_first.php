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

$returnData = [];

// IF REQUEST METHOD IS NOT EQUAL TO POST
if($_SERVER["REQUEST_METHOD"] != "POST"){
    $returnData = msg(1,404,'Page Not Found!');
}
// IF THERE ARE NO EMPTY FIELDS THEN-
else{
        try{
            
            $fetch_user_by_email = "SELECT value FROM `branch_text` WHERE id = 1";
            $query_stmt = $conn->prepare($fetch_user_by_email);
            $query_stmt->execute();

            // IF THE USER IS FOUNDED BY EMAIL
            if($query_stmt->rowCount() > 0){
                $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                $returnData = [
                    "success" => 1,
                    "done" => 1,
                    "value" => $row['value']
                ];
            }
            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else{
                $returnData = msg(1,422,'No Branch data found!');
            }
        }
        catch(PDOException $e){
            $returnData = msg(1,500,$e->getMessage());
        }

    

}

header('Content-Type: application/json');
echo json_encode($returnData);