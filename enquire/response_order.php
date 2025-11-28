<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: GET" );
header( "Content-Type: application/json; charset=UTF-8" );
header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );

function callAPI( $url, $data = false ) {
    $curl = curl_init( $url );
    curl_setopt( $curl, CURLOPT_POST, 1 );
    if ( $data )
    curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $data ) );

    curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );

    curl_setopt( $curl, CURLOPT_HTTPHEADER, [
        'authorization: 51OQh7kBlMNs3vb0LrYUouZTpqEFIfi9gWdSeDaJAV6jCy8wmK3h8GVaHuWUjIfQYiK5cZNbTX29eLrO',
        'Content-Type: application/json'
    ] );

    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

    $result = curl_exec( $curl );

    curl_close( $curl );

    return $result;

}

function msg( $success, $status, $message, $extra = [] ) {
    return array_merge( [
        'success' => $success,
        'error' => $status,
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
    $status = $_POST['status'];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
        $returnData = msg( 0, 1, 'Page Not Found!' );
    } elseif (
        !isset( $orderId )
        || empty( trim( $orderId ) )
        || !isset( $paymentId )
        || empty( trim( $paymentId ) )
    ) {
        $returnData = msg( 1, 1, 'Please Fill in all Required Fields!' );
    } else {

        try {

            if ( $status == "1" ) {
                $sig = hash_hmac( 'sha256', $orderId."|".$paymentId, $keySecret );
                //$sig = hmac_sha256( $orderId + "|" + $paymentId, $keySecret );

                if ( $signature == $sig ) {

                    $update = "update transcations set status = 1, razorpay_payment_id ='".$paymentId."' where razorpay_order_id ='".$orderId."'";
                    $query_stmt = $conn->prepare( $update );
                    if ( $query_stmt->execute() ) {

                        $query = "SELECT o.order_id,(SELECT sub_category_name FROM sub_category WHERE sub_category_id = o.sub_category_id) as title, t.total , o.user_id, (SELECT number from users WHERE user_id = o.user_id) as user_number, s.partner_id, (SELECT number from users WHERE user_id = s.partner_id) as partner_number from orders o, transcations t, shortlisted_partners s WHERE t.transcation_id = o.transcation_id AND o.order_id = s.order_id AND t.razorpay_order_id = '".$orderId."';";
                        $query_stmt = $conn->prepare( $query );
                        $query_stmt->execute();
                        if ( $query_stmt->rowCount() > 0 ) {
                            $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
                            $phone = $row[0]['user_number'];
                            $order_id = $row[0]['order_id'];
                            $amount =  $row[0]['total'];
                            $title =  $row[0]['title'];
                            $data = array( "route"=>"q", "message"=>"Your order no.".$order_id."  of Rs. ".$amount." for ".$title." service has been successfully placed with Buffering Bazaar. Please check your orders section for delivery - Buffering Bazaar Team", "language"=>"english", "flash"=>"0", "numbers"=>$phone );
                            $url = "https://www.fast2sms.com/dev/bulkV2";
                            $result = callAPI( $url, $data );
                            $res = json_decode( $result, true );

                            $totalRows = $query_stmt->rowCount();
                            $i = 0;
                            while ( $i < $totalRows ) {

                                $phone = $row[$i]['partner_number'];
                                $title =  $row[$i]['title'];
                                $data = array( "route"=>"q", "message"=>"Hey. You have received an enquiry for $title service. Kindly check your Enquires section in Buffering Bazaar app for accepting - Buffering Bazaar Team", "language"=>"english", "flash"=>"0", "numbers"=>$phone );
                                $url = "https://www.fast2sms.com/dev/bulkV2";
                                $result = callAPI( $url, $data );

                                $i++;
                            }
                        }

                        $returnData = [
                            'success' => 1,
                            'error' => 0,
                            'saved' => 1,
                            'message' => 'Payment Success.'
                        ];
                    } else {
                        $returnData = [
                            'success' => 1,
                            'error' => 1,
                            'saved' => 1,
                            'message' => 'Payment Success.'
                        ];
                    }

                } else {
                    $returnData = msg( 1, 1, 'Payment Failed.' );
                }
            } else {
                $update = "update transcations set status = 2, razorpay_payment_id ='".$paymentId."' where razorpay_order_id ='".$orderId."'";
                $query_stmt = $conn->prepare( $update );
                if ( $query_stmt->execute() ) {
                    $returnData = msg( 1, 1, 'Payment Failed.' );
                }

            }

        } catch( PDOException $e ) {
            $returnData = msg( 1, 1, $e->getMessage() );
        }
    }

} else {
    $returnData = msg( 1, 1, 'Unauthorized!' );
}

header( 'Content-Type: application/json' );
echo json_encode( $returnData );
?>