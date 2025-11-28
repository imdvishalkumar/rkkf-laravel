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

echo $api->utility->verifyWebhookSignature( $webhookBody, $webhookSignature, $webhookSecret );

$webhook = json_decode( $webhookBody );

if ( $webhook->event == "payment.authorized" ) {

    $orderId = $webhook->payload->payment->entity->order_id;
    
    $myfile = fopen( "testfile.txt", "w" );
    fwrite( $myfile, $webhook->payload->payment->entity->notes );
    fclose( $myfile );

    $update = "update transcation set status = 1 where order_id ='".$orderId."'";
    $query_stmt = $conn->prepare( $update );
    if ( $query_stmt->execute() ) {
        $query = "select * from transcation where order_id ='".$orderId."'";
        $query_stmt = $conn->prepare( $query );

        if ( $query_stmt->execute() ) {
            
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
                    $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (".$row['student_id'].",$month,'".$row['year']."','".$row['date']."','".( $fees + $check )."','".$row['coupon_id']."','0','0','razorpay')";
                    $query_stmt = $conn->prepare( $feeQuery );
                    $query_stmt->execute();
                    $feeId = $conn->insert_id;
                    $update = "update transcation set ref_id = '".$feeId."' where order_id ='".$orderId."'";
                    $query_stmt = $conn->prepare( $update );
                    $query_stmt->execute();

                } else {
                    $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (".$row['student_id'].",$month,'".$row['year']."','".$row['date']."','$fees','".$row['coupon_id']."','0','0','razorpay')";
                    $query_stmt = $conn->prepare( $feeQuery );
                    $query_stmt->execute();
                    $feeId = $conn->insert_id;
                    $update = "update transcation set ref_id = '".$feeId."' where order_id ='".$orderId."'";
                    $query_stmt = $conn->prepare( $update );
                    $query_stmt->execute();
                }
            }
        }

    } else {
        $myfile = fopen( "testfile.txt", "a" );
        fwrite( $myfile, $webhook->payload->payment->entity->order_id );
        fclose( $myfile );
    }

} elseif ( $webhook->event == "payment.captured" ) {

    $orderId = $webhook->payload->payment->entity->order_id;
    $update = "update transcation set status = 2 where order_id ='".$orderId."'";
    $query_stmt = $conn->prepare( $update );
    if ( !$query_stmt->execute() ) {
        $myfile = fopen( "testfile.txt", "a" );
        fwrite( $myfile, $webhook->payload->payment->entity->order_id );
        fclose( $myfile );
    }

} elseif ( $webhook->event == "payment.failed" ) {

    $orderId = $webhook->payload->payment->entity->order_id;
    $update = "update transcation set status = -1 where order_id ='".$orderId."'";
    $query_stmt = $conn->prepare( $update );
    if ( !$query_stmt->execute() ) {
        $myfile = fopen( "testfile.txt", "a" );
        fwrite( $myfile, $webhook->payload->payment->entity->order_id );
        fclose( $myfile );
    }

} else {

    $myfile = fopen( "testfile.txt", "a" );
    fwrite( $myfile, $webhook->payload->payment->entity->order_id );
    fclose( $myfile );
}
/*
$myfile = fopen( "testfile.txt", "w" );
fwrite( $myfile, $webhook->payload->payment->entity->order_id );
fclose( $myfile );
$webhook->event == "payment.failed" ||
*/

?>