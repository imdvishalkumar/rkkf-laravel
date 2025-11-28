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

    require __DIR__.'/classes/Database.php';
    require __DIR__.'/middlewares/Auth.php';

    $allHeaders = getallheaders();
    $db_connection = new Database();
    $conn = $db_connection->dbConnection();
    $auth = new Auth( $conn, $allHeaders );

    if ( $auth->isAuth() ) {

        $id = $_GET['id'];

        $returnData = [];

        // IF REQUEST METHOD IS NOT EQUAL TO POST
        if ( $_SERVER["REQUEST_METHOD"] != "GET" ) {
            $returnData = msg( 1, 404, 'Page Not Found!' );
        }

        // CHECKING EMPTY FIELDS
        elseif (
            !isset( $id )
            || empty( trim( $id ) )
        ) {

            $returnData = msg( 0, 422, 'Please Fill in all Required Fields!' );
        }
        // IF THERE ARE NO EMPTY FIELDS THEN-
        else {
            try {

                //$query = "select * from students where student_id='".$id."'";
                $query = "SELECT o.*, p.name FROM orders o , products p WHERE o.student_id= '".$id."' AND p.product_id = o.product_id ORDER BY o.order_id DESC";
                $query_stmt = $conn->prepare( $query );
                if ( $query_stmt->execute() ) {
                    $totalRows = $query_stmt->rowCount();
                    $i = 0;
                    $j = 0;
                    $counter = "";
                    $row = $query_stmt->fetchAll( \PDO::FETCH_ASSOC );
                    $newRow = [];
                    $newRowCount = -1;
                    while( $i < $totalRows ) {
                        if ( $row[$i]['counter'] == $counter ) {
                            
                            $newArray['product_id'] = $row[$i]['product_id'];
                            $newArray['name'] = $row[$i]['name_var'];
                            $newArray['qty'] = $row[$i]['qty'];
                            $newArray['price'] = $row[$i]['p_price'];
                            $newArray['total'] = ( ( $row[$i]['p_price'] )*( $row[$i]['qty'] ) );
                            
                            //$newRow[$newRowCount]['details'] = $newArray;
                            
                            
                            array_push($newRow[$newRowCount]['details'],$newArray);

                            $newRow[$newRowCount]['total'] += $newArray['total'];
                           
                        } else {
                            $newArray = [];
                            $newRowCount++;
                            $counter = $row[$i]['counter'];
                            $newArray['product_id'] = $row[$i]['product_id'];
                            $newArray['name'] = $row[$i]['name_var'];
                            $newArray['qty'] = $row[$i]['qty'];
                            $newArray['price'] = $row[$i]['p_price'];
                            $newArray['total'] = ( ( $row[$i]['p_price'] )*( $row[$i]['qty'] ) );

                            $newRow[$newRowCount]['counter'] = $row[$i]['counter'];
                            $newRow[$newRowCount]['date'] = $row[$i]['date'];
                            $newRow[$newRowCount]['flag_delivered'] = $row[$i]['flag_delivered'];
                            $newRow[$newRowCount]['status'] = $row[$i]['status'];
                            $newRow[$newRowCount]['total'] = $newArray['total'];
                            $newRow[$newRowCount]['details'] = [];
                            
                            array_push($newRow[$newRowCount]['details'],$newArray);

                        }
                        $i++;
                    }
                    $arr2 = array_values( $newRow );

                    $returnData = [
                        'success' => 1,
                        'data' => $arr2
                    ];
                }

                if ( $query_stmt->rowCount() ) {
                    $row = $query_stmt->fetchAll( PDO::FETCH_ASSOC );

                }
                // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                else {
                    $returnData = msg( 1, 200, 'No orders found!' );
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