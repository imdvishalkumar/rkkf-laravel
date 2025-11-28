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
        $returnData = msg( 1, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif ( !isset( $id )
    || empty( trim( $id ) ) ) {

        $returnData = msg( 1, 422, 'Please Fill in all Required Fields!' );
    }
    // IF THERE ARE NO EMPTY FIELDS THEN-
    else {
        $id = trim( $id );

        // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
        if ( !filter_var( $id, FILTER_VALIDATE_INT ) ) {
            $returnData = msg( 1, 422, 'Invalid ID!' );
        }
        // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
        else {
            try {

                //$query = "select * from students where student_id='".$id."'";
                //$query = "SELECT s.*, br.name as branch_name FROM students s , branch br where s.branch_id = br.branch_id AND s.active = 1 AND s.branch_id =".$id;
                // $query = "SELECT s. *, br.name as branch_name, EXISTS(select student_id from leave_table WHERE CURDATE() BETWEEN from_date AND to_date AND student_id = s.student_id) as present FROM students s , branch br where s.branch_id = br.branch_id AND s.active = 1 AND s.branch_id =".$id." AND student_id NOT IN (SELECT student_id FROM fastrack)";
                $query = "SELECT s.student_id,s.firstname,s.lastname,s.active, br.name as branch_name, EXISTS(select student_id from leave_table WHERE CURDATE() BETWEEN from_date AND to_date AND student_id = s.student_id) as present FROM students s , branch br where s.branch_id = br.branch_id AND s.branch_id = ".$id." AND student_id NOT IN (SELECT student_id FROM fastrack);";
                $query_stmt = $conn->prepare( $query );
                $query_stmt->execute();

                if ( $query_stmt->rowCount() > 0 ) {
                    //                        $totalRows = $query_stmt->rowCount();
                    //                        $i = 0;
                    $row = $query_stmt->fetchAll( \PDO::FETCH_ASSOC );
                    //                        while( $i < $totalRows )
                    // {
                    //                            if ( $row[$i]['profile_img'] != NULL )
                    // {
                    //                                $row[$i]['profile_img'] = $_SERVER['DOCUMENT_ROOT']."/rkkf/api/" . $row[$i]['profile_img'];
                    //                            }
                    //                            else
                    // {
                    //                                $row[$i]['profile_img'] = "";
                    //                            }
                    //                                $i++;
                    //                        }
                    //                        //$row['profile_img'] = "http://192.168.0.103/rkkf/api/" . $row['profile_img'];
                    $returnData = [
                        'success' => 1,
                        'data' => $row
                    ];
                }
                // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                else {
                    $returnData = msg( 1, 200, 'No Student found in Branch!' );
                }
            } catch( PDOException $e ) {
                $returnData = msg( 1, 500, $e->getMessage() );
            }

        }

    }
} else {
    $returnData = msg( 1, 401, 'Unauthorized!' );
}
header( 'Content-Type: application/json' );
echo json_encode( $returnData );