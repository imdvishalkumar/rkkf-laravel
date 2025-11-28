<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: GET" );
header( "Content-Type: application/json; charset=UTF-8" );
header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );

function msg( $success, $status, $message, $extra = [] ) {
    return array_merge( [
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra );
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

require __DIR__.'/classes/Database.php';
require __DIR__.'/middlewares/Auth.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$baseUrl = $db_connection->getBaseURl();
$auth = new Auth( $conn, $allHeaders );

if ( $auth->isAuth() ) {

    $page = $_GET['page'];
    $pageSize = $_GET['page_size'];
    $studentId = $_GET['student_id'];

    $returnData = [];

    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ( $_SERVER["REQUEST_METHOD"] != "GET" ) {
        $returnData = msg( 1, 404, 'Page Not Found!' );
    }

    // CHECKING EMPTY FIELDS
    elseif (
        !isset( $page )
        || empty( trim( $page ) ) 
        || !isset( $pageSize )
        || empty( trim( $pageSize ) ) 
        || !isset( $studentId )
        || empty( trim( $studentId ) ) 
    ) {

        $returnData = msg( 0, 422, 'Please Fill in all Required Fields!' );
    }
    // IF THERE ARE NO EMPTY FIELDS THEN-
    else {
        try {
            $page--;
            $page = $page * $pageSize;
            if ($page == 0) {
                $query = "SELECT COUNT(*) as count, (DATE_FORMAT(current_date, '%d-%m-%Y')) as `current_date`, (DATE_FORMAT(Date(NOW() - INTERVAL 1 MONTH), '%d-%m-%Y')) as last_month_date FROM `attendance` WHERE student_id = ".$studentId."  AND attend = 'P' AND date < CURRENT_DATE AND date > NOW() - INTERVAL 1 MONTH;";
                $query_stmt = $conn->prepare( $query );
                $query_stmt->execute();
                $attendance_row = $query_stmt->fetch( \PDO::FETCH_ASSOC );
            }
            
            $query = "SELECT id, title, description, created FROM post WHERE is_deleted = 0 ORDER BY id DESC LIMIT ".$pageSize." OFFSET ".$page.";";
            $query_stmt = $conn->prepare( $query );
            if ( $query_stmt->execute() ) {
                $totalRows = $query_stmt->rowCount();
                $i = 0;
                $row = $query_stmt->fetchAll( \PDO::FETCH_ASSOC );
                while( $i < $totalRows ) {
                    $post_id = $row[$i]['id'];
                    $created = $row[$i]['created'];
                    $row[$i]['created'] = time_elapsed_string($created);
                    $query_media = "SELECT path, type FROM media WHERE post_id = ".$post_id.";";
                    $query_stmt_media = $conn->prepare( $query_media );
                    if ( $query_stmt_media->execute() ) {
                        $totalRows_media = $query_stmt_media->rowCount();
                        if ( $totalRows_media > 0 ) {
                            $row_media = $query_stmt_media->fetchAll( \PDO::FETCH_ASSOC );
                            $j = 0;
                            while( $j < $totalRows_media ) {
                                $path = $row_media[$j]['path'];
                                $row_media[$j]['path'] = $baseUrl."images/feed/".$path;
                                $j++;
                            }
                            $post_id = $row[$i]['media'] = $row_media;
                        } else {
                            $post_id = $row[$i]['media'] = array();
                        }
                    }

                    $i++;
                }
                if ($page == 0) {
                    $returnData = [
                        'success' => 1,
                        'data' => $row,
                        'attendance_data' => $attendance_row
                    ];
                } else {
                    $returnData = [
                        'success' => 1,
                        'data' => $row
                    ];
                }
            }

            if ( $query_stmt->rowCount() ) {
                $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );

            }
            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else {
                $returnData = msg( 1, 200, 'No post found!' );
            }
        } catch( PDOException $e ) {
            $returnData = msg( 1, 500, $e->getMessage() );
        }

    }
} else {
    $returnData = msg( 1, 401, 'Unauthorized!' );
}
header( 'Content-Type: application/json' );
echo json_encode( $returnData );