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

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "GET" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    } else {

        // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
        try {
            $query = "select * from event ORDER BY from_date DESC LIMIT 5;";

            $query_stmt = $conn->prepare( $query );
            $query_stmt->execute();
            // if exam exists
            if ( $query_stmt->rowCount() ) {
                $examRow = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
                
                $returnData = [
                    'success' => 1,
                    'data' => $examRow
                ];

            }
            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else {
                $returnData = msg( 1, 422, 'No event Found!' );
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