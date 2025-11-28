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

    $coupon = $_GET['coupon'];
    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "GET" ) {
        $returnData = msg( 0, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $coupon )
        || empty( trim( $coupon ) )
     ) {

            $returnData = msg( 0, 422, 'Please Fill in all Required Fields!' );
        }
        // IF THERE ARE NO EMPTY FIELDS THEN-
        else {
            $coupon = trim( $coupon );

            // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
            if ( strlen($coupon) < 3 )  {
                $returnData = msg( 0, 200, 'Invalid Coupon!' );
            }
            // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
            else {
                try {
                    $query = "select * from coupon WHERE coupon_txt = '".$coupon."' AND used = 0;";    
                    $query_stmt = $conn->prepare( $query );
                    $query_stmt->execute();
                    // if exam exists
                    if ( $query_stmt->rowCount() ) {
                        $row = $query_stmt->fetch( PDO::FETCH_ASSOC );
                        
                        $query = "update coupon set used = 1 WHERE coupon_txt = '".$coupon."';";    
                        $query_stmt = $conn->prepare( $query );
                        $query_stmt->execute();
                        
                        $returnData = [
                            'success' => 1,
                            'couponData' => $row
                        ];

                    }
                    // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                    else {
                        $returnData = msg( 1, 422, 'No Coupon Found!' );
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