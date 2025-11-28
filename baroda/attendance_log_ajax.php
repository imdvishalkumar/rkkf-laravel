<?php
include_once("auth.php"); //include auth.php file on all secure pages
if ($_POST)
{
    include_once ("connection.php");
    if ( isset( $_POST['grno'] ) ) {
        $grno = $_POST['grno'];
        $param = $_POST['check_active'];
        if ( $grno != '' ) {
            $student_id = '';
            if ($param == 'true') {
                $sql = "SELECT CONCAT(firstname ,' ',lastname) as name , student_id, (select name from branch where branch_id = s.branch_id) as branch_name ,(select name from belt where belt_id = s.belt_id) as belt_name FROM students s WHERE (CAST(student_id AS VARCHAR(9)) LIKE '".$grno."%' or CONCAT(firstname ,' ',lastname) like '%".$grno."%') AND s.active = 1";
            } else {
                $sql = "SELECT CONCAT(firstname ,' ',lastname) as name , student_id, (select name from branch where branch_id = s.branch_id) as branch_name ,(select name from belt where belt_id = s.belt_id) as belt_name FROM students s WHERE (CAST(student_id AS VARCHAR(9)) LIKE '".$grno."%' or CONCAT(firstname ,' ',lastname) like '%".$grno."%') AND s.active != 1";
            }
            $result = $con->query( $sql );
            ?>
            <div class = "form-group">
            <label>Select Student</label>
            <select class = "form-control select2 " style = "width: 100%;" name = "disable_student_id" id = "disable_student_id" onchange = "get_AttenInfo()" required>
            <option disabled selected value>Select Student</option>
            <?php
            if ( $result->num_rows > 0 ) {
                while( $rows = $result->fetch_assoc() ) {
                    ?>
                    <option data-select2-id = "30" value = "<?php echo $rows["student_id"]; ?>"><?php echo $rows["student_id"]." - ".$rows["name"]." - ".$rows["branch_name"]." - ".$rows["belt_name"];
                    ?></option>
                    <?php
                }
            } else {
                ?>
                <option disabled value>No Student Found.</option>
                <?php
            }
            ?>
            </select>
            </div>
            <?php
        }
    }
    if ( isset( $_POST['disable_student_id'] ) ) {
        $student_id = $_POST['disable_student_id'];
        if ( $student_id != '' ) {

            $sql1 = "SELECT SUM(case attend when 'P' then 1 else 0 end) as present, SUM(case attend when 'A' then 1 else 0 end) as absent, SUM(case attend when 'L' then 1 else 0 end) as leaves,(SELECT name FROM branch WHERE branch_id = a.branch_id) as branch FROM attendance a where a.student_id = ".$student_id." GROUP BY a.branch_id";
            $sql2 = "SELECT b.belt_id,b.name, IFNULL((SELECT DISTINCT e.date FROM exam_fees ef, exam_attendance ea, exam e WHERE s.student_id = ef.student_id AND s.student_id = ea.student_id AND ef.exam_belt_id = b.belt_id AND ef.status = 1 AND ea.attend ='P' AND ea.exam_id = e.exam_id AND ea.exam_id = ef.exam_id LIMIT 1),'-') as date, IFNULL((SELECT DISTINCT e.exam_id FROM exam_fees ef, exam_attendance ea, exam e WHERE s.student_id = ef.student_id AND s.student_id = ea.student_id AND ef.exam_belt_id = b.belt_id AND ef.status = 1 AND ea.attend ='P' AND ea.exam_id = e.exam_id AND ea.exam_id = ef.exam_id LIMIT 1),'-') as exam_id, IFNULL((SELECT DISTINCT ea.certificate_no FROM exam_fees ef, exam_attendance ea, exam e WHERE s.student_id = ef.student_id AND s.student_id = ea.student_id AND ef.exam_belt_id = b.belt_id AND ef.status = 1 AND ea.attend ='P' AND ea.exam_id = e.exam_id AND ea.exam_id = ef.exam_id LIMIT 1),'-') as certificate FROM belt b, students s WHERE b.belt_id != 1 AND b.belt_id <= s.belt_id AND s.student_id = ".$student_id;
            $sql3 = "SELECT STR_TO_DATE(DATE_FORMAT(CONCAT(year,'-',months,'-01'),'%d/%m/%Y'),'%d/%m/%Y') as date, months, year, amount from fees WHERE student_id = ".$student_id." ORDER BY date;";
            $sql4 = "SELECT SUM(case attend when 'P' then 1 else 0 end) as present, SUM(case attend when 'A' then 1 else 0 end) as absent, SUM(case attend when 'L' then 1 else 0 end) as leaves, DATE_FORMAT(date,'%m-%Y') as date FROM attendance WHERE student_id = ".$student_id." GROUP BY Month(date) , YEAR(date) ORDER BY YEAR(date),Month(date)";
            if ( isset( $_POST['start_date'] ) && isset( $_POST['end_date'] ) && (!empty( $_POST['start_date'] )) && (!empty( $_POST['end_date'] )) ) {
                $startdate = $_POST['start_date'];
                $enddate = $_POST['end_date'];
                $startdate1 = substr($startdate, 0, -3);
                $enddate1 = substr($enddate, 0, -3);

                $sql1 = "SELECT SUM(case attend when 'P' then 1 else 0 end) as present, SUM(case attend when 'A' then 1 else 0 end) as absent, SUM(case attend when 'L' then 1 else 0 end) as leaves,(SELECT name FROM branch WHERE branch_id = a.branch_id) as branch FROM attendance a where a.student_id = ".$student_id." AND a.date >= '" . $startdate . "' AND a.date <= '" . $enddate . "' GROUP BY a.branch_id;";
                //$sql2 = "SELECT DISTINCT b.name,e.date FROM belt b, students s, exam_fees ef, exam_attendance ea, exam e WHERE b.belt_id <= s.belt_id AND s.student_id = ef.student_id AND s.student_id = ea.student_id AND ef.exam_belt_id = b.belt_id AND ef.status = 1 AND ea.attend ='P' AND ea.exam_id = e.exam_id AND ea.exam_id = ef.exam_id AND e.date >= '" . $startdate . "' AND e.date <= '" . $enddate . "' AND s.student_id = ".$student_id;
                $sql3 = "SELECT STR_TO_DATE(DATE_FORMAT(CONCAT(year,'-',months,'-01'),'%d/%m/%Y'),'%d/%m/%Y') as date, months, year, amount from fees WHERE student_id = ".$student_id." AND Date(CONCAT('" . $startdate1 . "', '-01')) <= Date(CONCAT(year , '-' , months , '-01')) AND Date(CONCAT('" . $enddate1 . "', '-01')) >= Date(CONCAT(year , '-' , months , '-01')) ORDER BY date;";
                $sql4 = "SELECT SUM(case attend when 'P' then 1 else 0 end) as present, SUM(case attend when 'A' then 1 else 0 end) as absent, SUM(case attend when 'L' then 1 else 0 end) as leaves, DATE_FORMAT(date,'%m-%Y') as date FROM attendance WHERE student_id = ".$student_id." AND date >= '" . $startdate . "' AND date <= '" . $enddate . "' GROUP BY Month(date) , YEAR(date) ORDER BY YEAR(date),Month(date)";
            }

            $result = $con->query( $sql1 );
            $result2 = $con->query( $sql2 );
            $result3 = $con->query( $sql3 );
            $result4 = $con->query( $sql4 );
            $resultExam = $con->query( "SELECT exam_id,name,date FROM exam;" );
            $resultExamAdd = $con->query( "SELECT exam_id,name,date FROM exam;" );
            $phones = "SELECT dadno,dadwp,momno,momwp,selfno,selfwp FROM students WHERE student_id = ".$student_id." AND dadno != '' AND dadwp != '' AND momno != '' AND momwp != '' AND selfno != '' AND selfwp != ''";
            $resultPhone = $con->query( $phones );
            $total_p = 0;
            $total_a = 0;
            $total_l = 0;
     
if ($resultPhone->num_rows > 0)
{   $row = $resultPhone->fetch_assoc();
    echo"<label>Dad No : ".$row['dadno']."</label><br>";
    echo"<label>Dad Wp : ".$row['dadwp']."</label><br>";
    echo"<label>Mom No : ".$row['momno']."</label><br>";
    echo"<label>Mom Wp : ".$row['momwp']."</label><br>";
    echo"<label>Self No : ".$row['selfno']."</label><br>";
    echo"<label>Self Wp : ".$row['selfwp']."</label><br>";
}
?>
<label>Attendance by Branch</label>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Branch</th>
            <th>Present</th>
            <th>Absent</th>
            <th>Leave</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($result->num_rows > 0)
            {

                while ($row = $result->fetch_assoc())
                {
                    echo "<tr>
                    <td>" . $row["branch"]. "</td>
                    <td>" . $row["present"]. "</td>
                    <td>" . $row["absent"]. "</td>
                    <td>" . $row["leaves"] . "</td>
                    </tr>";
                    $total_p = $total_p + $row["present"];
                    $total_a = $total_a + $row["absent"];
                    $total_l = $total_l + $row["leaves"];
                }
                echo"<td> Total </td>
                <td>".$total_p."</td>
                <td>".$total_a."</td>
                <td>".$total_l."</td>";
            }
            else
            {
?><tr>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr><?php
            }
?>
    </tbody>
</table>
<label>Belts Data</label>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <?php
            $dates = array();
            $belt_ids = array();
            $exam_ids = array();
            $certificates = array();
            if ($result2->num_rows > 0)
            {
                while ($row = $result2->fetch_assoc())
                {
                    echo "<th>" . $row["name"]. "</th>";
                    array_push($dates,$row["date"]);
                    array_push($belt_ids,$row["belt_id"]);
                    array_push($exam_ids,$row["exam_id"]);
                    array_push($certificates,$row["certificate"]);
                }
            } else {
                echo "<th> - </th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <tr>
        <?php
            $i = 0;
            $count = count($dates);
            for ($i = 0; $i < $count; $i++) {
                $value = $dates[$i];
             echo "<td>";
             echo "<div id='div_".$belt_ids[$i]."'>Exam Date : <br>".$value."<br>Certificate No : <br>".$certificates[$i]."<br>";
             if ($value == '-') {
                    echo "<button class='add-user action-icon' title='add'>
                        <span class='far fa-plus-square' onclick='addBeltData($belt_ids[$i],$student_id)'></span>
                        </button><br>";
                } else {
                    echo "<button  class='edit-user action-icon' title='Edit'>
                        <span class='far fa-edit' onclick='editBeltData($belt_ids[$i],$student_id,$exam_ids[$i],\"$certificates[$i]\")'></span>
                        </button><br>";
                }
             echo"</div>";
                
                echo "</td>"; 
            }
            // foreach ($dates as $value) {
            //     echo "<td>" . $value ."<br>";
            //     if ($value == '-') {
                    
            //         echo "<a href='edit_student.php?belt_id=".$belt_ids[$i]."&student_id=".$student_id."' class='add-user action-icon' title='add'>
            //             <span class='far fa-plus-square'></span>
            //             </a><br>";
                        
            //     } else {
                    
            //         echo "<a href='edit_student.php?belt_id=".$belt_ids[$i]."&student_id=".$student_id."&exam_id=".$exam_ids[$i]."' class='edit-user action-icon' title='Edit'>
            //             <span class='fas fa-edit'></span>
            //             </a><br>";
            //     }
            //     echo "</td>";
            //     $i++;
            // }
          ?>
        </tr>
    </tbody>
</table>

<label>Fees Data</label>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <?php
            $amounts = array();
            if ($result3->num_rows > 0)
            {
                while ($row = $result3->fetch_assoc())
                {
                    echo "<th>" . $row["months"] . " - " . $row["year"] . "</th>";
                    array_push($amounts,$row["amount"]);
                }
            } else {
                echo "<th> - </th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <tr>
        <?php
            foreach ($amounts as $value) {
                echo "<td>" . $value . "</td>";
            }
          ?>
        </tr>
    </tbody>
</table>

<label>Attendance by Months</label>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <?php
            $attend = array();
            if ($result4->num_rows > 0)
            {
                while ($row = $result4->fetch_assoc())
                {
                    echo "<th>" . $row["date"] . "</th>";
                    array_push($attend,"P - ".$row["present"]." <br> A - ".$row["absent"]." <br> L - ".$row["leaves"]."");
                }
            } else {
                echo "<th> - </th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <tr>
        <?php
            foreach ($attend as $value) {
                echo "<td>" . $value . "</td>";
            }
          ?>
        </tr>
    </tbody>
</table>


<script>
function editBeltData(beltId,studentId,examId,cert) {
    var divId = "#div_" + beltId;
    console.log(divId);
        
    var htmlContent = "<div class='form-group'><select class='form-control select2 ' style='width: 100%;' name='selected_exam_id_"+beltId+"' id='selected_exam_id_"+beltId+"' required><option disabled selected value>Select Exam</option><?php if ( $resultExam->num_rows > 0 ) { while( $rows=$resultExam->fetch_assoc() ) { ?> <option data-select2-id='30' value='<?php echo $rows['exam_id']; ?>'><?php echo $rows['name'].' - '.$rows['date']; ?> </option> <?php }}else{ ?> <option disabled value>No Exam Found.</option> <?php } ?></select> <label>Certificate No</label> <input type='text' class='form-control' id='certificate_"+beltId+"' value = '"+cert+"' placeholder='Certificate No'> <span class='fas fa-sign-in-alt' onclick='editBeltDataSubmit("+beltId+","+studentId+","+examId+")'></span> </div>";
    
    var html = "<div class='form-group'> <input list='somethingelse' class='form-control select2 ' style='width: 100%;' name='selected_exam_id_"+beltId+"' id='selected_exam_id_"+beltId+"' required> <datalist id='somethingelse'> <option disabled selected value>Select Exam</option> <?php if ( $resultExam->num_rows > 0 ) { while( $rows=$resultExam->fetch_assoc() ) { ?> <option data-select2-id='30' value='<?php echo $rows['exam_id']; ?>'><?php echo $rows['name'].' - '.$rows['date']; ?> </option> <?php }}else{ ?> <option disabled value>No Exam Found.</option> <?php } ?> </datalist> <label>Certificate No</label> <input type='text' class='form-control' id='certificate_"+beltId+"' value = '"+cert+"' placeholder='Certificate No'> <span class='fas fa-sign-in-alt' onclick='editBeltDataSubmit("+beltId+","+studentId+","+examId+")'></span> </div>";
    $(divId).html(html);
}
function editBeltDataSubmit(beltId,studentId,oldExamId) {
    var divId = "#div_" + beltId;
    var id = "#selected_exam_id_" + beltId;
    var certid = "#certificate_" + beltId;
    var examId = $(id).val();
    var cert = $(certid).val();
    if (examId != null && cert) {
        $.ajax({
            type: "POST",
            url: "attendance_log_ajax.php", // Name of the php files
            data: {
                belt_id: beltId,
                exam_id: examId,
                old_exam_id: oldExamId,
                student_id: studentId,
                certi: cert
            },
            success: function(html) {
                $(divId).html(html);
            }
        });
    }
}

function addBeltDataSubmit(beltId,studentId) {
    var divId = "#div_" + beltId;
    var id = "#selected_exam_id_" + beltId;
    var certid = "#certificate_" + beltId;
    var examId = $(id).val();
    var cert = $(certid).val();
    if (examId != null && cert) {
        $.ajax({
            type: "POST",
            url: "attendance_log_ajax.php", // Name of the php files
            data: {
                belt_id: beltId,
                exam_id: examId,
                student_id: studentId,
                certi: cert
            },
            success: function(html) {
                $(divId).html(html);
            }
        });
    }
}
function addBeltData(beltId,studentId) {
    var divId = "#div_" + beltId;
    console.log(divId);
    var htmlContent = "<div class='form-group'><select class='form-control select2 ' style='width: 100%;' name='selected_exam_id_"+beltId+"' id='selected_exam_id_"+beltId+"' required><option disabled selected value>Select Exam</option><?php if ( $resultExamAdd->num_rows > 0 ) { while( $rows=$resultExamAdd->fetch_assoc() ) { ?> <option data-select2-id='30' value='<?php echo $rows['exam_id']; ?>'><?php echo $rows['name'].' - '.$rows['date']; ?> </option> <?php }}else{ ?> <option disabled value>No Exam Found.</option> <?php } ?></select> <input type='text' class='form-control' id='certificate_"+beltId+"' placeholder='Certificate No'> <span class='fas fa-sign-in-alt' onclick='addBeltDataSubmit("+beltId+","+studentId+")'></span> </div>";
    $(divId).html(htmlContent);
}
</script>

<?php 
        }
    }
    if ( isset( $_POST['belt_id'] ) && isset( $_POST['exam_id'] ) && isset( $_POST['old_exam_id'] ) && isset( $_POST['student_id'] ) && isset( $_POST['certi'] ) ) {
        
        $belt_id = $_POST['belt_id'];
        $exam_id = $_POST['exam_id'];
        $old_exam_id = $_POST['old_exam_id'];
        $student_id = $_POST['student_id'];
        $cert = $_POST['certi'];

        $sql1 = "UPDATE exam_fees SET exam_id = '".$exam_id."' WHERE exam_id = ".$old_exam_id." AND student_id = ".$student_id." AND exam_belt_id = ".$belt_id.";";
        $sql2 = "UPDATE exam_attendance SET exam_id = '".$exam_id."', certificate_no = '".$cert."' WHERE  exam_id = ".$old_exam_id." AND student_id = ".$student_id.";";
        $sql3 = "SELECT date FROM exam WHERE exam_id = '".$exam_id."';";
        
        $result1 = $con->query( $sql1 );
        $result2 = $con->query( $sql2 );
        $result3 = $con->query( $sql3 );
        $row = $result3->fetch_assoc();
        
        if ($result1 && $result2) {
            echo "Exam Date : <br>".$row['date']."<br>Certificate No : <br>".$cert."<br>";
            echo "<button  class='edit-user action-icon' title='Edit'>
                        <span class='far fa-edit' onclick='editBeltData($belt_id,$student_id,$exam_id,$cert)'></span>
                        </button><br>";
        } else {
            echo "Error occured.";
        }
    } else if ( isset( $_POST['belt_id'] ) && isset( $_POST['exam_id'] ) && isset( $_POST['student_id'] ) && isset( $_POST['certi'] ) ) {
        
        $belt_id = $_POST['belt_id'];
        $exam_id = $_POST['exam_id'];
        $student_id = $_POST['student_id'];
        $cert = $_POST['certi'];
        
        $sql1 = "INSERT INTO `exam_attendance` (`exam_attendance_id`, `exam_id`, `student_id`, `attend`, `user_id`, `certificate_no`) VALUES (NULL, '".$exam_id."', '".$student_id."', 'P', '1', '".$cert."');";
        $sql2 = "INSERT INTO `exam_fees` (`exam_fees_id`, `exam_id`, `student_id`, `date`, `mode`, `rp_order_id`, `status`, `amount`, `exam_belt_id`) VALUES (NULL, '".$exam_id."', '".$student_id."', CURDATE(), 'old_record', 'manual', '1', '0', '".$belt_id."');";
        $sql3 = "SELECT date FROM exam WHERE exam_id = '".$exam_id."';";
        
        $result1 = $con->query( $sql1 );
        $result2 = $con->query( $sql2 );
        $result3 = $con->query( $sql3 );
        
        $row = $result3->fetch_assoc();
        
        if ($result1 && $result2) {
            echo "Exam Date : <br>".$row['date']."<br>Certificate No : <br>".$cert."<br>";
            echo "<button  class='edit-user action-icon' title='Edit'>
                        <span class='far fa-edit' onclick='editBeltData($belt_id,$student_id,$exam_id)'></span>
                        </button><br>";
        } else {
            echo "Error occured.";
        }
    }
    // aya thi new add thy ske ajax Post method hovi joi bus
}
?>
