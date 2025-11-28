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
        || !isset( $_POST['branch_id'] )
        || empty( trim( $_POST['branch_id'] ) )
    ) {
        $returnData = msg( 1, 200, 'Please Fill in all Required Fields!' );
    } else {
        $attenArray = trim( $_POST['attendanceArray'] );
        $branchId = trim( $_POST['branch_id'] );
        $userId = trim( $_POST['user_id'] );

        try {
            $queryCheckUser = "select role from users WHERE  user_id = $userId AND role = 2";
            $query_stmtUser = $conn->prepare( $queryCheckUser );
            $query_stmtUser->execute();

            if ( $query_stmtUser->rowCount() > 0 ) {

            // echo $attenArray;

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

            $queryCheck = "select date from attendance WHERE date = CURDATE() AND is_additional != 1 AND branch_id = $branchId";
            $query_stmt = $conn->prepare( $queryCheck );
            $query_stmt->execute();
            if ( $query_stmt->rowCount() == 0 ) {
                $success_var = false;
                foreach ( $presentArr as $presentId ) {
                    $insert = "INSERT INTO `attendance` (`attendance_id`, `student_id`, `date`, `attend`, `branch_id`, `user_id`) VALUES ('', '".$presentId."', CURDATE(), 'P', '".$branchId."', '".$userId."');";
                    //echo $insert;
                    $query_stmt = $conn->prepare( $insert );
                    if ( $query_stmt->execute() ) {
                        $success_var = true;
                    } else {
                        $success_var = false;
                    }
                }
                foreach ( $absentArr as $absentId ) {
                    $insert = "INSERT INTO `attendance` (`attendance_id`, `student_id`, `date`, `attend`, `branch_id`, `user_id`) VALUES ('', '".$absentId."', CURDATE(), 'A', '".$branchId."', '".$userId."');";
                    $query_stmt = $conn->prepare( $insert );
                    if ( $query_stmt->execute() ) { 
                        $success_var = true;
                    } else {
                        $success_var = false;
                    }
                }
                foreach ( $leaveArr as $leaveId ) {
                    $insert = "INSERT INTO `attendance` (`attendance_id`, `student_id`, `date`, `attend`, `branch_id`, `user_id`, `is_additional`) VALUES ('', '".$leaveId."', CURDATE(), 'L', '".$branchId."', '".$userId."',0);";
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
                    $returnData = msg( 1, 422, 'Unable to submit Attendance try again!' );
                }
            } else {
                $returnData = msg( 1, 200, 'Attendance already exists!' );

            }
        }
        } catch( PDOException $e ) {
            $returnData = msg( 1, 500, $e->getMessage() );
        }

    }

} else {
    $returnData = msg( 1, 401, 'Unauthorized!' );
}

header( 'Content-Type: application/json' );
echo json_encode( $returnData );