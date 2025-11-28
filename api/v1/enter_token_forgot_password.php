<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use PHPMailer\PHPMailer\PHPMailer;

function msg($success, $status, $message, $extra = [])
{
    return array_merge(['success' => $success, 'status' => $status, 'message' => $message], $extra);
}

require __DIR__ . '/classes/Database.php';
require __DIR__ . '/classes/JwtHandler.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// IF REQUEST METHOD IS NOT EQUAL TO POST
if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    $returnData = msg(1, 404, 'Page Not Found!');
}
// CHECKING EMPTY FIELDS
elseif (!isset($data->email) || empty(trim($data->email)) || !isset($data->token) || empty(trim($data->token)))
{

    $returnData = msg(1, 422, 'Please Fill in all Required Fields!');
}
// IF THERE ARE NO EMPTY FIELDS THEN-
else
{
    $email = trim($data->email);
    $token = trim($data->token);

    // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $returnData = msg(1, 422, 'Invalid Email Address!');
    }
    // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
    else
    {
        try
        {
            $query = "SELECT * FROM `students` WHERE `reset_link_token`='" . $token . "' and `email`='" . $email . "';";
            $query_stmt = $conn->prepare($query);
            $query_stmt->execute();
            $curDate = date("Y-m-d H:i:s");
            if ($query_stmt->rowCount())
            {
                $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                if ($row['exp_date'] >= $curDate)
                {
                    $returnData = [
                            'success' => 1,
                            'tokenMatched' => true,
                            'message' => 'Token validated successfully.'
                        ];
                } else {
                    $returnData = [
                            'success' => 1,
                            'tokenMatched' => false,
                            'message' => 'Token Expired.!'
                        ];
                }
            }
            else
            {   
                $returnData = [
                            'success' => 1,
                            'tokenMatched' => false,
                            'message' => 'Incorrect Token.'
                        ];
            }
        }
        catch(PDOException $e)
        {
            $returnData = msg(1, 500, $e->getMessage());
        }

    }

}

header('Content-Type: application/json');
echo json_encode($returnData);

