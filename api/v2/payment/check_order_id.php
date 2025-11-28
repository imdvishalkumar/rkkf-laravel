<?php

require( 'razorpay-php/Razorpay.php' );

use Razorpay\Api\Api;

class Razorpay
{
    public function checkOrderId(string $orderId = "")
    {
        require ('config.php');
        require ('razorpay-php/Razorpay.php');

        if (empty($orderId))
        {
            return false;
        }

        $api = new Api($keyId, $keySecret);

        $api->setHeader('x-razorpay-account', '');

        $order = $api
            ->order
            ->fetch($orderId);

        $oStatus = $order->status;

        if ($oStatus == "paid")
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    public function checkOrderIdAcrossAccounts(string $orderId = "")
    {
        if (empty($orderId)) {
            return [
                'success' => false,
                'message' => 'Order ID is required'
            ];
        }
        
        $accounts = [
            [
                'keyId' => 'rzp_live_pTWAP0kjfa1vE8',
                'keySecret' => 'huA2LerjpYxROcvJgNzJptiP'
            ],
            [
                'keyId' => 'rzp_live_aCvUQkKVnFxtrW',
                'keySecret' => '8Stz6IasX2j9jUDUTKg11wPU'
            ],
            [
                'keyId' => 'rzp_live_H9VcMjuwzC0Aix',
                'keySecret' => '2lGJI6c4UMLeYuBnIBnINd2X'
            ],
            [
                'keyId' => 'rzp_live_bCTOrVy6sjvk7b',
                'keySecret' => 'q89Hvvlc5qLi623yh6pMDG84'
            ],
        ];

    
        if (empty($accounts)) {
            return [
                'success' => false,
                'message' => 'No accounts configured'
            ];
        }
    
        foreach ($accounts as $account) {
            try {
                $keyId = $account['keyId'];
                $keySecret = $account['keySecret'];
    
                $api = new Api($keyId, $keySecret);
    
                if (!empty($account['xRazorpayAccount'])) {
                    $api->setHeader('x-razorpay-account', $account['xRazorpayAccount']);
                }
    
                // Attempt to fetch the order
                $order = $api->order->fetch($orderId);
    
                // Check the order status
                $oStatus = $order->status;
    
                if ($oStatus === "paid") {
                    return [
                        'success' => true,
                        'message' => 'Order is paid',
                        'account' => $account
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Order is not paid',
                        'account' => $account
                    ];
                }
            } catch (BadRequestError $e) {
                // Continue to the next account if the order ID is not found
                continue;
            } catch (Exception $e) {
                // Continue to the next account if the order ID is not found
                continue;
            }
        }
    
        // If no account has the order
        return [
            'success' => false,
            'message' => 'Order not found in any account2'
        ];
    }



    public function getTranscationRow($conn,$orderId)
    {
        $query = "SELECT * FROM transcation WHERE order_id = :order_id;";
        $query_stmt = $conn->prepare($query);
        $query_stmt->bindParam(':order_id', $orderId);
        $query_stmt->execute();
        $row = $query_stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }

    public function getEnquireRow($conn,$orderId)
    {
        $query = "SELECT * FROM enquire WHERE order_id = :order_id;";
        $query_stmt = $conn->prepare($query);
        $query_stmt->bindParam(':order_id', $orderId);
        $query_stmt->execute();
        $row = $query_stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }

    public function getOrderRow($conn,$orderId)
    {
        $query = "SELECT * FROM orders WHERE rp_order_id = :order_id;";
        $query_stmt = $conn->prepare($query);
        $query_stmt->bindParam(':order_id', $orderId);
        $query_stmt->execute();
        $row = $query_stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }

    public function getExamFeeRow($conn,$orderId)
    {
        $query = "SELECT * FROM exam_fees WHERE rp_order_id = :order_id;";
        $query_stmt = $conn->prepare($query);
        $query_stmt->bindParam(':order_id', $orderId);
        $query_stmt->execute();
        $row = $query_stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }

    public function getEventFeeRow($conn,$orderId)
    {
        $query = "SELECT * FROM event_fees WHERE rp_order_id = :order_id;";
        $query_stmt = $conn->prepare($query);
        $query_stmt->bindParam(':order_id', $orderId);
        $query_stmt->execute();
        $row = $query_stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }

    public function checkFeesData($conn, $row)
    {
        var_dump($row);
        $orderId = $row['order_id'];
        $studentId = $row['student_id'];
        $constantYear = $row['year'];
        $year = $constantYear;
        $date = $row['date'];
        $couponId = $row['coupon_id'];
        $monthsArray = explode(',', $row['months']);
        $count = count($monthsArray);
        $amount = $row['amount'];
        $check = $amount % $count;
        $fees = ($amount - $check) / $count;
        $AllFeesInserted = array();
        foreach ($monthsArray as $month)
        {
            $query = "SELECT * FROM fees WHERE mode = :order_id AND year = :year AND months = :months AND student_id = :student_id;";
            $query_stmt = $conn->prepare($query);
            $query_stmt->bindParam(':student_id', $studentId);
            $query_stmt->bindParam(':order_id', $orderId);
            $query_stmt->bindParam(':year', $year);
            $query_stmt->bindParam(':months', $month);
            $query_stmt->execute();
            $row = $query_stmt->fetch(\PDO::FETCH_ASSOC);
            if ($row != false)
            {
                array_push($AllFeesInserted, true);
            }
            else
            {
                array_push($AllFeesInserted, false);
            }
            if ($month == 12)
            {
                $year = $year + 1;
            }
        }
        $allOk = true;
        foreach ($AllFeesInserted as $fee)
        {
            if (!($fee))
            {
                $allOk = false;
            }
        }
        if ($allOk)
        {
            $data = array(
                "order_found" => true,
                "message" => "Everything seems good."
            );
            return $data;
        }
        else
        {
            $year = $constantYear;
            for ($x = 0;$x < $count;$x++)
            {
                if (!($AllFeesInserted[$x]))
                {
                    $amount = $fees;
                    $month = $monthsArray[$x];
                    if ($x == 0)
                    {
                        $amount = $fees + $check;
                    }
                    else
                    {
                        if ($month == 1)
                        {
                            $year = $year + 1;
                        }
                    }
                    $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values (:student_id,:months,:year,:date,:amount,:coupon_id,'0','0',:order_id)";
                    $query_stmt = $conn->prepare($feeQuery);
                    $query_stmt->bindParam(':student_id', $studentId);
                    $query_stmt->bindParam(':months', $month);
                    $query_stmt->bindParam(':year', $year);
                    $query_stmt->bindParam(':date', $date);
                    $query_stmt->bindParam(':amount', $amount);
                    $query_stmt->bindParam(':coupon_id', $couponId);
                    $query_stmt->bindParam(':order_id', $orderId);
                    $query_stmt->execute();
                }
            }
            $data = array(
                "order_found" => true,
                "message" => "Order is fixed Kindly Check."
            );
            return $data;
        }
    }
}

?>
