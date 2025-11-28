<?php
if ( $_POST ) {
    if ( isset( $_POST['order_id'] ) && isset( $_POST['order_type'] ) ) {
        require __DIR__ . '/api/v2/classes/Database.php';
        require __DIR__ . '/api/v2/payment/check_order_id.php';
        $db_connection = new Database();
        $rzr = new Razorpay();
        $conn = $db_connection->dbConnection();
        $data = array( "order_found"=>false , "message"=> "No record found." );
        $orderId = trim($_POST['order_id']);
        $orderType = trim($_POST['order_type']);
        if ( $orderId != '' && $orderType != '' ) {
            if ($orderType == "fee") {
                //check in mysql first and create new object here if it works.
                $row = $rzr->getTranscationRow($conn,$orderId);
                if ($row != false) {
                    $orderStatus = $row['status'];
                    if ($orderStatus == "1") {
                        $data = $rzr->checkFeesData($conn,$row);
                    } else {
                        $orderStatus = $rzr->checkOrderId($orderId);
                        if ($orderStatus) {
                            $query = "UPDATE transcation SET status = '1' WHERE order_id = :order_id;";
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->bindParam( ':order_id', $orderId );
                            $query_stmt->execute();
                            $row = $rzr->getTranscationRow($conn,$orderId);
                            $data = $rzr->checkFeesData($conn,$row);
                        } else {
                            $data = array( "order_found"=>true , "message"=> "Order is not paid." );
                        }
                    }
                } else {
                    $data = array( "order_found"=>false , "message"=> "No record found." );
                }
                
            } else if ($orderType == "enq") {
                $row = $rzr->getEnquireRow($conn,$orderId);
                if ($row != false) {
                    $orderStatus = $row['payment_status'];
                    if ($orderStatus == "1") {
                        $data = array(
                            "order_found" => true,
                            "message" => "Everything seems good."
                        );
                    } else {
                        $orderStatus = $rzr->checkOrderId($orderId);
                        if ($orderStatus) {
                            $query = "UPDATE enquire SET payment_status = '1' WHERE order_id = :order_id;";
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->bindParam( ':order_id', $orderId );
                            $query_stmt->execute();
                            $data = array(
                                "order_found" => true,
                                "message" => "Order is fixed Kindly Check."
                            );
                        } else {
                            $data = array( "order_found"=>true , "message"=> "Order is not paid." );
                        }
                    }
                } else {
                    $data = array( "order_found"=>false , "message"=> "No record found." );
                }
                
            } else if ($orderType == "order") {
                $row = $rzr->getOrderRow($conn,$orderId);
                if ($row != false) {
                    $orderStatus = $row['status'];
                    if ($orderStatus == "1") {
                        $data = array(
                            "order_found" => true,
                            "message" => "Everything seems good."
                        );
                    } else {
                        $orderStatus = $rzr->checkOrderId($orderId);
                        if ($orderStatus) {
                            $query = "UPDATE orders SET status = '1' WHERE rp_order_id = :order_id;";
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->bindParam( ':order_id', $orderId );
                            $query_stmt->execute();
                            $data = array(
                                "order_found" => true,
                                "message" => "Order is fixed Kindly Check."
                            );
                        } else {
                            $data = array( "order_found"=>true , "message"=> "Order is not paid." );
                        }
                    }
                } else {
                    $data = array( "order_found"=>false , "message"=> "No record found." );
                }
                
            } else if ($orderType == "exam") {
                $row = $rzr->getExamFeeRow($conn,$orderId);
                if ($row != false) {
                    $orderStatus = $row['status'];
                    if ($orderStatus == "1") {
                        $data = array(
                            "order_found" => true,
                            "message" => "Everything seems good."
                        );
                    } else {
                        $orderStatus = $rzr->checkOrderId($orderId);
                        if ($orderStatus) {
                            $query = "UPDATE exam_fees SET status = '1' WHERE rp_order_id = :order_id;";
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->bindParam( ':order_id', $orderId );
                            $query_stmt->execute();
                            $data = array(
                                "order_found" => true,
                                "message" => "Order is fixed Kindly Check."
                            );
                        } else {
                            $data = array( "order_found"=>true , "message"=> "Order is not paid." );
                        }
                    }
                } else {
                    $data = array( "order_found"=>false , "message"=> "No record found." );
                }
                
            } else if ($orderType == "event") {
                $row = $rzr->getEventFeeRow($conn,$orderId);
                if ($row != false) {
                    $orderStatus = $row['status'];
                    if ($orderStatus == "1") {
                        $data = array(
                            "order_found" => true,
                            "message" => "Everything seems good."
                        );
                    } else {
                        $orderStatus = $rzr->checkOrderId($orderId);
                        if ($orderStatus) {
                            $query = "UPDATE event_fees SET status = '1' WHERE rp_order_id = :order_id;";
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->bindParam( ':order_id', $orderId );
                            $query_stmt->execute();
                            $data = array(
                                "order_found" => true,
                                "message" => "Order is fixed Kindly Check."
                            );
                        } else {
                            $data = array( "order_found"=>true , "message"=> "Order is not paid." );
                        }
                    }
                } else {
                    $data = array( "order_found"=>false , "message"=> "No record found." );
                }
                
            }
        }
         ob_end_clean();
        
         echo json_encode($data);
    }
}



?>
