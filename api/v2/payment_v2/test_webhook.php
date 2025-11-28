<?php

require ( '../classes/Database.php' );
require ( '../middlewares/Auth.php' );

require( 'config.php' );
require( 'razorpay-php/Razorpay.php' );
use Razorpay\Api\Api;
$db_connection = new Database();
$conn = $db_connection->dbConnection();

$webhookBody = file_get_contents( "php://input" );

$webhook = json_decode( $webhookBody );

$orderId = $webhook->payload->payment->entity->order_id;
$desc = $webhook->payload->payment->entity->description;

if ( $webhook->event == "payment.authorized" ) {

    if ( $desc == "monthlyFees" ) {
        
        $keySecret = "6TtZ8RXwbMWnpBFbasayRvox";
        $webhookSignature = hash_hmac( 'sha256', $webhookBody, $keySecret );
        $webhookSecret = $keySecret;
        
        $api = new Api( $keySecret, $keySecret );
        
        echo $api->utility->verifyWebhookSignature( $webhookBody, $webhookSignature, $webhookSecret );
        $update = "update transcation set status = 1 where order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $update );
        if ( $query_stmt->execute() ) {
                $queryCheck = "SELECT * FROM fees WHERE mode = '".$orderId."'";
                $query_stmt = $conn->prepare( $queryCheck );
                $query_stmt->execute();
                if ( $query_stmt->rowCount() > 0 ) {

                } else {
                    $query = "select * from transcation where order_id ='".$orderId."'";
                    $query_stmt = $conn->prepare( $query );
                    $row = $query_stmt->fetch( PDO::FETCH_ASSOC );
                    $query_stmt->execute();
                    $monthsArray = explode( ',', $row['months'] );
                    $count = count( $monthsArray );
                    $amount = $row['amount'];
                    $check = $amount % $count;
                    $fees = ( $amount-$check ) / $count;
                    $temp = 0;
                    foreach ( $monthsArray as $month ) {
                        if ( $temp == 0 ) {
                            $temp++;
                            $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (".$row['student_id'].",$month,'".$row['year']."','".$row['date']."','".( $fees + $check )."','".$row['coupon_id']."','0','0','".$orderId."')";
                            $query_stmt = $conn->prepare( $feeQuery );
                            $query_stmt->execute();
                            $feeId = $conn->lastInsertId();
                            $update = "update transcation set ref_id = '".$feeId."' where order_id ='".$orderId."'";
                            $query_stmt = $conn->prepare( $update );
                            $query_stmt->execute();

                        } else {
                            $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (".$row['student_id'].",$month,'".$row['year']."','".$row['date']."','$fees','".$row['coupon_id']."','0','0','".$orderId."')";
                            $query_stmt = $conn->prepare( $feeQuery );
                            $query_stmt->execute();
                            $feeId = $conn->lastInsertId();
                            $update = "update transcation set ref_id = '".$feeId."' where order_id ='".$orderId."'";
                            $query_stmt = $conn->prepare( $update );
                            $query_stmt->execute();
                        }
                    }

                }
            

        } else {
            $myfile = fopen( "monthlyFeesLogs.txt", "a" );
            fwrite( $myfile, $webhook->payload->payment->entity->order_id );
            fclose( $myfile );
        }
    } else if ( $desc == "merchandise" ) {
        //query check
        
        $keySecret = "q89Hvvlc5qLi623yh6pMDG84";
        $webhookSignature = hash_hmac( 'sha256', $webhookBody, $keySecret );
        $webhookSecret = $keySecret;
        
        
        $api = new Api( $keySecret, $keySecret );
        
        echo $api->utility->verifyWebhookSignature( $webhookBody, $webhookSignature, $webhookSecret );
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
            if ( !( $query_stmt->execute() ) ) {
                $myfile = fopen( "merchandiseLogs.txt", "a" );
                fwrite( $myfile, $webhook->payload->payment->entity->order_id );
                fclose( $myfile );
            }
        } else {
            $update = "update orders set status = 1 where rp_order_id ='".$orderId."'";
            $query_stmt = $conn->prepare( $update );
            if ( !( $query_stmt->execute() ) ) {
                $myfile = fopen( "merchandiseLogs.txt", "a" );
                fwrite( $myfile, $webhook->payload->payment->entity->order_id );
                fclose( $myfile );
            }
        }

    } else if ( $desc == "examFee" ) {
        $keySecret = "sLgHVA9FL6a5qeyZF4TIpSTb";
        $webhookSignature = hash_hmac( 'sha256', $webhookBody, $keySecret );
        $webhookSecret = $keySecret;
        
        
        $api = new Api( $keySecret, $keySecret );
        
        echo $api->utility->verifyWebhookSignature( $webhookBody, $webhookSignature, $webhookSecret );
        $update = "update exam_fees set status = 1 where rp_order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $update );
        if ( !( $query_stmt->execute() ) ) {
            $myfile = fopen( "examFeesLogs.txt", "a" );
            fwrite( $myfile, $webhook->payload->payment->entity->order_id );
            fclose( $myfile );
        }

    } else if ( $desc == "eventFees" ) {
        $keySecret = "8Stz6IasX2j9jUDUTKg11wPU";
        $webhookSignature = hash_hmac( 'sha256', $webhookBody, $keySecret );
        $webhookSecret = $keySecret;
        
        
        $api = new Api( $keySecret, $keySecret );
        
        echo $api->utility->verifyWebhookSignature( $webhookBody, $webhookSignature, $webhookSecret );
        $update = "update event_fees set status = 1 where rp_order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $update );
        if ( !( $query_stmt->execute() ) ) {
            $myfile = fopen( "eventFeesLogs.txt", "a" );
            fwrite( $myfile, $webhook->payload->payment->entity->order_id );
            fclose( $myfile );
        }
    }

} elseif ( $webhook->event == "payment.failed" ) {

    if ( $desc == "monthlyFees" ) {
        $orderId = $webhook->payload->payment->entity->order_id;
        $update = "update transcation set status = -1 where order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $update );
        if ( !$query_stmt->execute() ) {
            $myfile = fopen( "monthlyFeesLogs.txt", "a" );
            fwrite( $myfile, $webhook->payload->payment->entity->order_id );
            fclose( $myfile );
        }

    } else if ( $desc == "merchandise" ) {
        $orderId = $webhook->payload->payment->entity->order_id;
        $update = "update orders set status = -1 where rp_order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $update );
        if ( !$query_stmt->execute() ) {
            $myfile = fopen( "merchandiseLogs.txt", "a" );
            fwrite( $myfile, $webhook->payload->payment->entity->order_id );
            fclose( $myfile );
        }

    } else if ( $desc == "examFee" ) {
        $update = "update exam_fees set status = -1 where rp_order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $update );
        if ( !( $query_stmt->execute() ) ) {
            $myfile = fopen( "examFeesLogs.txt", "a" );
            fwrite( $myfile, $webhook->payload->payment->entity->order_id );
            fclose( $myfile );
        }

    } else if ( $desc == "eventFees" ) {
        $update = "update event_fees set status = -1 where rp_order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $update );
        if ( !( $query_stmt->execute() ) ) {
            $myfile = fopen( "eventFeesLogs.txt", "a" );
            fwrite( $myfile, $webhook->payload->payment->entity->order_id );
            fclose( $myfile );
        }
    }

} elseif ( $webhook->event == "payment.captured" ) {

    if ( $desc == "monthlyFees" ) {

        $update = "update transcation set status = 1 where order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $update );
        if ( $query_stmt->execute() ) {

            if ( $query_stmt->execute() ) {
                $queryCheck = "SELECT * FROM fees WHERE mode = '".$orderId."'";
                $query_stmt = $conn->prepare( $queryCheck );
                $query_stmt->execute();
                if ( $query_stmt->rowCount() > 0 ) {

                } else {
                    $query = "select * from transcation where order_id ='".$orderId."'";
                    $query_stmt = $conn->prepare( $query );
                    $row = $query_stmt->fetch( PDO::FETCH_ASSOC );
                    $monthsArray = explode( ',', $row['months'] );
                    $count = count( $monthsArray );
                    $amount = $row['amount'];
                    $check = $amount % $count;
                    $fees = ( $amount-$check ) / $count;
                    $temp = 0;
                    foreach ( $monthsArray as $month ) {
                        if ( $temp == 0 ) {
                            $temp++;
                            $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (".$row['student_id'].",$month,'".$row['year']."','".$row['date']."','".( $fees + $check )."','".$row['coupon_id']."','0','0','".$orderId."')";
                            $query_stmt = $conn->prepare( $feeQuery );
                            $query_stmt->execute();
                            $feeId = $conn->insert_id;
                            $update = "update transcation set ref_id = '".$feeId."' where order_id ='".$orderId."'";
                            $query_stmt = $conn->prepare( $update );
                            $query_stmt->execute();

                        } else {
                            $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (".$row['student_id'].",$month,'".$row['year']."','".$row['date']."','$fees','".$row['coupon_id']."','0','0','".$orderId."')";
                            $query_stmt = $conn->prepare( $feeQuery );
                            $query_stmt->execute();
                            $feeId = $conn->insert_id;
                            $update = "update transcation set ref_id = '".$feeId."' where order_id ='".$orderId."'";
                            $query_stmt = $conn->prepare( $update );
                            $query_stmt->execute();
                        }
                    }

                }
            }

        } else {
            $myfile = fopen( "monthlyFeesLogs.txt", "a" );
            fwrite( $myfile, $webhook->payload->payment->entity->order_id );
            fclose( $myfile );
        }
    } else if ( $desc == "merchandise" ) {
        //query check
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
            if ( !( $query_stmt->execute() ) ) {
                $myfile = fopen( "merchandiseLogs.txt", "a" );
                fwrite( $myfile, $webhook->payload->payment->entity->order_id );
                fclose( $myfile );
            }
        } else {
            $update = "update orders set status = 1 where rp_order_id ='".$orderId."'";
            $query_stmt = $conn->prepare( $update );
            if ( !( $query_stmt->execute() ) ) {
                $myfile = fopen( "merchandiseLogs.txt", "a" );
                fwrite( $myfile, $webhook->payload->payment->entity->order_id );
                fclose( $myfile );
            }
        }

    } else if ( $desc == "examFee" ) {
        $update = "update exam_fees set status = 1 where rp_order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $update );
        if ( !( $query_stmt->execute() ) ) {
            $myfile = fopen( "examFeesLogs.txt", "a" );
            fwrite( $myfile, $webhook->payload->payment->entity->order_id );
            fclose( $myfile );
        }

    } else if ( $desc == "eventFees" ) {
        $update = "update event_fees set status = 1 where rp_order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $update );
        if ( !( $query_stmt->execute() ) ) {
            $myfile = fopen( "eventFeesLogs.txt", "a" );
            fwrite( $myfile, $webhook->payload->payment->entity->order_id );
            fclose( $myfile );
        }
    }

} else {

    if ( $desc == "monthlyFees" ) {
        $myfile = fopen( "monthlyFeesLogs.txt", "a" );
        fwrite( $myfile, $webhook->payload->payment->entity->order_id );
        fclose( $myfile );

    } else if ( $desc == "merchandise" ) {
        $myfile = fopen( "merchandiseLogs.txt", "a" );
        fwrite( $myfile, $webhook->payload->payment->entity->order_id );
        fclose( $myfile );

    } else if ( $desc == "examFee" ) {
        $myfile = fopen( "examFeesLogs.txt", "a" );
        fwrite( $myfile, $webhook->payload->payment->entity->order_id );
        fclose( $myfile );

    } else if ( $desc == "eventFees" ) {
        $myfile = fopen( "eventFeesLogs.txt", "a" );
        fwrite( $myfile, $webhook->payload->payment->entity->order_id );
        fclose( $myfile );

    }

}
/*
$myfile = fopen( "testfile.txt", "w" );
fwrite( $myfile, $webhook->payload->payment->entity->order_id );
fclose( $myfile );
$webhook->event == "payment.failed" ||
*/

?>