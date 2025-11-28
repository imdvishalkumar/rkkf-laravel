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

    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "GET" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    } else {
        try {

            //$query = "select * from students where student_id='".$id."'";
            $query = "SELECT * FROM branch";
            $query_stmt = $conn->prepare( $query );
            $query_stmt->execute();

            if ( $query_stmt->rowCount() ) {
                $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
                $returnData = [
                    'success' => 1,
                    'data' => $row
                ];
            }
            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else {
                $returnData = msg( 1, 200, 'No Branch found!' );
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