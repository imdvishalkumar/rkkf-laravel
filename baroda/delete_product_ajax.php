<?php
if ( $_POST ) {
    $data = array( "deleted"=>false );
    include_once( "connection.php" );
    if ( isset( $_POST['product_id'] ) ) {
        $id=$_POST['product_id'];
        $query = "delete from products WHERE product_id=$id";

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
