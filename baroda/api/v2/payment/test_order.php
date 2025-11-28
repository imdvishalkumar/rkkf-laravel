<?php
require( 'config.php' );
        require( 'razorpay-php/Razorpay.php' );

        // Create the Razorpay Order

        use Razorpay\Api\Api;

        $api = new Api( $keyId, $keySecret );

        $orderData = [
            'amount'          => 100 * 100, // 2000 rupees in paise
            'currency'        => 'INR'
        ];

        $razorpayOrder = $api->order->create( $orderData );

        $razorpayOrderId = $razorpayOrder['id'];

        echo $razorpayOrderId;
?>