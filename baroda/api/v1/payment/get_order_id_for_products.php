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
    $productArray = $_POST['productArray'];
    $amount = $_POST['amount'];
    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    } elseif (
        !isset( $student_id )
        || empty( trim( $student_id ) )
        || !isset( $productArray )
        || empty( trim( $productArray ) )
        || !isset( $amount )
        || empty( trim( $amount ) )
    ) {
        $returnData = msg( 1, 422, 'Please Fill in all Required Fields! $student_id,$amount,$productArray' );
    } else {

        $productIdArr = [];

        $productQtyArr = [];

        $decodedArray = json_decode( $productArray, true );

        // Create the Razorpay Order

        $api = new Api( $keyId, $keySecret );

        $orderData = [
            'amount'          => $amount * 100, // 2000 rupees in paise
            'currency'        => 'INR'
        ];

        $razorpayOrder = $api->order->create( $orderData );

        $razorpayOrderId = $razorpayOrder['id'];
        

        $queryCheck = "SELECT MAX(counter) as counter FROM `orders`";
        $query_stmt = $conn->prepare( $queryCheck );

        $allProductsInserted = false;

        if ( $query_stmt->execute() ) {
            $row = $query_stmt->fetch( PDO::FETCH_ASSOC );
            $counter = '';
            if ( empty( $row['counter'] ) ) {
                $counter = 1;
            } else {
                $counter = ( int )$row['counter'];
                $counter++;
            }
            foreach ( $decodedArray as $value ) {
                $name_var = isset( $value['name_var'] );
                $id = isset( $value['product_id'] );
                $var_id = isset( $value['variation_id'] );
                $qty = isset( $value['qty'] );
                $price = isset( $value['p_price'] );
                if ( $id && $qty && $price ) {
                    $insert = "INSERT INTO `orders` (`order_id`,`counter`, `student_id`, `product_id`, `name_var`, `variation_id`, `qty`, `p_price`, `rp_order_id`, `status`, `date`, `flag`) VALUES (NULL, '".$counter."', '".$student_id."', '".$value['product_id']."', '".$value['name_var']."', '".$value['variation_id']."', '".$value['qty']."', '".$value['p_price']."', '".$razorpayOrderId."', 0, CURDATE(), 0);";
                    $query_stmt = $conn->prepare( $insert );
                    if ( $query_stmt->execute() ) {
                        $allProductsInserted = true;
                    } else {
                        $allProductsInserted = false;
                    }
                }
            }
        }
        $query = "DELETE from cart WHERE student_id = ".$student_id;
        $query_stmt = $conn->prepare( $query );
        $query_stmt->execute();
        if ( $allProductsInserted ) {
            $returnData = [
                'success' => 1,
                'orderId' => $razorpayOrderId,
                'orderId' => $razorpayOrderId,
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
