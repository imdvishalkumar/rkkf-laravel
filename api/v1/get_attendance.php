<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: GET" );
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

    $id = $_GET['id'];
    $fromdate = $_GET['fromdate'];
    $todate = $_GET['todate'];
    if (isset( $_GET['default'] )) {
        $todate = date("Y-m-d");
    }
    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "GET" ) {
        $returnData = msg( 0, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $id )
        || empty( trim( $id ) )
        || !isset( $fromdate )
        || empty( trim( $fromdate ) ) 
        || !isset( $todate )
        || empty( trim( $todate ) ) ) {

            $returnData = msg( 0, 422, 'Please Fill in all Required Fields!' );
        }
        // IF THERE ARE NO EMPTY FIELDS THEN-
        else {
            $id = trim( $id );
            $fromdate = trim( $fromdate );
            $todate = trim( $todate );

            // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
            if ( !filter_var( $id, FILTER_VALIDATE_INT ) ) {
                $returnData = msg( 0, 422, 'Invalid ID!' );
            }
            // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
            else {
                try {

                    $query = "select * from attendance where ((date >='".$fromdate."') AND (date <='".$todate."')) AND student_id='".$id."'";
                    
                    $query_stmt = $conn->prepare( $query );
                    $query_stmt->execute();

                    if ( $query_stmt->rowCount() > 0) {                        
                        $totalRows = $query_stmt->rowCount();
                        $i = 0;
                        $present = 0;
                        $absent = 0;
                        $leave = 0;
                        $row = $query_stmt->fetchAll(\PDO::FETCH_ASSOC);
                        while($i < $totalRows)
                        {
                            if($row[$i]['attend'] == 'P')
                            {
                                $present++;
                            }
                            else if ($row[$i]['attend'] == 'A')
                            {
                                $absent++;
                            }
                            else if ($row[$i]['attend'] == 'L')
                            {
                                $leave++;
                            }
                            $i++;
                        }
                        
                        $returnData = [
                            'success' => 1,
                            'status' => 200,
                            'done' => 1,
                            'present' => $present,
                            'absent' => $absent,
                            'leave' => $leave,
                            'currentDate' => date("Y-m-d")
                        ];
                    }
                    // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                    else {
                        $returnData = msg( 1, 422, 'No Attendance Found!' );
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