<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST" );
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
    if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $_POST['s_id'] )
        || empty( trim( $_POST['s_id'] ) )
        || !isset( $_POST['product_id'] )
        || empty( trim( $_POST['product_id'] ) )
    ) {
        $returnData = msg( 1, 200, 'Please Fill in all Required Fields!' );
    } else {
        $student_id = trim( $_POST['s_id'] );
        $product_id = trim( $_POST['product_id'] );

        // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
        if ( !filter_var( $student_id, FILTER_VALIDATE_INT ) ) {
            $returnData = msg( 1, 422, 'Invalid ID!' );
        } else {
            try {
                if ( $product_id == "all" ) {
                    $query = "DELETE from cart WHERE student_id = ".$student_id;
                    $query_stmt = $conn->prepare( $query );
                    if ( $query_stmt->execute() ) {
                        $returnData = [
                            'success' => 1,
                            'removed' => 2, 
                            'message' => 'Cart products removed successfully.'
                        ];
                    } else {
                        $returnData = msg( 1, 422, 'Unable to remove product try again!' );
                    }
                } else {
                    $query = "DELETE from cart WHERE student_id = ".$student_id." AND product_id = ".$product_id;
                    $query_stmt = $conn->prepare( $query );
                    if ( $query_stmt->execute() ) {
                        $returnData = [
                            'success' => 1,
                            'removed' => 1,
                            'message' => 'Product removed successfully.'
                        ];
                    } else {
                        $returnData = msg( 1, 422, 'Unable to remove product try again!' );
                    }
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