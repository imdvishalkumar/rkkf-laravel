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
    elseif (
        !isset( $id )
        || empty( trim( $id ) )
    ) {

        $returnData = msg( 0, 422, 'Please Fill in all Required Fields!' );
    }
    // IF THERE ARE NO EMPTY FIELDS THEN-
    else {
        try {

            //$query = "select * from students where student_id='".$id."' ORDER BY id DESC";
            $query = "SELECT * FROM `notification` WHERE student_id = '".$id."' ORDER BY id DESC";
            $query_stmt = $conn->prepare( $query );
            if ( $query_stmt->execute() ) {

                $row = $query_stmt->fetchAll( \PDO::FETCH_ASSOC );
                $returnData = [
                    'success' => 1,
                    'data' => $row
                ];
            }

            if ( $query_stmt->rowCount() ) {
                $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );

            }
            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else {
                $returnData = msg( 1, 200, 'No Notifications found!' );
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