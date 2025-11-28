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
$baseUrl = $db_connection->getBaseURl();
$auth = new Auth( $conn, $allHeaders );

if ( $auth->isAuth() ) {

    $id = $_GET['id'];
    $role = $_GET['role'];
    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "GET" ) {
        $returnData = msg( 0, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $id )
        || empty( trim( $id ) )
        || !isset( $role )
        || empty( trim( $role ) ) ) {

            $returnData = msg( 0, 422, 'Please Fill in all Required Fields!' );
        }
        // IF THERE ARE NO EMPTY FIELDS THEN-
        else {
            $id = trim( $id );
            $role = trim( $role );

            // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
            if ( !filter_var( $id, FILTER_VALIDATE_INT ) ) {
                $returnData = msg( 0, 422, 'Invalid ID!' );
            }
            // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
            else {
                try {

                    //$query = "select * from students where student_id='".$id."'";
                    $query = "SELECT s.*, (SELECT IFNULL(SUM(hours), 0) from fastrack_attendance where student_id = s.student_id) as fast_hrs, (SELECT total_hours FROM fastrack WHERE student_id = s.student_id) as total_hrs, b.name as belt , br.name as branch_name ,TIMESTAMPDIFF(YEAR, s.dob, CURDATE()) AS age FROM students s , branch br , belt b where s.branch_id = br.branch_id AND s.active = 1 AND s.belt_id = b.belt_id AND s.student_id = '".$id."'";
                    $query_stmt = $conn->prepare( $query );
                    $query_stmt->execute();
                    
                    if ( $query_stmt->rowCount() ) {
                        $row = $query_stmt->fetch( PDO::FETCH_ASSOC );
                        if ( empty( $row['profile_img'] ) ) {
                            $row['profile_img'] = $baseUrl."images/profile/ins_img.jpg";
                        } else {
                            $row['profile_img'] = $baseUrl."images/profile/" . $row['profile_img'];
                        }
                        $query = "SELECT * FROM fees WHERE student_id = ".$id." ORDER BY year DESC , months DESC LIMIT 0,1";
                        $query_stmt = $conn->prepare( $query );
                        $query_stmt->execute();

                        if ( $query_stmt->rowCount() ) {
                            $row1 = $query_stmt->fetch( PDO::FETCH_ASSOC );

                            $returnData = [
                                'success' => 1,
                                'userdata' => $row,
                                'fee' => $row1
                            ];
                        } else {
                            $returnData = [
                                'success' => 1,
                                'userdata' => $row
                            ];
                        }
                    }
                    // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                    else {
                        $returnData = msg( 0, 422, 'Invalid Student ID!' );
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
?>