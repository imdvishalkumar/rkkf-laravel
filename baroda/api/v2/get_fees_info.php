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

require __DIR__.'/classes/Database.php';
require __DIR__.'/middlewares/Auth.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$auth = new Auth( $conn, $allHeaders );

if ( $auth->isAuth() ) {

    $id = $_GET['id'];
    $role = $_GET['role'];
    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "GET" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $id )
        || empty( trim( $id ) )
        || !isset( $role )
        || empty( trim( $role ) ) ) {

            $returnData = msg( 1, 422, 'Please Fill in all Required Fields!' );
        }
        // IF THERE ARE NO EMPTY FIELDS THEN-
        else {
            $id = trim( $id );
            $role = trim( $role );

            // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
            if ( !filter_var( $id, FILTER_VALIDATE_INT ) ) {
                $returnData = msg( 1, 422, 'Invalid ID!' );
            }
            // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
            else {
                try {
                    $query = "SELECT * FROM fees WHERE student_id = ".$id." ORDER BY year DESC , months DESC LIMIT 0,1";
                    $query_stmt = $conn->prepare( $query );
                    $query_stmt->execute();
                    // if exam exists
                    if ( $query_stmt->rowCount() > 0 ) {
                        $row = $query_stmt->fetch( PDO::FETCH_ASSOC );
                        $date = "";
                        $temp = $row['months'];

                        // if black belt
                        $query = "SELECT belt_id FROM students WHERE student_id = ".$id." AND belt_id >= (SELECT belt_id FROM belt WHERE name = 'Black Belt')";
                        $query_stmt = $conn->prepare( $query );
                        $query_stmt->execute();
                        // is black belt
                        if ( $query_stmt->rowCount() > 0 ) {
                            $monthStr = "";
                            $month = date( "m" );
                            $year = date( "Y" );
                            $i = 0;
                            while( $i<12 ) {
                                if ( $i == 11 ) {
                                    $monthStr = $monthStr.$month;
                                } else {
                                    $monthStr = $monthStr.$month.",";
                                }
                                if ( $month == 12 ) {
                                    $month = 0;
                                }
                                $month++;

                                $i++;
                            }
                            $dueRow[0] = array( "feeFor"=>"12 Month", "monthPay"=>$monthStr, "yearPay"=>$year, "amountPay"=>7000, "lateFee"=>$lateFee, "discountedFee"=> 0, "showSpinner"=> ( $monthCount-1 ) );

                            $returnData = [
                                'success' => 1,
                                'data' => $row,
                                'due' => $dueRow
                            ];
                        } else {
                            $query = "SELECT branch_id FROM students WHERE student_id = ".$id;
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->execute();
                            $rowBranch = $query_stmt->fetch( PDO::FETCH_ASSOC );
                            $branchId = $rowBranch['branch_id'];

                            $query = "SELECT * FROM branch WHERE branch_id = ".$branchId;
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->execute();
                            $monthAmount = $query_stmt->fetch( PDO::FETCH_ASSOC );
                            $monthlyFees = $monthAmount['fees'];
                            $lateFees = $monthAmount['late'];
                            $discountFees = $monthAmount['discount'];

                            $query = "SELECT to_belt_id, from_belt_id ,(to_belt_id - from_belt_id) as belt_up, TIMESTAMPDIFF(MONTH, from_date, to_date) as months_diff, total_fees FROM `fastrack` WHERE student_id = '".$id."' AND from_date <= CURDATE() AND to_date >= CURDATE();";
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->execute();
                            $fastrackStudent = false;

                            if ( $query_stmt->rowCount() > 0 ) {
                                $fastrackRow = $query_stmt->fetch( PDO::FETCH_ASSOC );
                                $beltUp = $fastrackRow['belt_up'];
                                $fromBeltId = $fastrackRow['from_belt_id'];
                                $toBeltId = $fastrackRow['to_belt_id'];
                                $monthDiff = $fastrackRow['months_diff'] + 1;
                                $totalFees = $fastrackRow['total_fees'];
                                $monthlyFees = $totalFees / $monthDiff;

                                $fastrackStudent = true;

                            }

                            $feeFor = "";
                            $monthPay = "";
                            $yearPay = "";
                            $lateFee = "";
                            $discountedFee = "";

                            //for late fees
                            $dateDiffQuery = "SELECT TIMESTAMPDIFF (MONTH, '".$row['year']."-".$row['months']."-01"."', '".date( "Y" )."-".date( "m" )."-01"."') as count";
                            $query_stmt = $conn->prepare( $dateDiffQuery );
                            $query_stmt->execute();
                            $lateCountRow = $query_stmt->fetch( PDO::FETCH_ASSOC );
                            $monthCount = $lateCountRow['count'];
                            $lateFee = ( $lateFees*( $monthCount-1 ) );

                            if ( ( $monthCount-1 ) >2 ) {
                                $monthCount = 3;
                            }

                            if ( $fastrackStudent ) {
                                //for 1 month
                                $yearPay = $row['year'];
                                if ( $row['months'] == 12 ) {
                                    $row['months'] = 0;
                                    $yearPay++;
                                }
                                $dueRow[0] = array( "feeFor"=>"1 Month", "monthPay"=>strval( $row['months'] + 1 ), "yearPay"=>$yearPay, "amountPay"=>$monthlyFees, "lateFee"=>$lateFee, "discountedFee"=> 0, "showSpinner"=> ( $monthCount-1 ) );

                                //for 2 months
                                if ( $row['months'] == 11 ) {
                                    $dueRow[1] = array( "feeFor"=>"2 Months", "monthPay"=>( $row['months'] + 1 ).",".( 1 ), "yearPay"=>$yearPay, "amountPay"=>( ( $monthlyFees * 2 ) ), "lateFee"=>$lateFee, "discountedFee"=> 0, "showSpinner"=> ( $monthCount-1 ) );
                                } else {
                                    $dueRow[1] = array( "feeFor"=>"2 Months", "monthPay"=>( $row['months'] + 1 ).",".( $row['months'] + 2 ), "yearPay"=>$yearPay, "amountPay"=>( ( $monthlyFees * 2 ) ), "lateFee"=>$lateFee, "discountedFee"=> 0, "showSpinner"=> ( $monthCount-1 ) );
                                }

                                //for 3 months

                                if ( ( date( "d" ) <= 15 ) && ( ( $row['months']+1 ) == date( "m" ) ) ) {
                                    $discountedFee = 0;
                                } else if ( ( ( $row['months'] ) >= date( "m" ) ) && $yearPay == date( "Y" ) ) {
                                    $discountedFee = 0;
                                } else {
                                    $discountedFee = 0;
                                }
                                if ( $row['months'] == 10 ) {
                                    $dueRow[2] = array( "feeFor"=>"3 Months", "monthPay"=>( $row['months'] + 1 ).",".( $row['months'] + 2 ).",".( 1 ), "yearPay"=>$yearPay, "amountPay"=>( $monthlyFees * 3 ), "lateFee"=>$lateFee, "discountedFee"=> 0, "showSpinner"=> ( $monthCount-1 ) );
                                } else if ( $row['months'] == 11 ) {
                                    $dueRow[2] = array( "feeFor"=>"3 Months", "monthPay"=>( $row['months'] + 1 ).",".( 1 ).",".( 2 ), "yearPay"=>$yearPay, "amountPay"=>( $monthlyFees * 3 ), "lateFee"=>$lateFee, "discountedFee"=> 0, "showSpinner"=> ( $monthCount-1 ) );
                                } else {
                                    $dueRow[2] = array( "feeFor"=>"3 Months", "monthPay"=>( $row['months'] + 1 ).",".( $row['months'] + 2 ).",".( $row['months'] + 3 ), "yearPay"=>$yearPay, "amountPay"=>( $monthlyFees * 3 ), "lateFee"=>$lateFee, "discountedFee"=> 0, "showSpinner"=> ( $monthCount-1 ) );
                                }

                            } else {
                                //for 1 month
                                $yearPay = $row['year'];
                                if ( $row['months'] == 12 ) {
                                    $row['months'] = 0;
                                    $yearPay++;
                                }
                                $dueRow[0] = array( "feeFor"=>"1 Month", "monthPay"=>strval( $row['months'] + 1 ), "yearPay"=>$yearPay, "amountPay"=>$monthlyFees, "lateFee"=>$lateFee, "discountedFee"=> 0, "showSpinner"=> ( $monthCount-1 ) );

                                //for 2 months
                                if ( $row['months'] == 11 ) {
                                    $dueRow[1] = array( "feeFor"=>"2 Months", "monthPay"=>( $row['months'] + 1 ).",".( 1 ), "yearPay"=>$yearPay, "amountPay"=>( ( $monthlyFees * 2 ) ), "lateFee"=>$lateFee, "discountedFee"=> 0, "showSpinner"=> ( $monthCount-1 ) );
                                } else {
                                    $dueRow[1] = array( "feeFor"=>"2 Months", "monthPay"=>( $row['months'] + 1 ).",".( $row['months'] + 2 ), "yearPay"=>$yearPay, "amountPay"=>( ( $monthlyFees * 2 ) ), "lateFee"=>$lateFee, "discountedFee"=> 0, "showSpinner"=> ( $monthCount-1 ) );
                                }

                                //for 3 months

                                if ( ( date( "d" ) <= 15 ) && ( ( $row['months']+1 ) == date( "m" ) ) ) {
                                    $discountedFee = 0;
                                } else if ( ( ( $row['months'] ) >= date( "m" ) ) && $yearPay == date( "Y" ) ) {
                                    $discountedFee = 0;
                                } else {
                                    $discountedFee = 0;
                                }
                                if ( $row['months'] == 10 ) {
                                    $dueRow[2] = array( "feeFor"=>"3 Months", "monthPay"=>( $row['months'] + 1 ).",".( $row['months'] + 2 ).",".( 1 ), "yearPay"=>$yearPay, "amountPay"=>( $monthlyFees * 3 ), "lateFee"=>$lateFee, "discountedFee"=> $discountedFee, "showSpinner"=> ( $monthCount-1 ) );
                                } else if ( $row['months'] == 11 ) {
                                    $dueRow[2] = array( "feeFor"=>"3 Months", "monthPay"=>( $row['months'] + 1 ).",".( 1 ).",".( 2 ), "yearPay"=>$yearPay, "amountPay"=>( $monthlyFees * 3 ), "lateFee"=>$lateFee, "discountedFee"=> $discountedFee, "showSpinner"=> ( $monthCount-1 ) );
                                } else {
                                    $dueRow[2] = array( "feeFor"=>"3 Months", "monthPay"=>( $row['months'] + 1 ).",".( $row['months'] + 2 ).",".( $row['months'] + 3 ), "yearPay"=>$yearPay, "amountPay"=>( $monthlyFees * 3 ), "lateFee"=>$lateFee, "discountedFee"=> $discountedFee, "showSpinner"=> ( $monthCount-1 ) );
                                }
                            }

                            $row['months'] = $temp;

                            $query = "SELECT * FROM fastrack WHERE student_id = ".$id;
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->execute();
                            $monthAmount = $query_stmt->fetch( PDO::FETCH_ASSOC );
                            $monthlyFees = $monthAmount['fees'];
                            $lateFees = $monthAmount['late'];
                            // $discountFees = $monthAmount['discount'];
                            $discountFees = "0";

                            $returnData = [
                                'success' => 1,
                                'data' => $row,
                                'due' => $dueRow
                            ];

                        }

                    }
                    // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                    else {
                        $returnData = msg( 1, 422, 'No Fees Found!' );
                    }

                } catch( PDOException $e ) {
                    $returnData = msg( 1, 500, $e->getMessage() );
                }

            }

        }
    } else {
        $returnData = msg( 1, 401, 'Unauthorized!' );
    }
    header( 'Content-Type: application/json' );
    echo json_encode( $returnData );
