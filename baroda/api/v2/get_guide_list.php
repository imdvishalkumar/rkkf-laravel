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

    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "GET" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    }
    // IF THERE ARE NO EMPTY FIELDS THEN-
    else {
        try {
            $query = "SELECT name, link FROM guide WHERE is_deleted = 0;";
            $query_stmt = $conn->prepare( $query );
            if ( $query_stmt->execute() ) {
                $row = $query_stmt->fetchAll( \PDO::FETCH_ASSOC );
                $returnData = [
                    'success' => 1,
                    'data' => $row
                ];
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