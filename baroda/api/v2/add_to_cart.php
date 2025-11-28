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
        || !isset( $_POST['variation_id'] )
        || empty( trim( $_POST['variation_id'] ) )
        || !isset( $_POST['qty'] )
        || empty( trim( $_POST['qty'] ) )
    ) {
        $returnData = msg( 1, 200, 'Please Fill in all Required Fields!' );
    } else {
        $student_id = trim( $_POST['s_id'] );
        $product_id = trim( $_POST['product_id'] );
        $variation_id = trim( $_POST['variation_id'] );
        $qty = trim( $_POST['qty'] );

        // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
        if ( !filter_var( $student_id, FILTER_VALIDATE_INT ) ) {
            $returnData = msg( 1, 422, 'Invalid ID!' );
        } else {
            try {
                $queryCheck = "SELECT * from cart WHERE student_id = ".$student_id." AND variation_id = ".$variation_id." AND product_id = ".$product_id;
                $query_stmt = $conn->prepare( $queryCheck );
                $query_stmt->execute();
                if ( $query_stmt->rowCount() == 0 ) {
                    $queryCheck = "SELECT qty FROM `variation` WHERE qty >= ".$qty." AND product_id = ".$product_id." AND id = ".$variation_id;
                    $query_stmt = $conn->prepare( $queryCheck );
                    $query_stmt->execute();
                    if ( $query_stmt->rowCount()>0 ) {
                        $insert = "INSERT INTO `cart` (`student_id`, `product_id`, `variation_id`, `qty`) VALUES ('".$student_id."', '".$product_id."', '".$variation_id."', '".$qty."');";
                        $query_stmt = $conn->prepare( $insert );
                        if ( $query_stmt->execute() ) {
                            $returnData = [
                                'success' => 1,
                                'added' => 1,
                                'qty' => $qty,
                                'message' => 'Product added successfully.'
                            ];
                        } else {
                            $returnData = msg( 1, 422, 'Unable to add product try again!' );
                        }
                    } else {
                        $returnData = msg( 1, 422, 'Quantity is not avaliable at this moment!' );
                    }
                } else {
                    $queryCheck = "SELECT c.qty , v.qty as total FROM products p , variation v , cart c WHERE v.qty >= c.qty + '".$qty."' AND c.student_id = '".$student_id."' AND v.id = '".$variation_id."' AND p.product_id = c.product_id AND p.product_id = v.product_id AND c.product_id = '".$product_id."' AND (v.qty - c.qty) > 0;";
                    $query_stmt = $conn->prepare( $queryCheck );
                    $query_stmt->execute();
                    if ( $query_stmt->rowCount()>0 ) {
                        $update = "UPDATE cart SET qty = qty + ".$qty." WHERE student_id = ".$student_id." AND variation_id = ".$variation_id." AND product_id = ".$product_id;
                        $query_stmt = $conn->prepare( $update );
                        if ( $query_stmt->execute() ) {
                            $returnData = [
                                'success' => 1,
                                'added' => 1,
                                'message' => 'Product updated successfully.'
                            ];
                        } else {
                            $returnData = msg( 1, 422, 'Unable to add product try again!' );
                        }
                    } else {
                        $returnData = msg( 1, 422, 'Quantity is not avaliable at this moment!' );
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