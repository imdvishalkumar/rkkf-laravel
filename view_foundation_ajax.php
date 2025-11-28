<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Database connection
    $con = new mysqli("localhost", "u931471842_tony", "Tony@2007", "u931471842_rkkf");
    
    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    if (isset($_POST['exam_atten'], $_POST['start_date'], $_POST['end_date']))
    {
        $count = $con->real_escape_string($_POST['exam_atten']);
        $startdate = $con->real_escape_string($_POST['start_date']);
        $enddate = $con->real_escape_string($_POST['end_date']);
        
        $sql = "SELECT 
                s.student_id, 
                CONCAT(s.firstname,' ',s.lastname) as name, 
                b.name as branch, 
                bt.name as belt, 
                IFNULL(ex.last_exam_date, 'Not Found') as last_exam_date, 
                IFNULL(a.attendance_count, '0') as atten, 
                IFNULL(f.last_fees_paid, 'Not Found') as last_fees_paid, 
                s.dadno, 
                s.dadwp, 
                s.momno, 
                s.momwp, 
                s.selfno, 
                s.selfwp 
            FROM 
                students s 
            LEFT JOIN branch b ON s.branch_id = b.branch_id
            LEFT JOIN belt bt ON s.belt_id = bt.belt_id
            LEFT JOIN (
                SELECT ea.student_id, MAX(ex.date) as last_exam_date
                FROM exam_attendance ea
                JOIN exam ex ON ea.exam_id = ex.exam_id AND ex.isPublished = 1
                WHERE ea.attend = 'P'
                GROUP BY ea.student_id
            ) ex ON s.student_id = ex.student_id
            LEFT JOIN (
                SELECT student_id, COUNT(*) as attendance_count
                FROM attendance 
                WHERE date <= ? AND date >= ? AND attend = 'P'
                GROUP BY student_id
            ) a ON s.student_id = a.student_id
            LEFT JOIN (
                SELECT student_id, DATE_FORMAT(MAX(DATE_FORMAT(CONCAT(year,'-',months,'-01'), '%Y-%m-%d')), '%m-%Y') as last_fees_paid
                FROM fees
                GROUP BY student_id
            ) f ON s.student_id = f.student_id
            WHERE 
                s.active = 1 
                AND IFNULL(a.attendance_count, 0) >= ?";
        
        // Prepare statement
        $stmt = $con->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssi", $enddate, $startdate, $count);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $con->error;
        }
    }
    else
    {
        echo "Incomplete Filter...";
        $con->close();
        exit;
    }

    // HTML content starts here
    ?>
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>GR No</th>
                <th>Name</th>
                <th>Branch</th>
                <th>Belt</th>
                <th>Last Exam Date</th>
                <th>Last Fees Paid</th>
                <th>Contact</th>
                <th>WhatsApp <i class='fab fa-whatsapp'></i></th>
                <th>Attendance</th>
            </tr>
        </thead>
        <tbody>
    <?php
    if ($result && $result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            echo "<tr>
                    <td>{$row["student_id"]}</td>
                    <td>{$row["name"]}</td>
                    <td>{$row["branch"]}</td>
                    <td>{$row["belt"]}</td>
                    <td>{$row["last_exam_date"]}</td>
                    <td>{$row["last_fees_paid"]}</td>
                    <td>";
                    echo !empty($row["dadno"]) ? $row["dadno"] . " " : "";
                    echo !empty($row["momno"]) ? $row["momno"] . " " : "";
                    echo !empty($row["selfno"]) ? $row["selfno"] : "";
            echo "</td><td>";
                    echo !empty($row["dadwp"]) ? $row["dadwp"] . "\n" : "";
                    echo !empty($row["momwp"]) ? $row["momwp"] . "\n" : "";
                    echo !empty($row["selfwp"]) ? $row["selfwp"] : "";
            echo "</td>
                  <td>{$row["atten"]}</td>
                  </tr>";
        }
    }
    else
    {
        echo "<tr><td colspan='9'>No results found</td></tr>";
    }
    ?>
        </tbody>
    </table>
    <?php
    $con->close();
}
?>
