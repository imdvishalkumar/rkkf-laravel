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
        $returnData = msg( 0, 404, 'Page Not Found!' );
    }
    // IF THERE ARE NO EMPTY FIELDS THEN-
    else {
        if (isset($_GET['name'])){
            $id = $_GET['name'];
        } else {
            $id = "";
        }
        $id = trim( $id );

        try {

            //$query = "select * from students where student_id='".$id."'";
            //$query = "SELECT s.*, br.name as branch_name FROM students s , branch br where s.branch_id = br.branch_id AND s.active = 1 AND s.branch_id =".$id;
            $query = "SELECT s.* , CONCAT(s.firstname ,' ',s.lastname) as name, br.name as branch_name FROM students s, branch br WHERE CAST(s.student_id AS VARCHAR(9)) LIKE '".$id."%' or CONCAT(s.firstname ,' ',s.lastname) like '%".$id."%' AND active = 1 AND s.student_id NOT IN (SELECT student_id FROM fastrack) AND s.branch_id = br.branch_id";

            $query_stmt = $conn->prepare( $query );
            $query_stmt->execute();

            if ( $query_stmt->rowCount() > 0 ) {
                $row = $query_stmt->fetchAll( \PDO::FETCH_ASSOC );
                $returnData = [
                    'success' => 1,
                    'data' => $row
                ];
            }
            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else {
                $returnData = msg( 1, 200, 'No Student found !' );
            }
        } catch( PDOException $e ) {
            $returnData = msg( 0, 500, $e->getMessage() );
        }

    }
} else {
    $returnData = msg( 0, 401, 'Unauthorized!' );
}
header( 'Content-Type: application/json' );
echo json_encode( $returnData );