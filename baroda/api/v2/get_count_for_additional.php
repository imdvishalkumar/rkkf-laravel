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
            $present_count = 0;
            $event_count = 0;
            $queryCheck = "select * from attendance WHERE date = '".$date."' AND student_id = ".$studentId;
            $query_stmt = $conn->prepare( $queryCheck );
            $query_stmt->execute();
            if ( $query_stmt->rowCount() > 0 ) {
                $rows = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
                foreach ($rows as $row) {
                    if ($row['attend'] == 'P') {
                        $present_count++;
                    } else if ($row['attend'] == 'E') {
                        $event_count++;
                    }
                }
                $returnData = [
                        'success' => 1,
                        'present_count' => $present_count,
                        'event_count' => $event_count,
                        'done' => 1
                    ];
            } else {
                $returnData = msg( 1, 200, 'No Count Found.' );
            }
        } catch( PDOException $e ) {
            $returnData = msg( 1, 500, $e->getMessage() );
        }

    }

} else {
    $returnData = msg( 1, 401, 'Unauthorized!' );
}

header( 'Content-Type: application/json' );
echo json_encode( $returnData );