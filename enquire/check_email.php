<?php

require ( '../api/v1/classes/Database.php' );

if ( isset( $_POST['email'] ) ) {
    $email = $_POST['email'];
    
    $db_connection = new Database();
    $conn = $db_connection->dbConnection();
    
    $query = "(SELECT email FROM `users` WHERE `email`= :email) UNION (SELECT email FROM students WHERE email= :email AND active = 1 )";
    $query_stmt = $conn->prepare( $query );
    $query_stmt->bindValue(':email', $email);
    $query_stmt->execute();
    if($query_stmt->rowCount()){
        echo "false";
    } else {
        echo "true";
    }
}

?>