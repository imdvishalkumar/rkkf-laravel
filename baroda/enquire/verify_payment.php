<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require ( '../api/v1/classes/Database.php' );
require( 'config.php' );
require( 'razorpay-php/Razorpay.php' );
use Razorpay\Api\Api;

if(!isset($_POST['razorpay_payment_id']) 
    || !isset($_POST['razorpay_order_id'])
    || !isset($_POST['razorpay_signature'])
    || empty(trim($_POST['razorpay_payment_id']))
    || empty(trim($_POST['razorpay_order_id']))
    || empty(trim($_POST['razorpay_signature']))
    ) {
    echo "Unauthorized";
} else {
    $orderId = trim($_POST['razorpay_order_id']);
    $paymentId = trim($_POST['razorpay_payment_id']);
    $signature = trim($_POST['razorpay_signature']);
    try {
        $api = new Api($keyId, $keySecret);
        $attributes  = array('razorpay_signature'  => $signature,  'razorpay_payment_id'  => $paymentId ,  'razorpay_order_id' => $orderId);
        $api->utility->verifyPaymentSignature($attributes);
        
        $db_connection = new Database();
        $conn = $db_connection->dbConnection();
        $update = "update enquire set payment_status = 1, payment_id = :payment_id where order_id = :order_id";
        $query_stmt = $conn->prepare( $update );
        $query_stmt->bindParam( ':payment_id', $paymentId );
        $query_stmt->bindParam( ':order_id', $orderId );
        if ( $query_stmt->execute() ) {
            
			$_SESSION["enquire"] = "enquire";

            if(isset($_SESSION["enquire"])) {
                header("Location: success_enquire");
            }
        } else {
            echo "transcation failed if money is deducted from your account please contact admin at : +91 98243 58718.";
        }
        
    }
    catch(Exception $e) {
        echo 'Message: ' .$e->getMessage();
    }

}

?>