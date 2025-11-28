<?php
if ( $_POST ) {
    require ( '../api/v1/classes/Database.php' );
    if ( isset( $_POST['branch_id'] ) ) {
        $branchId = $_POST['branch_id'];
        $db_connection = new Database();
        $conn = $db_connection->dbConnection();
        $query = "SELECT fees FROM branch WHERE branch_id = :branch_id;";
        $query_stmt = $conn->prepare( $query );
        $query_stmt->bindParam( ':branch_id', $branchId );
        $query_stmt->execute();
        if ( $query_stmt->rowCount() > 0 ) {
            $monthAmount = $query_stmt->fetch( PDO::FETCH_ASSOC );
            //$monthlyFees = $monthAmount['fees'];
            $monthlyFees = 1000;
            echo "<label class='label'> ( Admission Fees: ₹ 300 + Branch Fees: ₹ ".$monthlyFees." = ₹ ".( $monthlyFees+300 ).") </label>";
        }

    }
}
?>
