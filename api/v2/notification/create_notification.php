<?php

require ( '../classes/Database.php' );
require ( '../middlewares/Auth.php' );

$db_connection = new Database();
$conn = $db_connection->dbConnection();

function sendMessage( $message, $type, $array) {
    $content = array(
        "en" => $message
    );

    $fields = array(
        'app_id' => "edbfda7f-4c81-4ac1-b25f-7fd20d08701e",
        'include_player_ids' => $array,
        'data' => array( "type" => $type ),
        'contents' => $content
    );

    $fields = json_encode( $fields );
    print( "\nJSON sent:\n" );
    print( $fields );

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications" );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json; charset=utf-8' ) );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
    curl_setopt( $ch, CURLOPT_HEADER, FALSE );
    curl_setopt( $ch, CURLOPT_POST, TRUE );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

    $response = curl_exec( $ch );
    curl_close( $ch );
    
    return $response;
}
$query = "SELECT * FROM devices where is_active = 1";
$query_stmt = $conn->prepare( $query );
$query_stmt->execute();
$playerIdArray = array( ); 

if ( $query_stmt->rowCount() ) {
    $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );
    $totalRows = $query_stmt->rowCount();
    $i = 0;
    while( $i < $totalRows ) {
        if ( $row[$i]['player_id'] != NULL ) {
            array_push($playerIdArray, $row[$i]['player_id']);
        }
        $i++;
    }
}

$response = sendMessage( "Notification Testing.", "exam", $playerIdArray);
$return["allresponses"] = $response;
$return = json_encode( $return );

print( "\n\nJSON received:\n" );
print( $return );
print( "\n" );
?>