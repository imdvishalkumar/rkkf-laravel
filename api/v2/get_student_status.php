<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function msg($success, $status, $message, $extra = [])
{
    return array_merge(['success' => $success, 'status' => $status, 'message' => $message], $extra);
}

require __DIR__ . '/classes/Database.php';
require __DIR__ . '/middlewares/Auth.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$baseUrl = $db_connection->getBaseURl();
$auth = new Auth($conn, $allHeaders);

// if ($auth->isAuth())
if (true)
{
    $id = $_GET['id'];
    $returnData = [];
    // IF REQUEST METHOD IS NOT EQUAL TO POST
    if ($_SERVER["REQUEST_METHOD"] != "GET")
    {
        $returnData = msg(0, 404, 'Page Not Found!');
    }
    // CHECKING EMPTY FIELDS
    elseif (!isset($id) || empty(trim($id)))
    {
        $returnData = msg(0, 422, 'Please Fill in all Required Fields!');
    }
    // IF THERE ARE NO EMPTY FIELDS THEN-
    else
    {
        $id = trim($id);
        // $role = trim($role);
        // CHECKING THE EMAIL FORMAT ( IF INVALID FORMAT )
        if (!filter_var($id, FILTER_VALIDATE_INT))
        {
            $returnData = msg(0, 422, 'Invalid ID!');
        }
        // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
        else
        {
            $maintenance = false;
            $message = "App is under maintenance kindly check back after some time.";
            $maintenanceData = ['maintenance' => $maintenance, 'message' => $message];
            try
            {
                $query = "SELECT s.student_id, s.active, IFNULL(DATE_FORMAT((SELECT DATE_FORMAT(CONCAT(year,'-',months,'-01'), '%Y-%m-%d') as mdate FROM fees WHERE student_id = s.student_id ORDER BY mdate DESC LIMIT 1), '%Y-%m-%d'),'1000-01-01') as last_fees_paid, date_sub(DATE_FORMAT(CONCAT(YEAR(now()),'-',MONTH(now()),'-01'), '%Y-%m-%d'), interval 1 month) as must_fees, DATE_FORMAT(CONCAT(YEAR(now()),'-',MONTH(now()),'-01'), '%Y-%m-%d') as current_month, current_date FROM students s WHERE s.student_id = :student_id;";
                $query_stmt = $conn->prepare($query);
                $query_stmt->bindParam(':student_id', $id);
                $query_stmt->execute();
                if ($query_stmt->rowCount() > 0)
                {
                    $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                    $showpopup = false;
                    $type = "discount";
                    $active = $row['active'];
                    $lastFeesPaid = $row['last_fees_paid'];
                    $currentMonth = $row['current_month'];
                    $mustFees = $row['must_fees'];
                    $currentDate = $row['current_date'];

                    if ($row['active'] == 1)
                    {
                        if ($lastFeesPaid >= $currentMonth)
                        {
                            $showpopup = false;
                            $type = "active";
                        }
                        else if ($lastFeesPaid == $mustFees)
                        {
                            $time = strtotime($currentDate);
                            $idate = date("d", $time);
                            if ($idate <= 15)
                            {
                                $showpopup = true;
                                $type = "discount";
                            }
                            else
                            {
                                $showpopup = true;
                                $type = "deactivatesoon";
                            }
                        }
                        else
                        {
                            $showpopup = true;
                            $type = "deactivated";
                        }
                    }
                    else
                    {
                        $showpopup = true;
                        $type = "deactivated";
                    }
                    $row['showpopup'] = $showpopup;
                    $row['type'] = $type;

                    $returnData = ['success' => 1, 'data' => $row, 'maintenanceData' => $maintenanceData];
                }
                // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                else
                {
                    $returnData = msg(0, 422, 'Invalid Student ID!');
                }
            }
            catch(PDOException $e)
            {
                $returnData = msg(0, 500, $e->getMessage());
            }
        }
    }
}
else
{
    $returnData = msg(0, 401, 'Unauthorized!');
}
header('Content-Type: application/json');
echo json_encode($returnData);
?>
