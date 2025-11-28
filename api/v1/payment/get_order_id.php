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

    $student_id = $_POST['student_id'];
    $months = $_POST['months'];
    $year = $_POST['year'];
    $amount = $_POST['amount'];
    $coupon_id = $_POST['coupon_id'];
    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
        $returnData = msg( 0, 404, 'Page Not Found!' );
    } elseif (
        !isset( $student_id )
        || empty( trim( $student_id ) )
        || !isset( $months )
        || empty( trim( $months ) )
        || !isset( $year )
        || empty( trim( $year ) )
        || !isset( $amount )
        || empty( trim( $amount ) )
        || !isset( $coupon_id )
        || empty( trim( $coupon_id ) )
    ) {
        $returnData = msg( 0, 422, 'Please Fill in all Required Fields! $student_id,$months,$year,$amount,$coupon_id' );
    } else {

        // Create the Razorpay Order


        $api = new Api( $keyId, $keySecret );

        $orderData = [
            'amount'          => $amount * 100, // 2000 rupees in paise
            'currency'        => 'INR'
        ];

        $razorpayOrder = $api->order->create( $orderData );

        $razorpayOrderId = $razorpayOrder['id'];

        $insert = "INSERT INTO `transcation` (`transcation_id`, `student_id`, `order_id`, `status`, `type`, `ref_id`, `amount`, `date`, `months`, `year`, `coupon_id`) VALUES (NULL, $student_id, '".$razorpayOrderId."', 'pending', 'fees', '0', '".$amount."', CURDATE(), '".$months."', '".$year."', '".$coupon_id."');";
        $query_stmt = $conn->prepare( $insert );
        if ( $query_stmt->execute() ) {
            $returnData = [
                'success' => 1,
                'orderId' => $razorpayOrderId,
                'message' => 'Order created.'
            ];
        } else {
            $returnData = msg( 1, 422, 'Unable to create order id!' );
        }
    }

} else {
    $returnData = msg( 0, 401, 'Unauthorized!' );
}
header( 'Content-Type: application/json' );
echo json_encode( $returnData );
