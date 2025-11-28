<?php

    header( "Access-Control-Allow-Origin: *" );
    header( "Access-Control-Allow-Headers: access" );
    header( "Access-Control-Allow-Methods: GET" );
    header( "Content-Type: application/json; charset=UTF-8" );
    header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    
    require ( '../classes/Database.php' );
    require ( '../middlewares/Auth.php' );
    require( 'razorpay-php/Razorpay.php' );
    
    use Razorpay\Api\Api;
    
    $allHeaders = getallheaders();
    $db_connection = new Database();
    $conn = $db_connection->dbConnection();
    $auth = new Auth( $conn, $allHeaders );
    
    // RKKF
    // $rKey = 'rzp_live_H9VcMjuwzC0Aix';
    // $rSecret = '2lGJI6c4UMLeYuBnIBnINd2X';
    
    // KUKU SERVICES
    // $rKey = 'rzp_live_pTWAP0kjfa1vE8';
    // $rSecret = 'huA2LerjpYxROcvJgNzJptiP';
    
    // // RF SALES
    // $rKey = 'rzp_live_bCTOrVy6sjvk7b';
    // $rSecret = 'q89Hvvlc5qLi623yh6pMDG84';
    
    // //YOGOJU
    // $rKey = 'rzp_live_aCvUQkKVnFxtrW';
    // $rSecret = '8Stz6IasX2j9jUDUTKg11wPU';
    
    $api = new Api($rKey, $rSecret);

    // Define date range (convert to UNIX timestamps)
    $fromDate = strtotime('2025-06-06 00:00:00'); // Start of day
    $toDate = strtotime('2025-06-08 23:59:59');   // End of day
    
    try {
        $orders = $api->order->all([
            'from' => $fromDate,
            'to' => $toDate,
            'count' => 100 // max per page
        ]);
    
        if (count($orders['items']) === 0) {
            echo "No Razorpay orders found between 2025-06-06 and 2025-06-08.\n";
        } else {
            foreach ($orders['items'] as $order) {
                
                if (in_array($order['status'], ['created', 'attempted'])) {
                    continue;
                }

                $orderId = $order['id'];
                // echo "Order ID: {$order['id']} | Amount: " . ($order['amount'] / 100) . " | Status: " . ($order['status']) . " | Created At: " . date('Y-m-d H:i:s', $order['created_at']) . "\n";
                    
                    // RKKF Fee Pending status update
                        // $update = "update transcation set status = 1 where order_id ='".$orderId."'";
                        // $query_stmt = $conn->prepare( $update );
                        // if ( $query_stmt->execute() ) {
                        //     $queryCheck = "SELECT * FROM fees WHERE mode = '".$orderId."'";
                        //     $query_stmt = $conn->prepare( $queryCheck );
                        //     $query_stmt->execute();
                        //     if ( $query_stmt->rowCount() > 0 ) {
                        //         $returnData = [
                        //             'success' => 1,
                        //             'saved' => 1,
                        //             'message' => 'Payment Success.'
                        //         ];
                        //     } else {
                        //         $query = "select * from transcation where order_id ='".$orderId."'";
                        //         $query_stmt = $conn->prepare( $query );
                        //         $query_stmt->execute();
                        //         $row = $query_stmt->fetch( PDO::FETCH_ASSOC );
                        //         $monthsArray = explode( ',', $row['months'] );
                        //         $count = count( $monthsArray );
                        //         $amount = $row['amount'];
                        //         $check = $amount % $count;
                        //         $fees = ( $amount-$check ) / $count;
                        //         $temp = 0;
                        //         $AllFeesInserted = false;
                        //         foreach ( $monthsArray as $month ) {
                        //             if ( $temp == 0 ) {
                        //                 $temp++;
                        //                 $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (".$row['student_id'].",$month,'".$row['year']."','".$row['date']."','".( $fees + $check )."','".$row['coupon_id']."','0','0','".$orderId."')";
                        //                 $query_stmt = $conn->prepare( $feeQuery );
                        //                 $query_stmt->execute();
                        //                 $feeId = $conn->lastInsertId();
                        //                 $update = "update transcation set ref_id = '".$feeId."' where order_id ='".$orderId."'";
                        //                 $query_stmt = $conn->prepare( $update );
                        //                 if ( $query_stmt->execute() ) {
                        //                     $AllFeesInserted = true;
                        //                 } else {
                        //                     $AllFeesInserted = false;
                        //                     break;
                        //                 }
                        //             } else {
                        //                 $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (".$row['student_id'].",$month,'".$row['year']."','".$row['date']."','$fees','".$row['coupon_id']."','0','0','".$orderId."')";
                        //                 $query_stmt = $conn->prepare( $feeQuery );
                        //                 $query_stmt->execute();
                        //                 $feeId = $conn->lastInsertId();
                        //                 $update = "update transcation set ref_id = '".$feeId."' where order_id ='".$orderId."'";
                        //                 $query_stmt = $conn->prepare( $update );
                        //                 if ( $query_stmt->execute() ) {
                        //                     $AllFeesInserted = true;
                        //                 } else {
                        //                     $AllFeesInserted = false;
                        //                     break;
                        //                 }
                        //             }
                        //             if ( $month == 12 ) {
                        //                 $row['year'] = $row['year'] + 1;
                        //             }
                        //         }
                        //         if ( $AllFeesInserted ) {
                        //             $returnData = [
                        //                 'success' => 1,
                        //                 'saved' => 1,
                        //                 'message' => 'Payment Success.'
                        //             ];
                        //         } else {
                        //             $returnData = msg( 1, 200, 'Payment Failed.' );
                        //         }
                        //     }
                        // } else {
                        //     $myfile = fopen( "monthlyFeesLogs.txt", "a" );
                        //     fwrite( $myfile, $orderId );
                        //     fclose( $myfile );
                        // }
                    // END Rkkf 
                    
                    
                    // Start KUKU pending status update
                        // $update = "update exam_fees set status = 1 where rp_order_id ='".$orderId."'";
                        // $query_stmt = $conn->prepare( $update );
                        // if ( $query_stmt->execute() ) {
                        //     $returnData = [
                        //         'success' => 1,
                        //         'saved' => 1,
                        //         'message' => 'Payment Success.'
                        //     ];
                        // } else {
                        //     $returnData = msg( 1, 200, 'Payment Failed.' );
                        // }
                    // END KUKU
                    
                    // RF SALES pending status update
                        // $select = "SELECT flag FROM `orders` WHERE flag = 0 AND rp_order_id ='".$orderId."'";
                        // $query_stmt = $conn->prepare( $select );
                        // $query_stmt->execute();
                        // if ( $query_stmt->rowCount() > 0 ) {
                        //     // get var id
                        //     $select = "SELECT id FROM variation WHERE id IN (SELECT variation_id FROM orders WHERE rp_order_id = '".$orderId."')";
                        //     $query_stmt = $conn->prepare( $select );
                        //     $query_stmt->execute();
                        //     if ( $query_stmt->rowCount() > 0 ) {
                        //         $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
                        //         $totalRows = $query_stmt->rowCount();
                        //         $i = 0;
                        //         while( $i < $totalRows ) {
                        //             if ( $row[$i]['id'] != NULL ) {
                        //                 $update = "UPDATE variation SET qty = qty - (SELECT qty FROM orders WHERE variation_id = '".$row[$i]['id']."' AND rp_order_id = '".$orderId."') WHERE id = '".$row[$i]['id']."'";
                        //                 $query_stmt = $conn->prepare( $update );
                        //                 $query_stmt->execute();
                        //             }
                        //             $i++;
                        //         }
                        //     }
                        //     $update = "update orders set status = 1 , flag = 1 where rp_order_id ='".$orderId."'";
                        //     $query_stmt = $conn->prepare( $update );
                        //     if ( $query_stmt->execute() ) {
                        //         $returnData = [
                        //             'success' => 1,
                        //             'saved' => 1,
                        //             'message' => 'Payment Success.'
                        //         ];
                        //     } else {
                        //         $returnData = msg( 1, 200, 'Payment Failed.' );
                        //     }
                        // } else {
                        //     $update = "update orders set status = 1 where rp_order_id ='".$orderId."'";
                        //     $query_stmt = $conn->prepare( $update );
                        //     if ( $query_stmt->execute() ) {
                        //         $returnData = [
                        //             'success' => 1,
                        //             'saved' => 1,
                        //             'message' => 'Payment Success.'
                        //         ];
                        //     } else {
                        //         $returnData = msg( 1, 200, 'Payment Failed.' );
                        //     }
                        // }
                    // End RF SALES
                
            }
        }
    
    } catch (\Razorpay\Api\Errors\Error $e) {
        echo "Razorpay API Error: " . $e->getMessage();
    }

    // $sql = "SELECT * FROM `transcation` WHERE `status` = 0 AND `date` BETWEEN '2025-06-06' AND '2025-06-08' ORDER BY `order_id` LIMIT 10";
    // $stmt = $conn->prepare($sql);
    // $stmt->execute(); // ✅ Execute the statement
    
    // $allPayments = $stmt->fetchAll(PDO::FETCH_ASSOC); // ✅ Then fetch results
    
    // if (!$allPayments) {
    //     echo json_encode(['success' => 0, 'message' => 'Missing order_id']);
    //     exit;
    // }
    
    // if (is_array($allPayments) && count($allPayments) > 0) {
    //      foreach ($allPayments as $payment) {
    //         $orderId = $payment['order_id'];
    //         // Razorpay credentials
    //         $rKey = get_env_variable('RAZORPAY_API_LIVE_KEY');
    //         $rSecret = get_env_variable('RAZORPAY_API_LIVE_SECRET');
            
    //         $api = new Api($rKey, $rSecret);
            
    //         try {
    //             $payments = $api->order->fetch($orderId)->payments();
    //         } catch (Error $e) {
    //             Log::error("Razorpay fetch error: " . $e->getMessage());
    //             echo json_encode(['success' => 0, 'message' => 'Failed to fetch payment info']);
    //             exit;
    //         }
            
    //         // ✅ Update status only for specific date range
    //         $update = "UPDATE transcation SET status = 1 WHERE order_id = :order_id AND `date` BETWEEN '2025-06-06' AND '2025-06-08'";
            
    //         $query_stmt = $conn->prepare($update);
    //         $query_stmt->bindParam(':order_id', $orderId);
            
    //         if ($query_stmt->execute()) {
    //             // ✅ Check if already inserted
    //             $queryCheck = "SELECT * FROM fees WHERE mode = :order_id";
    //             $query_stmt = $conn->prepare($queryCheck);
    //             $query_stmt->bindParam(':order_id', $orderId);
    //             $query_stmt->execute();
            
    //             if ($query_stmt->rowCount() > 0) {
    //                 $returnData = ['success' => 1, 'saved' => 1, 'message' => 'Payment already processed.'];
    //             } else {
    //                 // ⬇ Fetch transaction data
    //                 $query = "SELECT * FROM transcation WHERE order_id = :order_id";
    //                 $query_stmt = $conn->prepare($query);
    //                 $query_stmt->bindParam(':order_id', $orderId);
    //                 $query_stmt->execute();
    //                 $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
            
    //                 if (!$row) {
    //                     echo json_encode(['success' => 0, 'message' => 'Transaction not found']);
    //                     exit;
    //                 }
            
    //                 $monthsArray = explode(',', $row['months']);
    //                 $count = count($monthsArray);
    //                 $amount = $row['amount'];
    //                 $check = $amount % $count;
    //                 $fees = ($amount - $check) / $count;
    //                 $year = $row['year'];
    //                 $allFeesInserted = true;
            
    //                 foreach ($monthsArray as $index => $month) {
    //                     $feeAmount = ($index == 0) ? $fees + $check : $fees;
    //                     $feeQuery = "INSERT INTO fees 
    //                                  (student_id, months, year, date, amount, coupon_id, additional, disabled, mode)
    //                                  VALUES (:student_id, :months, :year, :date, :amount, :coupon_id, '0', '0', :mode)";
    //                     $query_stmt = $conn->prepare($feeQuery);
    //                     $query_stmt->execute([
    //                         ':student_id' => $row['student_id'],
    //                         ':months' => $month,
    //                         ':year' => $year,
    //                         ':date' => $row['date'],
    //                         ':amount' => $feeAmount,
    //                         ':coupon_id' => $row['coupon_id'],
    //                         ':mode' => $orderId
    //                     ]);
            
    //                     $feeId = $conn->lastInsertId();
            
    //                     $update = "UPDATE transcation SET ref_id = :fee_id WHERE order_id = :order_id";
    //                     $query_stmt = $conn->prepare($update);
    //                     if (!$query_stmt->execute([':fee_id' => $feeId, ':order_id' => $orderId])) {
    //                         $allFeesInserted = false;
    //                         break;
    //                     }
            
    //                     if ((int)$month === 12) {
    //                         $year++;
    //                     }
    //                 }
            
    //                 $returnData = $allFeesInserted
    //                     ? ['success' => 1, 'saved' => 1, 'message' => 'Payment Success.']
    //                     : ['success' => 0, 'message' => 'Payment failed during fee insertion.'];
    //             }
    //         } else {
    //             // Log failed update
    //             file_put_contents("monthlyFeesLogs.txt", $orderId . PHP_EOL, FILE_APPEND);
    //             $returnData = ['success' => 0, 'message' => 'Failed to update transaction status.'];
    //         }
    //      }
    // }
    


    error_reporting(E_ALL);
    ini_set('display_errors', 1);
