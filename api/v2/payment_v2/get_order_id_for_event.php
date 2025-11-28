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
// require( 'config.php' );
require( 'razorpay-php/Razorpay.php' );

use Razorpay\Api\Api;

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$auth = new Auth( $conn, $allHeaders );

if ( $auth->isAuth() ) {

    $student_id = $_POST['student_id'];
    $event_id = $_POST['event_id'];
    $amount = $_POST['amount'];
    // $amount = 1;
    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    } elseif (
        !isset( $student_id )
        || empty( trim( $student_id ) )
        || !isset( $event_id )
        || empty( trim( $event_id ) )
        || !isset( $amount )
        || empty( trim( $amount ) )
    ) {
        $returnData = msg( 1, 422, 'Please Fill in all Required Fields! $student_id,$amount,$productArray' );
    } else {

        $productIdArr = [];

        $productQtyArr = [];

        // Create the Razorpay Order
        
        $keyId = 'rzp_live_aCvUQkKVnFxtrW';
        $keySecret = '8Stz6IasX2j9jUDUTKg11wPU';
        
        $api = new Api( $keyId, $keySecret );

        $orderData = [
            'amount'          => $amount * 100, // 2000 rupees in paise
            'currency'        => 'INR'
        ];

        $razorpayOrder = $api->order->create( $orderData );

        $razorpayOrderId = $razorpayOrder['id'];
        
        $insert = "INSERT INTO `event_fees` (`event_fees_id`, `event_id`, `student_id`, `date`, `mode`, `rp_order_id`, `status`, `amount`) VALUES (NULL, '".$event_id."', '".$student_id."', CURDATE(), 'razorpay', '".$razorpayOrderId."', '0', '".$amount."');";
        $query_stmt = $conn->prepare( $insert );
        if ( $query_stmt->execute() ) {
            $returnData = [
                'success' => 1,
                'orderId' => $razorpayOrderId,
                'keyId' => $keyId,
                'message' => 'Order created.'
            ];
        } else {
            $returnData = msg( 1, 422, 'Unable to create order id!' );
        }
     
    }
} else {
    $returnData = msg( 1, 401, 'Unauthorized!' );
}
header( 'Content-Type: application/json' );
echo json_encode( $returnData );
