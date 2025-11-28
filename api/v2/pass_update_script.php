<?php
require __DIR__.'/classes/Database.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$query = "select selfno,student_id from students WHERE student_id > 440";
$query_stmt = $conn->prepare( $query );
$query_stmt->execute();
$row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
$count = $query_stmt->rowCount();
$i = 0;
while( $i < $count ) {

    $pwd = password_hash( $row[$i]['selfno'], PASSWORD_DEFAULT );
    $query1 = "update students set password = '".$pwd."' WHERE student_id = ".$row[$i]['student_id'];
    $query_stmt = $conn->prepare( $query1 );
    $query_stmt->execute();
    $i++;
}

?>