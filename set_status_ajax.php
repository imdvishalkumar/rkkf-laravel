<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ( $_POST ) {
    include_once( "connection.php" );
    if ( isset( $_POST['stuId'] ) && $_POST['stuId'] != '' ) {
        $stuId=$_POST['stuId'];
        if(isset($_POST['from']) && $_POST['from'] == 1){
            $query = "UPDATE `students` SET `call_flag` = 1 WHERE `student_id` = $stuId";
            $result = mysqli_query($con,$query);  
        }
    }
    if ( isset( $_POST['feeId'] ) &&  $_POST['feeId'] != '' ) {
        $feeId=$_POST['feeId'];
        if(isset($_POST['from']) && $_POST['from'] == 2){
            $query = "UPDATE `fees` SET `call_flag` = 1 WHERE `fee_id` = $feeId";
            $result = mysqli_query($con,$query);  
        }
    }

   // return json_encode(['status' => 'success']);
}

?>