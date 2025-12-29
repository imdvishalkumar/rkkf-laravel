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

require ( '../classes/Database.php' );
require ( '../middlewares/Auth.php' );
require( 'config.php' );
require( 'razorpay-php/Razorpay.php' );

use Razorpay\Api\Api;

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$auth = new Auth( $conn, $allHeaders );

if ( $auth->isAuth() ) {

    $orderId = $_POST['order_id'];
    $paymentId = $_POST['payment_id'];
    $signature = $_POST['signature'];
    $type = $_POST['type'];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
        $returnData = msg( 1, 200, 'Page Not Found!' );
    } elseif (
        !isset( $orderId )
        || empty( trim( $orderId ) )
        || !isset( $paymentId )
        || empty( trim( $paymentId ) )
        || !isset( $signature )
        || empty( trim( $signature ) )
        || !isset( $type )
        || empty( trim( $type ) )
    ) {
        $returnData = msg( 1, 200, 'Please Fill in all Required Fields!' );
    } else {

        

        if ( true ) {
            if ( $type == "monthlyFees" ) {
                $keySecret = "6TtZ8RXw324rdDSasayRvox";
                $sig = hash_hmac( 'sha256', $orderId."|".$paymentId, $keySecret );
                $update = "update transcation set status = 1 where order_id ='".$orderId."'";
                $query_stmt = $conn->prepare( $update );
                if ( $query_stmt->execute() ) {
                    $queryCheck = "SELECT * FROM fees WHERE mode = '".$orderId."'";
                    $query_stmt = $conn->prepare( $queryCheck );
                    $query_stmt->execute();
                    if ( $query_stmt->rowCount() > 0 ) {
                        $returnData = [
                            'success' => 1,
                            'saved' => 1,
                            'message' => 'Payment Success.'
                        ];
                    } else {
                        $query = "select * from transcation where order_id ='".$orderId."'";
                        $query_stmt = $conn->prepare( $query );
                        $query_stmt->execute();
                        $row = $query_stmt->fetch( PDO::FETCH_ASSOC );
                        $monthsArray = explode( ',', $row['months'] );
                        $count = count( $monthsArray );
                        $amount = $row['amount'];
                        $check = $amount % $count;
                        $fees = ( $amount-$check ) / $count;
                        $temp = 0;
                        $AllFeesInserted = false;
                        foreach ( $monthsArray as $month ) {
                            if ( $temp == 0 ) {
                                $temp++;
                                $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (".$row['student_id'].",$month,'".$row['year']."','".$row['date']."','".( $fees + $check )."','".$row['coupon_id']."','0','0','".$orderId."')";
                                $query_stmt = $conn->prepare( $feeQuery );
                                $query_stmt->execute();
                                $feeId = $conn->lastInsertId();
                                $update = "update transcation set ref_id = '".$feeId."' where order_id ='".$orderId."'";
                                $query_stmt = $conn->prepare( $update );
                                if ( $query_stmt->execute() ) {
                                    $AllFeesInserted = true;
                                } else {
                                    $AllFeesInserted = false;
                                    break;
                                }
                            } else {
                                $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (".$row['student_id'].",$month,'".$row['year']."','".$row['date']."','$fees','".$row['coupon_id']."','0','0','".$orderId."')";
                                $query_stmt = $conn->prepare( $feeQuery );
                                $query_stmt->execute();
                                $feeId = $conn->lastInsertId();
                                $update = "update transcation set ref_id = '".$feeId."' where order_id ='".$orderId."'";
                                $query_stmt = $conn->prepare( $update );
                                if ( $query_stmt->execute() ) {
                                    $AllFeesInserted = true;
                                } else {
                                    $AllFeesInserted = false;
                                    break;
                                }
                            }
                            if ( $month == 12 ) {
                                $row['year'] = $row['year'] + 1;
                            }
                        }
                        if ( $AllFeesInserted ) {
                            $returnData = [
                                'success' => 1,
                                'saved' => 1,
                                'message' => 'Payment Success.'
                            ];
                        } else {
                            $returnData = msg( 1, 200, 'Payment Failed.' );
                        }
                    }
                } else {
                    $myfile = fopen( "monthlyFeesLogs.txt", "a" );
                    fwrite( $myfile, $orderId );
                    fclose( $myfile );
                }
            } else if ( $type == "merchandise" ) {
                //query check
                $keySecret = "q89Hvvlc5qLi623yh6pMDG84";
                $sig = hash_hmac( 'sha256', $orderId."|".$paymentId, $keySecret );
                $select = "SELECT flag FROM `orders` WHERE flag = 0 AND rp_order_id ='".$orderId."'";
                $query_stmt = $conn->prepare( $select );
                $query_stmt->execute();
                if ( $query_stmt->rowCount() > 0 ) {
                    // get var id
                    $select = "SELECT id FROM variation WHERE id IN (SELECT variation_id FROM orders WHERE rp_order_id = '".$orderId."')";
                    $query_stmt = $conn->prepare( $select );
                    $query_stmt->execute();
                    if ( $query_stmt->rowCount() > 0 ) {
                        $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
                        $totalRows = $query_stmt->rowCount();
                        $i = 0;
                        while( $i < $totalRows ) {
                            if ( $row[$i]['id'] != NULL ) {
                                $update = "UPDATE variation SET qty = qty - (SELECT qty FROM orders WHERE variation_id = '".$row[$i]['id']."' AND rp_order_id = '".$orderId."') WHERE id = '".$row[$i]['id']."'";
                                $query_stmt = $conn->prepare( $update );
                                $query_stmt->execute();
                            }
                            $i++;
                        }
                    }
                    $update = "update orders set status = 1 , flag = 1 where rp_order_id ='".$orderId."'";
                    $query_stmt = $conn->prepare( $update );
                    if ( $query_stmt->execute() ) {
                        $returnData = [
                            'success' => 1,
                            'saved' => 1,
                            'message' => 'Payment Success.'
                        ];
                    } else {
                        $returnData = msg( 1, 200, 'Payment Failed.' );
                    }
                } else {
                    $update = "update orders set status = 1 where rp_order_id ='".$orderId."'";
                    $query_stmt = $conn->prepare( $update );
                    if ( $query_stmt->execute() ) {
                        $returnData = [
                            'success' => 1,
                            'saved' => 1,
                            'message' => 'Payment Success.'
                        ];
                    } else {
                        $returnData = msg( 1, 200, 'Payment Failed.' );
                    }
                }

            } else if ( $type == "examFee" ) {
                $keySecret = "sLgHVA9FL6a5qeyZF4TIpSTb";
                $sig = hash_hmac( 'sha256', $orderId."|".$paymentId, $keySecret );
                $update = "update exam_fees set status = 1 where rp_order_id ='".$orderId."'";
                $query_stmt = $conn->prepare( $update );
                if ( $query_stmt->execute() ) {
                    $returnData = [
                        'success' => 1,
                        'saved' => 1,
                        'message' => 'Payment Success.'
                    ];
                } else {
                    $returnData = msg( 1, 200, 'Payment Failed.' );
                }

            } else if ( $type == "eventFees" ) {
                $keySecret = "8Stz6IasX2j9jUDUTKg11wPU";
                $sig = hash_hmac( 'sha256', $orderId."|".$paymentId, $keySecret );
                $update = "update event_fees set status = 1 where rp_order_id ='".$orderId."'";
                $query_stmt = $conn->prepare( $update );
                if ( $query_stmt->execute() ) {
                    $returnData = [
                        'success' => 1,
                        'saved' => 1,
                        'message' => 'Payment Success.'
                    ];
                } else {
                    $returnData = msg( 1, 200, 'Payment Failed.' );
                }
            }

        } else {
            $returnData = msg( 1, 200, 'Payment Failed.' );
        }
    }

} else {
    $returnData = msg( 1, 200, 'Unauthorized!' );
}

header( 'Content-Type: application/json' );
echo json_encode( $returnData );
?>