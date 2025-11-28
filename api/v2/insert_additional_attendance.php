<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST" );
header( "Content-Type: application/json; charset=UTF-8" );
header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );

function msg( $success, $status, $message, $extra = [] ) {
    return array_merge( [
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra );
}

require __DIR__.'/classes/Database.php';
require __DIR__.'/middlewares/Auth.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$auth = new Auth( $conn, $allHeaders );

if ( $auth->isAuth() ) {

    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $_POST['student_id'] )
        || empty( trim( $_POST['student_id'] ) )
        || !isset( $_POST['date'] )
        || empty( trim( $_POST['date'] ) )
        || !isset( $_POST['branch_id'] )
        || empty( trim( $_POST['branch_id'] ) )
        || !isset( $_POST['user_id'] )
        || empty( trim( $_POST['user_id'] ) )
    ) {
        $returnData = msg( 1, 200, 'Please Fill in all Required Fields!' );
    } else {

        $studentId = $_POST['student_id'];
        $date = $_POST['date'];
        $date = date('Y-m-d', strtotime($date));
        //echo $date;
        $branchId = $_POST['branch_id'];
        $insId = $_POST['user_id'];

        try {
             $queryCheckUser = "select role from users WHERE  user_id = $insId AND role = 2";
            $query_stmtUser = $conn->prepare( $queryCheckUser );
            $query_stmtUser->execute();

            if ( $query_stmtUser->rowCount() > 0 ) {

                $success_var = false;
                $insert = "INSERT INTO `attendance` (`attendance_id`, `student_id`, `date`, `attend`, `branch_id`, `user_id`, `is_additional`) VALUES ('', '".$studentId."','".$date."', 'P', '".$branchId."',".$insId.",1);";
                //echo $insert;
                $query_stmt = $conn->prepare( $insert );
                if ( $query_stmt->execute() ) {
                    $success_var = true;
                } else {
                    $success_var = false;
                }
                if ( $success_var ) {
                    $returnData = [
                        'success' => 1,
                        'saved' => 1,
                        'message' => 'Attendance Submited.'
                    ];
                } else {
                    $returnData = msg( 1, 422, 'Unable to submit leave try again!' );
                }}
            
        } catch( PDOException $e ) {
            $returnData = msg( 1, 500, $e->getMessage() );
        }

    }

} else {
    $returnData = msg( 1, 401, 'Unauthorized!' );
}

header( 'Content-Type: application/json' );
echo json_encode( $returnData );