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
        $returnData = msg( 0, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $_POST['s_id'] )
        || empty( trim( $_POST['s_id'] ) )
        || !isset( $_POST['from_date'] )
        || empty( trim( $_POST['from_date'] ) )
        || !isset( $_POST['to_date'] )
        || empty( trim( $_POST['to_date'] ) )
        || !isset( $_POST['reason'] )
        || empty( trim( $_POST['reason'] ) )
    ) {
        $returnData = msg( 1, 200, 'Please Fill in all Required Fields!' );
    }

    else {
        $student_id = trim( $_POST['s_id'] );
        $fromDate = trim( $_POST['from_date'] );
        $fromDate = date('Y-m-d', strtotime($fromDate));
        $toDate = trim( $_POST['to_date'] );
        $toDate = date('Y-m-d', strtotime($toDate));
        $reason = trim( $_POST['reason'] ); 
        
        // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
        if ( !filter_var( $student_id, FILTER_VALIDATE_INT ) ) {
            $returnData = msg( 0, 422, 'Invalid ID!' );
        }
        else {
            try {
                $queryCheck = "SELECT * from leave_table WHERE (('".$fromDate."' BETWEEN from_date AND to_date) AND ('".$toDate."' BETWEEN from_date AND to_date)) AND student_id =".$student_id;
                $query_stmt = $conn->prepare($queryCheck);
                $query_stmt->execute();
                if($query_stmt->rowCount() == 0) {
                    $insert = "INSERT INTO `leave_table` (`leave_id`, `student_id`, `from_date`, `to_date`, `reason`) VALUES ('', '".$student_id."', '".$fromDate."', '".$toDate."', '".$reason."');";
                    $query_stmt = $conn->prepare( $insert );
                    if ( $query_stmt->execute() ) {
                        $returnData = [
                            'success' => 1,
                            'saved' => 1,
                            'message' => 'Leave Submited.'
                        ];
                    }
                    else {
                        $returnData = msg( 1, 422, 'Unable to submit leave try again!' );
                    }
                } else {
                    $returnData = msg( 1, 200, 'Leave already exists!' );   
                }
            } catch( PDOException $e ) {
                $returnData = msg( 0, 500, $e->getMessage() );
            }
        }
    }

} else {
    $returnData = msg( 0, 401, 'Unauthorized!' );
}

header( 'Content-Type: application/json' );
echo json_encode( $returnData );