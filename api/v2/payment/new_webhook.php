<?php

require ( '../classes/Database.php' );
require ( '../middlewares/Auth.php' );

require( 'config.php' );
require( 'razorpay-php/Razorpay.php' );

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$webhookBody = file_get_contents( "php://input" );
$webhookSignature = hash_hmac( 'sha256', $webhookBody, $keySecret );
$webhookSecret = $keySecret;

use Razorpay\Api\Api;
$api = new Api( $keySecret, $keySecret );

$api->utility->verifyWebhookSignature( $webhookBody, $webhookSignature, $webhookSecret );

$webhook = json_decode( $webhookBody );

$orderId = $webhook->payload->payment->entity->order_id;
$desc = $webhook->payload->payment->entity->description;

if ( $webhook->event == "payment.authorized" ) {

    if ( $desc == "monthlyFees" ) {
        
        $query = "SELECT * FROM `transcation` WHERE order_id = :order_id;";
        $query_stmt = $conn->prepare( $query );
        $query_stmt->bindParam( ':order_id', $orderId );
        $query_stmt->execute();
        if ( $query_stmt->rowCount() > 0 ) {
            $rowT = $query_stmt->fetch( PDO::FETCH_ASSOC );
            if ($rowT['status'] == 0) {
                $monthsArray = explode( ',', $rowT['months'] );
                $count = count( $monthsArray );
                $amount = $rowT['amount'];
                $check = $amount % $count;
                $fees = ( $amount-$check ) / $count;
                $temp = 0;

                $student_id = $rowT['student_id'];
                $year = $rowT['year'];
                $date = $rowT['date'];
                $amount = $rowT['amount'];
                $coupon_id = $rowT['coupon_id'];
                $additional = '0';
                $disabled = '0';
                $remarks = '';

                foreach ( $monthsArray as $month ) {
                    
                $checkQuery = "SELECT COUNT(*) FROM fees WHERE 
                student_id = :student_id AND 
                months = :months AND 
                year = :year AND 
                date = :date AND 
                amount = :amount AND 
                coupon_id = :coupon_id AND 
                additional = :additional AND 
                disabled = :disabled AND 
                mode = :mode AND 
                remarks = :remarks";
                $check_stmt = $conn->prepare($checkQuery);
                $check_stmt->bindParam(':student_id', $student_id);
                $check_stmt->bindParam(':months', $month);
                $check_stmt->bindParam(':year', $year);
                $check_stmt->bindParam(':date', $date);
                $check_stmt->bindParam(':amount', $fee1);
                $check_stmt->bindParam(':coupon_id', $coupon_id);
                $check_stmt->bindParam(':additional', $additional);
                $check_stmt->bindParam(':disabled', $disabled);
                $check_stmt->bindParam(':mode', $orderId);
                $check_stmt->bindParam(':remarks', $remarks);
                $check_stmt->execute();
                $count = $check_stmt->fetchColumn();

                $check_stmt = $conn->prepare($checkQuery);
                $check_stmt->bindParam(':student_id', $student_id);
                $check_stmt->bindParam(':months', $month);
                $check_stmt->bindParam(':mode', $orderId);
                $check_stmt->execute();
                $count = $check_stmt->fetchColumn();

                    
                    if ( $temp == 0 && $count == 0) {
                        $temp++;
                        $fee1 = ( $fees + $check );
                        $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode,remarks) values ( :student_id, :months, :year, :date, :amount, :coupon_id, :additional, :disabled, :mode, :remarks );";
                        $query_stmt = $conn->prepare( $feeQuery );
                        $query_stmt->bindParam( ':student_id', $student_id );
                        $query_stmt->bindParam( ':months', $month );
                        $query_stmt->bindParam( ':year', $year );
                        $query_stmt->bindParam( ':date', $date );
                        $query_stmt->bindParam( ':amount', $fee1 );
                        $query_stmt->bindParam( ':coupon_id', $coupon_id );
                        $query_stmt->bindParam( ':additional', $additional );
                        $query_stmt->bindParam( ':disabled', $disabled );
                        $query_stmt->bindParam( ':mode', $orderId );
                        $query_stmt->bindParam( ':remarks', $remarks );
                        $query_stmt->execute();
                    } else if($count == 0) {
                        $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode,remarks) values ( :student_id, :months, :year, :date, :amount, :coupon_id, :additional, :disabled, :mode, :remarks );";
                        $query_stmt = $conn->prepare( $feeQuery );
                        $query_stmt->bindParam( ':student_id', $student_id );
                        $query_stmt->bindParam( ':months', $month );
                        $query_stmt->bindParam( ':year', $year );
                        $query_stmt->bindParam( ':date', $date );
                        $query_stmt->bindParam( ':amount', $fees );
                        $query_stmt->bindParam( ':coupon_id', $coupon_id );
                        $query_stmt->bindParam( ':additional', $additional );
                        $query_stmt->bindParam( ':disabled', $disabled );
                        $query_stmt->bindParam( ':mode', $orderId );
                        $query_stmt->bindParam( ':remarks', $remarks );
                        $query_stmt->execute();
                    }
                    if ( $month == 12 ) {
                        $year = $year + 1;
                    }
                }
                $feeId = $conn->lastInsertId();
                $update = "update transcation set status = 1, ref_id = '".$feeId."' where order_id ='".$orderId."'";
                $query_stmt = $conn->prepare( $update );
                $query_stmt->execute();
                $activeQuery = "UPDATE students SET active = 1 WHERE student_id IN (SELECT s.student_id FROM students s WHERE s.student_id = :student_id AND IFNULL(DATE_FORMAT((SELECT DATE_FORMAT(CONCAT(year,'-',months,'-01'), '%Y-%m-%d') as mdate FROM fees WHERE student_id = s.student_id ORDER BY mdate DESC LIMIT 1), '%Y-%m-%d'),'1000-01-01') >= date_sub(DATE_FORMAT(CONCAT(YEAR(now()),'-',MONTH(now()),'-01'), '%Y-%m-%d'), interval 1 month));";
                $query_stmt = $conn->prepare( $activeQuery );
                $query_stmt->bindParam( ':student_id', $student_id );
                $query_stmt->execute();
            }
        }
        
    } else if ( $desc == "Admission fees" ) {
        $update = "UPDATE `enquire` SET `payment_status` = '1' WHERE `enquire`.`order_id` = :order_id;";
        $query_stmt = $conn->prepare( $update );
        $query_stmt->bindParam( ':order_id', $orderId );
        if ( !( $query_stmt->execute() ) ) {
            $myfile = fopen( "examFeesLogs.txt", "a" );
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
        
        $query = "SELECT * FROM `transcation` WHERE order_id = :order_id;";
        $query_stmt = $conn->prepare( $query );
        $query_stmt->bindParam( ':order_id', $orderId );
        $query_stmt->execute();
        if ( $query_stmt->rowCount() > 0 ) {
            $rowT = $query_stmt->fetch( PDO::FETCH_ASSOC );
            if ($rowT['status'] == 0) {
                $monthsArray = explode( ',', $rowT['months'] );
                $count = count( $monthsArray );
                $amount = $rowT['amount'];
                $check = $amount % $count;
                $fees = ( $amount-$check ) / $count;
                $temp = 0;

                $student_id = $rowT['student_id'];
                $year = $rowT['year'];
                $date = $rowT['date'];
                $amount = $rowT['amount'];
                $coupon_id = $rowT['coupon_id'];
                $additional = '0';
                $disabled = '0';
                $remarks = '';

                foreach ( $monthsArray as $month ) {
                    if ( $temp == 0 ) {
                        $temp++;
                        $fee1 = ( $fees + $check );
                        $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode,remarks) values ( :student_id, :months, :year, :date, :amount, :coupon_id, :additional, :disabled, :mode, :remarks );";
                        $query_stmt = $conn->prepare( $feeQuery );
                        $query_stmt->bindParam( ':student_id', $student_id );
                        $query_stmt->bindParam( ':months', $month );
                        $query_stmt->bindParam( ':year', $year );
                        $query_stmt->bindParam( ':date', $date );
                        $query_stmt->bindParam( ':amount', $fee1 );
                        $query_stmt->bindParam( ':coupon_id', $coupon_id );
                        $query_stmt->bindParam( ':additional', $additional );
                        $query_stmt->bindParam( ':disabled', $disabled );
                        $query_stmt->bindParam( ':mode', $orderId );
                        $query_stmt->bindParam( ':remarks', $remarks );
                        $query_stmt->execute();
                    } else {
                        $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode,remarks) values ( :student_id, :months, :year, :date, :amount, :coupon_id, :additional, :disabled, :mode, :remarks );";
                        $query_stmt = $conn->prepare( $feeQuery );
                        $query_stmt->bindParam( ':student_id', $student_id );
                        $query_stmt->bindParam( ':months', $month );
                        $query_stmt->bindParam( ':year', $year );
                        $query_stmt->bindParam( ':date', $date );
                        $query_stmt->bindParam( ':amount', $fees );
                        $query_stmt->bindParam( ':coupon_id', $coupon_id );
                        $query_stmt->bindParam( ':additional', $additional );
                        $query_stmt->bindParam( ':disabled', $disabled );
                        $query_stmt->bindParam( ':mode', $orderId );
                        $query_stmt->bindParam( ':remarks', $remarks );
                        $query_stmt->execute();
                    }
                    if ( $month == 12 ) {
                        $year = $year + 1;
                    }
                }
                $feeId = $conn->lastInsertId();
                $update = "update transcation set status = 1, ref_id = '".$feeId."' where order_id ='".$orderId."'";
                $query_stmt = $conn->prepare( $update );
                $query_stmt->execute();
                $activeQuery = "UPDATE students SET active = 1 WHERE student_id IN (SELECT s.student_id FROM students s WHERE s.student_id = :student_id AND IFNULL(DATE_FORMAT((SELECT DATE_FORMAT(CONCAT(year,'-',months,'-01'), '%Y-%m-%d') as mdate FROM fees WHERE student_id = s.student_id ORDER BY mdate DESC LIMIT 1), '%Y-%m-%d'),'1000-01-01') >= date_sub(DATE_FORMAT(CONCAT(YEAR(now()),'-',MONTH(now()),'-01'), '%Y-%m-%d'), interval 1 month));";
                $query_stmt = $conn->prepare( $activeQuery );
                $query_stmt->bindParam( ':student_id', $student_id );
                $query_stmt->execute();
            }
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