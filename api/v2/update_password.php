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
    $returnData = msg(0, 404, 'Page Not Found!');
}
// CHECKING EMPTY FIELDS
elseif (!isset($data->email) || empty(trim($data->email)) || !isset($data->password) || empty(trim($data->password)))
{

    $returnData = msg(0, 422, 'Please Fill in all Required Fields!');
}
// IF THERE ARE NO EMPTY FIELDS THEN-
else
{
    $email = trim($data->email);
    $password = trim($data->password);

    // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $returnData = msg(0, 422, 'Invalid Email Address!');
    }
    // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
    else
    {
        try
        {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $update = "UPDATE students set  password='" . $hash . "', reset_link_token='" . NULL . "' ,exp_date='" . NULL . "' WHERE email='" . $email . "'";
            $query_stmt = $conn->prepare($update);
            if ($query_stmt->execute()) {
                $returnData = [
                            'success' => 1,
                            'updated' => true,
                            'message' => 'Passwrod Updated Successfully.'
                        ];
            } else {
                $returnData = [
                            'success' => 1,
                            'updated' => false,
                            'message' => 'Error while Updating Password.'
                        ];
            }

        }
        catch(PDOException $e)
        {
            $returnData = msg(0, 500, $e->getMessage());
        }

    }

}

header('Content-Type: application/json');
echo json_encode($returnData);