<?php

require ( '../classes/Database.php' );
require ( '../middlewares/Auth.php' );

require( 'config.php' );
require( 'razorpay-php/Razorpay.php' );

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$webhookBody = file_get_contents( "php://input" );
$webhook = json_decode( file_get_contents( "php://input" ) );
$webhookSignature = hash_hmac( 'sha256', $webhookBody, $keySecret );
$webhookSecret = $keySecret;

use Razorpay\Api\Api;
$api = new Api( $keySecret, $keySecret );

$api->utility->verifyWebhookSignature( $webhookBody, $webhookSignature, $webhookSecret );


$orderId = $webhook->payload->payment->entity->order_id;
echo $orderId;

/*
$myfile = fopen( "testfile.txt", "w" );
fwrite( $myfile, $webhook->payload->payment->entity->order_id );
fclose( $myfile );
$webhook->event == "payment.failed" ||
*/

?>