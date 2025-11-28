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
        $returnData = msg( 0, 404, 'Page Not Found!' );
    } else {
        try {
            if ( isset( $_GET['belt_id'] ) ) {
                $belt_id = $_GET['belt_id'];
                $query = "SELECT * FROM products p , variation v WHERE p.product_id = v.product_id AND v.qty > 0";
                $query_stmt = $conn->prepare( $query );
                $query_stmt->execute();

                if ( $query_stmt->rowCount() > 0 ) {
                    $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
                    $totalRows = $query_stmt->rowCount();
                    $i = 0;
                    //belt_ids
                    while( $i < $totalRows ) {
                        $string = $row[$i]['belt_ids']; 
                        $str_arr = explode (",", $string);
                        if(in_array($belt_id, $str_arr)){
                            if ( $row[$i]['image1'] != NULL ) {
                                $row[$i]['image1'] = $baseUrl."images/products/" . $row[$i]['image1'];
                            } else {
                                $row[$i]['image1'] = $baseUrl."images/products/placeholder.png";
                            }
                            if ( $row[$i]['image2'] != NULL ) {
                                $row[$i]['image2'] = $baseUrl."images/products/" . $row[$i]['image2'];
                            } else {
                                $row[$i]['image2'] = $baseUrl."images/products/placeholder.png";
                            }
                            if ( $row[$i]['image3'] != NULL ) {
                                $row[$i]['image3'] = $baseUrl."images/products/" . $row[$i]['image3'];
                            } else {
                                $row[$i]['image3'] = $baseUrl."images/products/placeholder.png";
                            }
                        } else {
                            unset($row[$i]); 
                        }
                        $i++;
                    }
                    
                    $returnData = [
                        'success' => 1,
                        'data' => array_values($row)
                    ];
                }
                // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                else {
                    $returnData = msg( 0, 422, 'No Product Found!' );
                }
                
            } else {

                $query = "SELECT * FROM products p , variation v WHERE p.product_id = v.product_id AND v.qty > 0";
                $query_stmt = $conn->prepare( $query );
                $query_stmt->execute();
                
                $queryB = "SELECT * FROM belt";
                $query_stmtB = $conn->prepare( $queryB );
                $query_stmtB->execute();

                if ( $query_stmt->rowCount() > 0 ) {
                    $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
                    $belt = $query_stmtB->fetchAll( PDO::FETCH_ASSOC );
                    $totalRows = $query_stmt->rowCount();
                    $i = 0;
                    while( $i < $totalRows ) {
                        if ( $row[$i]['image1'] != NULL ) {
                            $row[$i]['image1'] = $baseUrl."images/products/" . $row[$i]['image1'];
                        } else {
                            $row[$i]['image1'] = $baseUrl."images/products/placeholder.png";
                        }
                        if ( $row[$i]['image2'] != NULL ) {
                            $row[$i]['image2'] = $baseUrl."images/products/" . $row[$i]['image2'];
                        } else {
                            $row[$i]['image2'] = $baseUrl."images/products/placeholder.png";
                        }
                        if ( $row[$i]['image3'] != NULL ) {
                            $row[$i]['image3'] = $baseUrl."images/products/" . $row[$i]['image3'];
                        } else {
                            $row[$i]['image3'] = $baseUrl."images/products/placeholder.png";
                        }
                        $i++;
                    }
                    $returnData = [
                        'success' => 1,
                        'data' => $row,
                        'belt' => $belt
                    ];
                }
                // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                else {
                    $returnData = msg( 0, 422, 'No Product Found!' );
                }

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
