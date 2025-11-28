<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST" );
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

    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $_POST['attendanceArray'] )
        || empty( trim( $_POST['attendanceArray'] ) )
        || !isset( $_POST['exam_id'] )
        || empty( trim( $_POST['exam_id'] ) )
    ) {
        $returnData = msg( 1, 200, 'Please Fill in all Required Fields!' );
    } else {
        $attenArray = trim( $_POST['attendanceArray'] );
        $examId = trim( $_POST['exam_id'] );
        $userId = trim( $_POST['user_id'] );

        try {

            $queryCheckUser = "select role from users WHERE  user_id = $userId AND role = 2";
            $query_stmtUser = $conn->prepare( $queryCheckUser );
            $query_stmtUser->execute();

            if ( $query_stmtUser->rowCount() > 0 ) {

            $presentArr = [];

            $absentArr = [];

            $leaveArr = [];

            $decodedArray = json_decode( $attenArray, true );

            foreach ( $decodedArray as $value ) {
                if ( isset( $value['present_student_id'] ) ) {
                    array_push( $presentArr, $value['present_student_id'] );
                }
                if ( isset( $value['absent_student_id'] ) ) {
                    array_push( $absentArr, $value['absent_student_id'] );
                }
                if ( isset( $value['leave_student_id'] ) ) {
                    array_push( $leaveArr, $value['leave_student_id'] );
                }
            }

            $queryCheck = "select exam_id from exam_attendance WHERE exam_id = $examId";
            $query_stmt = $conn->prepare( $queryCheck );
            $query_stmt->execute();
            if ( $query_stmt->rowCount() == 0 ) {
                                
                $success_var = false;
                
                foreach ( $presentArr as $presentId ) {
                    
                    $queryExam = "SELECT b.code, e.date, ef.exam_belt_id FROM belt b, exam e, students s, exam_fees ef WHERE ef.exam_belt_id = b.belt_id AND e.exam_id = ef.exam_id AND ef.student_id = s.student_id AND s.student_id = ".$presentId." AND ef.exam_id = ".$examId." AND ef.status = 1";
                    $queryExam_stmt = $conn->prepare( $queryExam );
                    $queryExam_stmt->execute();
                    $row1 = $queryExam_stmt->fetch( PDO::FETCH_ASSOC );
                    $code = $row1['code'];
                    $date = $row1['date'];
                    $date = str_replace('-', '', $date);
                    $cat = $row1['exam_belt_id'];
                    //$certificate_no = "EXAMDATE|BELTCODE|GRNO|CATEGORY";
                    $certificate_no = $date.$code.$presentId.$cat;
                    $update = "UPDATE students SET belt_id = ".$cat." WHERE student_id = ".$presentId.";";
                    $query_stmt = $conn->prepare( $update );
                    $query_stmt->execute();
                    $insert = "INSERT INTO `exam_attendance` (`exam_attendance_id`, `exam_id`, `student_id`, `attend`, `user_id`, `certificate_no`) VALUES ('', $examId, '".$presentId."',  'P', '".$userId."', '".$certificate_no."');";
                    $query_stmt = $conn->prepare( $insert );
                    if ( $query_stmt->execute() ) {
                        $success_var = true;
                    } else {
                        $success_var = false;
                    }
                }
                foreach ( $absentArr as $absentId ) {
                    $queryExam = "SELECT b.code, e.date, ef.exam_belt_id FROM belt b, exam e, students s, exam_fees ef WHERE ef.exam_belt_id = b.belt_id AND e.exam_id = ef.exam_id AND ef.student_id = s.student_id AND s.student_id = ".$presentId." AND ef.exam_id = ".$examId." AND ef.status = 1";
                    $queryExam_stmt = $conn->prepare( $queryExam );
                    $queryExam_stmt->execute();
                    $row1 = $queryExam_stmt->fetch( PDO::FETCH_ASSOC );
                    $code = $row1['code'];
                    $date = $row1['date'];
                    $date = str_replace('-', '', $date);
                    $cat = $row1['exam_belt_id'];
                    //$certificate_no = "EXAMDATE|BELTCODE|GRNO|CATEGORY";
                    $certificate_no = $date.$code.$presentId.$cat;
                    $update = "UPDATE students SET belt_id = ".$cat." WHERE student_id = ".$presentId.";";
                    $query_stmt = $conn->prepare( $update );
                    $query_stmt->execute();
                    $insert = "INSERT INTO `exam_attendance` (`exam_attendance_id`, `exam_id`, `student_id`, `attend`, `user_id`, `certificate_no`) VALUES ('', $examId, '".$absentId."',  'A', '".$userId."', '".$certificate_no."');";
                    $query_stmt = $conn->prepare( $insert );
                    if ( $query_stmt->execute() ) {
                        $success_var = true;
                    } else {
                        $success_var = false;
                    }
                }
                foreach ( $leaveArr as $leaveId ) {
                    $queryExam = "SELECT b.code, e.date, ef.exam_belt_id FROM belt b, exam e, students s, exam_fees ef WHERE ef.exam_belt_id = b.belt_id AND e.exam_id = ef.exam_id AND ef.student_id = s.student_id AND s.student_id = ".$presentId." AND ef.exam_id = ".$examId." AND ef.status = 1";
                    $queryExam_stmt = $conn->prepare( $queryExam );
                    $queryExam_stmt->execute();
                    $row1 = $queryExam_stmt->fetch( PDO::FETCH_ASSOC );
                    $code = $row1['code'];
                    $date = $row1['date'];
                    $date = str_replace('-', '', $date);
                    $cat = $row1['exam_belt_id'];
                    //$certificate_no = "EXAMDATE|BELTCODE|GRNO|CATEGORY";
                    $certificate_no = $date.$code.$presentId.$cat;
                    $update = "UPDATE students SET belt_id = ".$cat." WHERE student_id = ".$presentId.";";
                    $query_stmt = $conn->prepare( $update );
                    $query_stmt->execute();
                    $insert = "INSERT INTO `exam_attendance` (`exam_attendance_id`, `exam_id`, `student_id`, `attend`, `user_id`, `certificate_no`) VALUES ('', $examId, '".$leaveId."',  'F', '".$userId."', '".$certificate_no."');";
                    $query_stmt = $conn->prepare( $insert );
                    if ( $query_stmt->execute() ) {
                        $success_var = true;
                    } else {
                        $success_var = false;
                    }
                }
                if ( $success_var ) {
                    $returnData = [
                        'success' => 1,
                        'saved' => 1,
                        'message' => 'Attendance Submited.'
                    ];
                } else {
                    $returnData = msg( 1, 422, 'Unable to submit leave try again!' );
                }
            } else {
                $returnData = msg( 1, 200, 'Attendance already exists!' );

            }}
        } catch( PDOException $e ) {
            $returnData = msg( 1, 500, $e->getMessage() );
        }

    }

} else {
    $returnData = msg( 1, 401, 'Unauthorized!' );
}

header( 'Content-Type: application/json' );
echo json_encode( $returnData );