<?php
if ( $_POST ) {
    $data = array( "deleted"=>false );
    include_once( "connection.php" );
    if ( isset( $_POST['fee_id'] ) ) {
        $id=$_POST['fee_id'];
        
        $query = "DELETE FROM `exam_fees` WHERE `exam_fees`.`exam_fees_id` = $id AND `exam_fees`.`mode` != 'razorpay'";

        $result = mysqli_query($con,$query);
        if($result){
            $data = array( "deleted"=>true );
        } else {
            $data = array( "deleted"=>false );
        }
    }
    echo json_encode($data);
}
?>
