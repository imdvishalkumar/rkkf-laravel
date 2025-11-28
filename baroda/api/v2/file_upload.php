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
require __DIR__.'/classes/JwtHandler.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$data = json_decode( file_get_contents( "php://input" ) );
$returnData = [];

// IF REQUEST METHOD IS NOT EQUAL TO POST
if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
    $returnData = msg( 0, 404, 'Page Not Found!' );
}
// IF THERE ARE NO EMPTY FIELDS THEN-
else {

    try {
        $filename = $_FILES["image"]["name"];

        $tempname = $_FILES["image"]["tmp_name"];

        $folder = "../
        images/".$filename;

        // Now let's move the uploaded image into the folder: image 
        if (move_uploaded_file($tempname, $folder))  { 
            $msg = "Image uploaded successfully"; 
        }else{ 
            $msg = "Failed to upload image";
        }
        echo $msg;
            
            
        }
        catch(PDOException $e){
            $returnData = msg(0,500,$e->getMessage());
        }

    

}

header('Content-Type: application/json' );
        echo json_encode( $returnData );