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
$baseUrl = $db_connection->getBaseURl();
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
    ) {
        $returnData = msg( 1, 200, 'Please Fill in all Required Fields!' );
    } else {
        $student_id = trim( $_POST['s_id'] );

        // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
        if ( !filter_var( $student_id, FILTER_VALIDATE_INT ) ) {
            $returnData = msg( 1, 422, 'Invalid ID!' );
        } else {
            try {
                $queryCheck = "SELECT c.qty,c.product_id,p.details,p.name,v.price,v.variation,v.id,p.image1 FROM cart c , products p , variation v WHERE c.product_id = p.product_id AND p.product_id = v.product_id AND c.variation_id = v.id AND c.student_id = ".$student_id;
                $query_stmt = $conn->prepare( $queryCheck );
                $query_stmt->execute();
                if ( $query_stmt->rowCount() > 0 ) {
                    $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
                    $totalRows = $query_stmt->rowCount();
                    $i = 0;
                    while( $i < $totalRows ) {
                        if ( $row[$i]['image1'] != NULL ) {
                            $row[$i]['image1'] = $baseUrl."images/products/" . $row[$i]['image1'];
                        } else {
                            $row[$i]['image1'] = $baseUrl."images/products/placeholder.png";
                        }
                        $i++;
                    }
                    $returnData = [
                        'success' => 1,
                        'data' => $row
                    ];

                } else {
                    $returnData = msg( 1, 422, 'Cart is empty!' );
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