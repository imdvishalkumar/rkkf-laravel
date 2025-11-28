<?php

require ( '../classes/Database.php' );
require ( '../middlewares/Auth.php' );
require ( 'send_to_array.php' );

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$query = "SELECT n.id, n.details, n.type, n.timestamp, d.player_id FROM notification n , devices d WHERE n.sent = 0 AND n.student_id = d.student_id AND d.is_active = 1";

$query_stmt = $conn->prepare( $query );
$query_stmt->execute();
$playerIdArray = array();

if ( $query_stmt->rowCount() > 0 ) {
    $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
    $totalRows = $query_stmt->rowCount();
    $i = 0;
    $timestamp = "";
    $details = "";
    $type = "";
    $timestamp = $row[$i]['timestamp'];
    $details = $row[$i]['details'];
    $type = $row[$i]['type'];
    while( $i < $totalRows ) {
        if ( $row[$i]['timestamp'] == $timestamp ) {
            if ( $row[$i]['player_id'] != NULL ) {
                array_push( $playerIdArray, $row[$i]['player_id'] );
            }
        } else {
            break;
        }
        $i++;
    }
    sendNoti( $details, $type, $playerIdArray );
    echo "Notification function called.";
    $query = "UPDATE notification SET sent = 1 WHERE timestamp = '".$timestamp."'";
    $query_stmt = $conn->prepare( $query );
    $query_stmt->execute();

} else {
    echo "No pending notification left.";
}
?>