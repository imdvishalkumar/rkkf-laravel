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
require __DIR__.'/middlewares/Auth.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$auth = new Auth( $conn, $allHeaders );

if ( $auth->isAuth() ) {

    $data = json_decode( file_get_contents( "php://input" ) );
    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $_POST['id'] )
        || empty( trim( $_POST['id'] ) )
        || !isset( $_POST['fname'] )
        || empty( trim( $_POST['fname'] ) )
        || !isset( $_POST['lname'] )
        || empty( trim( $_POST['lname'] ) )
        || !isset( $_POST['dmno'] )
        || empty( trim( $_POST['dmno'] ) )
        || !isset( $_POST['dwno'] )
        || empty( trim( $_POST['dwno'] ) )
        || !isset( $_POST['mmno'] )
        || empty( trim( $_POST['mmno'] ) )
        || !isset( $_POST['mwno'] )
        || empty( trim( $_POST['mwno'] ) )
        || !isset( $_POST['smno'] )
        || empty( trim( $_POST['smno'] ) )
        || !isset( $_POST['swno'] )
        || empty( trim( $_POST['swno'] ) )
        || !isset( $_POST['address'] )
        || empty( trim( $_POST['address'] ) )
        || !isset( $_POST['pincode'] )
        || empty( trim( $_POST['pincode'] ) )
    ) {

        $returnData = msg( 1, 422, 'Please Fill in all Required Fields!' );
    }
    // IF THERE ARE NO EMPTY FIELDS THEN-
    else {
        define( 'UPLOAD_PATH', '../../images/profile/' );

        $id = trim( $_POST['id'] );

        $fname = trim( $_POST['fname'] );

        $lname = trim( $_POST['lname'] );

        $dmno = trim( $_POST['dmno'] );

        $dwno = trim( $_POST['dwno'] );

        $mmno = trim( $_POST['mmno'] );

        $mwno = trim( $_POST['mwno'] );

        $smno = trim( $_POST['smno'] );

        $swno = trim( $_POST['swno'] );

        $address = trim( $_POST['address'] );

        $pincode = trim( $_POST['pincode'] );

        // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
        if ( !filter_var( $id, FILTER_VALIDATE_INT ) ) {
            $returnData = msg( 1, 422, 'Invalid ID!' );
        }
        // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
        else {
            try {
                if ( !isset( $_FILES['profile_pic']['name'] ) || empty( trim( $_FILES['profile_pic']['name'] ) ) ) {
                    $update = "update students set firstname='".$fname."', lastname='".$lname."', dadno='".$dmno."', dadwp='".$dwno."', momno='".$mmno."', momwp='".$mwno."', selfno='".$smno."', selfwp='".$swno."', address='".$address."', pincode='".$pincode."' where student_id='".$id."'";
                } else {
                    move_uploaded_file( $_FILES['profile_pic']['tmp_name'], UPLOAD_PATH . $_FILES['profile_pic']['name'] );
                    $uploadPath = $_FILES['profile_pic']['name'];
                    
                    $select = "select profile_img from students where student_id='".$id."'";
                    $query_stmt = $conn->prepare( $select );
                    $query_stmt->execute();
                    $savedRow = $query_stmt->fetch( PDO::FETCH_ASSOC );
                    $fileDel = UPLOAD_PATH . $savedRow['profile_img'];
                    unlink($fileDel);

                    
                    $update = "update students set firstname='".$fname."', lastname='".$lname."', dadno='".$dmno."', dadwp='".$dwno."', momno='".$mmno."', momwp='".$mwno."', selfno='".$smno."', selfwp='".$swno."', address='".$address."', pincode='".$pincode."', profile_img='".$uploadPath."' where student_id='".$id."'";
                }

                $query_stmt = $conn->prepare( $update );
                // $savedRow['profile_img']

                // IF THE USER IS FOUNDED BY EMAIL
                if ( $query_stmt->execute() ) {
                    $returnData = [
                        'success' => 1,
                        'saved' => 1,
                        'message' => 'Profile Updated.'
                    ];
                }
                // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                else {
                    $returnData = msg( 1, 422, 'Invalid Email Address!' );
                }
            } catch( PDOException $e ) {
                $returnData = msg( 1, 500, $e->getMessage() );
            }
        }
    }

} else {
    $returnData = msg( 1, 401, 'Unauthorized!' );
}

header( 'Content-Type: application/json' );
echo json_encode( $returnData );