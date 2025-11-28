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
    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "GET" ) {
        $returnData = msg( 0, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $id )
        || empty( trim( $id ) ) ) {

            $returnData = msg( 0, 422, 'Please Fill in all Required Fields!' );
        }
        // IF THERE ARE NO EMPTY FIELDS THEN-
        else {
            $id = trim( $id );

            // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
            if ( !filter_var( $id, FILTER_VALIDATE_INT ) ) {
                $returnData = msg( 0, 422, 'Invalid ID!' );
            }
            // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
            else {
                try {
                    $paid = false;
                    $error = false;
                    $eligibleFee = false;
                    $eligibleAtten = false;
                    $dueDateGone = false;
                    $query = "SELECT e.*, EXISTS (SELECT event_fees_id from event_fees WHERE student_id = ".$id." AND e.event_id = event_id AND status = 1) as applied from event e WHERE e.from_date > CURDATE() AND e.penalty_due_date >= CURDATE() AND e.event_id = s.event_id AND s.student_id = ".$id." AND s.eligible = 1";                    
                    $query_stmt = $conn->prepare( $query );
                    $query_stmt->execute();
                    // if exam exists
                    if ( $query_stmt->rowCount() > 0 ) {
                        $count = $query_stmt->rowCount();
                        $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );

                   
                        $totalRows = $query_stmt->rowCount();
                        $i = 0;
                        while($i < $totalRows)
                        {
                            if($row[$i]['fees_due_date'] <= date('Y-m-d'))
                            {
                                $row[$i]['isPenalty'] = true;
                            }
                            else 
                            {
                                $row[$i]['isPenalty'] = false;
                            }
                            if($row[$i]['penalty_due_date'] >= date('Y-m-d'))
                            {
                                $row[$i]['entryOpen'] = true;
                            }
                            else 
                            {
                                $row[$i]['entryOpen'] = false;
                            }
                            $i++;
                        }
                        
                        $returnData = [
                            'success' => 1,
                            'data' => $row
                        ];

                    }
                    // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                    else {
                        $returnData = msg( 1, 422, 'No Event Found!' );
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