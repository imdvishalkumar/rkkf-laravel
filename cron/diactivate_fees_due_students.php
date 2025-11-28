<?php

require __DIR__ . '/../api/v2/classes/Database.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$query = "UPDATE students SET active = 0 WHERE student_id IN (SELECT s.student_id FROM students s WHERE IFNULL(DATE_FORMAT((SELECT DATE_FORMAT(CONCAT(year,'-',months,'-01'), '%Y-%m-%d') as mdate FROM fees WHERE student_id = s.student_id ORDER BY mdate DESC LIMIT 1), '%Y-%m-%d'),'1000-01-01') < date_sub(DATE_FORMAT(CONCAT(YEAR(now()),'-',MONTH(now()),'-01'), '%Y-%m-%d'), interval 1 month));";
$query_stmt = $conn->prepare($query);
$query_stmt->execute();
?>
