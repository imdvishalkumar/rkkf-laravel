<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: GET" );
header( "Content-Type: application/json; charset=UTF-8" );
header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
// if ( true ) {

    $student_id = $_POST['student_id'];
    $months = $_POST['months'];
    $year = $_POST['year'];
    $amount = $_POST['amount'];
    // $amount = 1;
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

        // backup credencials
        // $keyId = 'rzp_live_H9VcMjuwzC0Aix';
        // $keySecret = '2lGJI6c4UMLeYuBnIBnINd2X';
        
        $branchIdOfKukuEXAM = ["68", "34", "67", "32", "35", "74"];
        $branchIdOfYogojuEvent = ["39", "72", "28", "71", "42", "73", "38", "70", "43", "31", "75", "27", "51", "56", "82","90"];
        $branchIdOfrkkfFee = ["66", "64", "29", "69", "41", "78", "30", "80", "26", "84", "53", "85", "65", "77", "33", "81", "37", "76", "83"];
    
        // SQL query to retrieve branch_id for the student
        // $branchNameQuery = "SELECT b.branch_id FROM students s JOIN branch b ON s.branch_id = b.branch_id WHERE s.student_id = ?";
        // $stmt = $conn->prepare($branchNameQuery);
        // $stmt->bind_param('i', $student_id);
        // $stmt->execute();
        // $result = $stmt->get_result();

        $branchNameQuery = "SELECT b.branch_id FROM students s JOIN branch b ON s.branch_id = b.branch_id WHERE s.student_id = :student_id";
        $stmt = $conn->prepare($branchNameQuery);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Create the Razorpay Order

        $keyId = '';
        $keySecret = '';
        
        if ($result) {
        // if ($result && $result->num_rows > 0) {
            // $row = $result->fetch_assoc();
            $branch_id = $result['branch_id'];
        
            // Determine which keyId and keySecret to use
            if (in_array($branch_id, $branchIdOfKukuEXAM)) {
                $keyId = 'rzp_live_pTWAP0kjfa1vE8';
                $keySecret = 'huA2LerjpYxROcvJgNzJptiP';
                $user = "KUKU";
            } else if (in_array($branch_id, $branchIdOfYogojuEvent)) {
                $keyId = 'rzp_live_aCvUQkKVnFxtrW';
                $keySecret = '8Stz6IasX2j9jUDUTKg11wPU';
                $user = "Yogoju";
            } else if (in_array($branch_id, $branchIdOfrkkfFee)) {
                $keyId = 'rzp_live_H9VcMjuwzC0Aix';
                $keySecret = '2lGJI6c4UMLeYuBnIBnINd2X';
                $user = "rkkf";
            } else {
                $keyId = 'rzp_live_bCTOrVy6sjvk7b';
                $keySecret = 'q89Hvvlc5qLi623yh6pMDG84';
                $user = "RF SALES";
            }
    
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
                    'keyId' => $keyId,
                    'message' => 'Order created.'
                ];
            } else {
                $returnData = msg( 1, 422, 'Unable to create order id!' );
            }
        } else {
            $returnData = msg( 1, 422, 'No branch found for the given student_id or query failed.' );
        }
    }

} else {
    $returnData = msg( 0, 401, 'Unauthorized!' );
}
header( 'Content-Type: application/json' );
echo json_encode( $returnData );
