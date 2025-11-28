<?php

require ( '../api/v1/classes/Database.php' );

$data = array( "order_created"=>false );

if ( isset( $_POST['enquire'] ) ) {
    if (
        !isset( $_POST['firstname'] )
        || empty( trim( $_POST['firstname'] ) )
        || !isset( $_POST['lastname'] )
        || empty( trim( $_POST['lastname'] ) )
        || !isset( $_POST['gender'] )
        || empty( trim( $_POST['gender'] ) )
        || !isset( $_POST['email'] )
        || empty( trim( $_POST['email'] ) )
        || !isset( $_POST['dob'] )
        || empty( trim( $_POST['dob'] ) )
        || !isset( $_POST['dadno'] )
        || empty( trim( $_POST['dadno'] ) )
        || !isset( $_POST['dadwp'] )
        || empty( trim( $_POST['dadwp'] ) )
        || !isset( $_POST['momno'] )
        || empty( trim( $_POST['momno'] ) )
        || !isset( $_POST['momwp'] )
        || empty( trim( $_POST['momwp'] ) )
        || !isset( $_POST['selfno'] )
        || empty( trim( $_POST['selfno'] ) )
        || !isset( $_POST['selfwp'] )
        || empty( trim( $_POST['selfwp'] ) )
        || !isset( $_POST['address'] )
        || empty( trim( $_POST['address'] ) )
        || !isset( $_POST['branch_id'] )
        || empty( trim( $_POST['branch_id'] ) )
        || !isset( $_POST['pincode'] )
        || empty( trim( $_POST['pincode'] ) )
    ) { 
        $data = array( "order_created"=>false , "missing_param" =>true );
    } else {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];
        $date = str_replace('/', '-', $dob);
        $dob = date('Y-m-d', strtotime($date));
        $dadno = $_POST['dadno'];
        $dadwp = $_POST['dadwp'];
        $momno = $_POST['momno'];
        $momwp = $_POST['momwp'];
        $selfno = $_POST['selfno'];
        $selfwp = $_POST['selfwp'];
        $address = $_POST['address'];
        $branch_id = $_POST['branch_id'];
        $pincode = $_POST['pincode'];
        
        $db_connection = new Database();
        $conn = $db_connection->dbConnection();
        
        $insert = "INSERT INTO `enquire` (`enquire_id`, `firstname`, `lastname`, `gender`, `email`, `dob`, `doj`, `dadno`, `dadwp`, `momno`, `momwp`, `selfno`, `selfwp`, `address`, `branch_id`, `pincode`, `order_id`, `amount`, `payment_id`, `payment_status`, `inserted_status`, `updated_at`, `created_at`, `direct_entry`) VALUES (NULL, :firstname, :lastname, :gender, :email, :dob, CURDATE(), :dadno, :dadwp, :momno, :momwp, :selfno, :selfwp, :address, :branch_id, :pincode, '0', '0', '', '0', '0', current_timestamp(), current_timestamp(), '1');";
        $query_stmt = $conn->prepare( $insert );
        $query_stmt->bindParam( ':firstname', $firstname );
        $query_stmt->bindParam( ':lastname', $lastname );
        $query_stmt->bindParam( ':gender', $gender );
        $query_stmt->bindParam( ':email', $email );
        $query_stmt->bindParam( ':dob', $dob );
        $query_stmt->bindParam( ':dadno', $dadno );
        $query_stmt->bindParam( ':dadwp', $dadwp );
        $query_stmt->bindParam( ':momno', $momno );
        $query_stmt->bindParam( ':momwp', $momwp );
        $query_stmt->bindParam( ':selfno', $selfno );
        $query_stmt->bindParam( ':selfwp', $selfwp );
        $query_stmt->bindParam( ':address', $address );
        $query_stmt->bindParam( ':branch_id', $branch_id );
        $query_stmt->bindParam( ':pincode', $pincode );
        $name = $firstname." ".$lastname;
        
        if ( $query_stmt->execute() ) {
            $data = array(
                "order_created"=>true
            );
        } else {
            $data = array( "order_created"=>false );
        }
    }

} else {
    $data = array( "order_created"=>false );
}
echo json_encode($data);