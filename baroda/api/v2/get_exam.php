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
        $returnData = msg( 0, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $id )
        || empty( trim( $id ) )
    ) {

        $returnData = msg( 0, 422, 'Please Fill in all Required Fields!' );
    }
    // IF THERE ARE NO EMPTY FIELDS THEN-
    else {
        $id = trim( $id );
        $role = trim( $role );

        // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
        if ( !filter_var( $id, FILTER_VALIDATE_INT ) ) {
            $returnData = msg( 0, 422, 'Invalid ID!' );
        }
        // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
        else {
            try {
                $paid = false;
                $error = false;
                $eligibleFee = false;
                $eligibleAtten = false;
                $dueDateGone = false;
                $query = "select e.*,s.* from exam e, special_case_exam s WHERE e.date >= CURDATE() AND e.isPublished = 1 AND e.exam_id = s.exam_id AND s.student_id = ".$id." AND s.eligible = 1 ORDER BY date DESC LIMIT 1;";

                $query_stmt = $conn->prepare( $query );
                $query_stmt->execute();
                // if exam exists
                if ( $query_stmt->rowCount() > 0 ) {

                    $examRow = $query_stmt->fetch( PDO::FETCH_ASSOC );

                    // exam fee query
                    $query = "SELECT * FROM `exam_fees` WHERE exam_id = '".$examRow['exam_id']."' AND student_id = ".$id." AND status =1";
                    $query_stmt = $conn->prepare( $query );
                    $query_stmt->execute();

                    //if exam fees check
                    if ( $query_stmt->rowCount() > 0 ) {
                        $paid = true;
                        $examRow['paid'] = $paid;
                    } else {
                        $paid = false;
                    }
                    if ( $paid == true ) {
                        $returnData = [
                            'success' => 1,
                            'error' => $error,
                            'data' => $examRow
                        ];
                    } else {
                        // attendance query

                        $query = "SELECT COUNT(attendance_id) as count FROM attendance WHERE attend = 'P' AND student_id = ".$id." AND date >= '".$examRow['from_criteria']."' AND date <= '".$examRow['to_criteria']."' ";
                        $query_stmt = $conn->prepare( $query );
                        $query_stmt->execute();

                        //if attendance check
                        if ( $query_stmt->rowCount() > 0 ) {
                            $attendRow = $query_stmt->fetch( PDO::FETCH_ASSOC );
                            if ( $attendRow['count'] >= $examRow['sessions_count'] ) {
                                $eligibleAtten = true;
                            } else {
                                $eligibleAtten = false;
                            }
                        } else {
                            $error = true;
                        }
                        // date diff query
                        $query = "SELECT TIMESTAMPDIFF(month, '".$examRow['from_criteria']."', '".$examRow['to_criteria']."') + 1 AS DateDiff;";
                        $query_stmt = $conn->prepare( $query );
                        $query_stmt->execute();
                        $diffInMonths = 0;
                        if ( $query_stmt->rowCount() > 0 ) {
                            $monthRow = $query_stmt->fetch( PDO::FETCH_ASSOC );
                            $diffInMonths = $monthRow['DateDiff'];
                        } else {
                            $error = true;
                        }

                        // fee query
                        $query = "SELECT COUNT(fee_id) as count FROM fees WHERE student_id = '".$id."' AND CAST(CONCAT(year,'-', months,'-01') as date) >= '".$examRow['from_criteria']."' AND CAST(CONCAT(year,'-', months,'-01') as date) <= '".$examRow['to_criteria']."'";
                        $query_stmt = $conn->prepare( $query );
                        $query_stmt->execute();

                        //if fees check
                        if ( $query_stmt->rowCount() > 0 ) {
                            $feeRow = $query_stmt->fetch( PDO::FETCH_ASSOC );
                            if ( $feeRow['count'] >= $diffInMonths ) {
                                $eligibleFee = true;
                            } else {
                                $eligibleFee = false;
                            }
                        } else {
                            $error = true;
                        }

                        $curdate = date( "Y-m-d" );
                        $curdate = strtotime( $curdate );
                        $mydate = strtotime( $examRow['fess_due_date'] );
                        if ( $curdate > $mydate ) {
                            $dueDateGone = true;
                        } else {
                            $dueDateGone = false;
                        }

                        if ( !( $eligibleAtten ) ) {
                            $exam_id = $examRow['exam_id'];
                            $query = "SELECT * FROM `special_case_exam` WHERE student_id = ".$id." AND exam_id = ".$exam_id." AND eligible = 1";
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->execute();
                            if ( $query_stmt->rowCount() > 0 ) {
                                $eligibleAtten = true;
                            } else {
                                $eligibleAtten = false;
                            }
                        }

                        $examRow['paid'] = $paid;
                        $examRow['eligibleAttendance'] = $eligibleAtten;
                        $examRow['eligibleFee'] = $eligibleFee;
                        $examRow['dueDateGone'] = $dueDateGone;

                        $query = "SELECT to_belt_id, from_belt_id ,(to_belt_id - from_belt_id) as belt_up FROM `fastrack` WHERE student_id = ".$id." AND from_date < CURDATE() AND to_date > CURDATE()";
                        $query_stmt = $conn->prepare( $query );
                        $query_stmt->execute();

                        if ( $query_stmt->rowCount() > 0 ) {
                            $fastrackRow = $query_stmt->fetch( PDO::FETCH_ASSOC );
                            $beltUp = $fastrackRow['belt_up'];
                            $fromBeltId = $fastrackRow['from_belt_id'];
                            $toBeltId = $fastrackRow['to_belt_id'];

                            $query = "SELECT * FROM belt WHERE belt_id > ".$fromBeltId." AND belt_id <= ".$toBeltId;
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->execute();
                            if ( $query_stmt->rowCount() > 0 ) {
                                $beltSpinnerRow = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
                            }
                        }

                        if ( isset( $beltSpinnerRow ) ) {

                            $total = count( $beltSpinnerRow );
                            $i = 1;
                            while( $i < $total ) {
                                $beltSpinnerRow[$i]['exam_fees'] += $beltSpinnerRow[$i - 1]['exam_fees'];
                                $i++;
                            }

                            $examRow['beltSpinnerRow'] = $beltSpinnerRow;
                            $returnData = [
                                'success' => 1,
                                'error' => $error,
                                'data' => $examRow
                            ];
                        } else {

                            $query = "SELECT belt_id, exam_fees FROM belt WHERE belt_id = ((SELECT belt_id FROM students WHERE student_id = ".$id.") + 1)";
                            $query_stmt = $conn->prepare( $query );
                            $query_stmt->execute();

                            if ( $query_stmt->rowCount() > 0 ) {
                                $examFeeRow = $query_stmt->fetch( PDO::FETCH_ASSOC );
                                $examFee = $examFeeRow['exam_fees'];
                                $beltId = $examFeeRow['belt_id'];
                            }

                            if ( isset( $examFee ) ) {
                                $examRow['fees'] = $examFee;
                                $examRow['belt_id'] = $beltId;
                            } else {
                                $examRow['fees'] = "0";
                                $examRow['belt_id'] = "0";
                            }

                            $returnData = [
                                'success' => 1,
                                'error' => $error,
                                'data' => [$examRow]
                            ];
                        }
                    }

                }
                // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                else {
                    $returnData = msg( 1, 422, 'No Exam Found!' );
                }
            } catch( PDOException $e ) {
                $returnData = msg( 0, 500, $e->getMessage() );
            }

        }

    }
} else {
    $returnData = msg( 0, 401, 'Unauthorized!' );
}
header( 'Content-Type: application/json' );
echo json_encode( $returnData );
