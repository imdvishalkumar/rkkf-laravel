<?php
if ( $_POST ) {
    include_once( "connection.php" );
    if ( isset( $_POST['grno'] ) ) {
        $grno = $_POST['grno'];
        if ( $grno != '' ) {
            $student_id = '';
            $sql = "SELECT CONCAT(firstname ,' ',lastname) as name, s.active, student_id, (select name from branch where branch_id = s.branch_id) as branch_name ,(select name from belt where belt_id = s.belt_id) as belt_name FROM students s WHERE (CAST(student_id AS VARCHAR(9)) LIKE '".$grno."%' or CONCAT(firstname ,' ',lastname) like '%".$grno."%')";

            // $sql = "SELECT CONCAT(firstname ,' ',lastname) as name , student_id FROM students WHERE CAST(student_id AS VARCHAR(9)) LIKE '".$grno."%' or CONCAT(firstname ,' ',lastname) like '%".$grno."%'";
            $result = $con->query( $sql );
            ?>
<div class="form-group">
    <label>Select Student</label>
    <select class="form-control select2 " style="width: 100%;" name="disable_student_id" id="disable_student_id" onchange="feesInfo()" required>
        <option disabled selected value>Select Student</option>
        <?php
            if ( $result->num_rows > 0 ) {
                while( $rows = $result->fetch_assoc() ) {
                    if ($rows["active"] == 0) {
                    ?>
                    <option data-select2-id = "30" value = "<?php echo $rows["student_id"]; ?>"><?php echo $rows["student_id"]." - ".$rows["name"]." - ".$rows["branch_name"]." - ".$rows["belt_name"]." - Deactive";
                    ?></option>
        <?php
                    } else {
                    ?>
                    <option data-select2-id = "30" value = "<?php echo $rows["student_id"]; ?>"><?php echo $rows["student_id"]." - ".$rows["name"]." - ".$rows["branch_name"]." - ".$rows["belt_name"];
                    ?></option>
        <?php
                    }
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

            $sql1 = "SELECT * FROM exam";
            $sql2 = "SELECT * FROM belt";
            $result1 = $con->query( $sql1 );
            $result2 = $con->query( $sql2 );
            
            ?>
<div class="form-group">
    <label>Select Exam</label>
    <select class="form-control select2 " style="width: 100%;" name="exam_id" id="exam_id" required>
        <option disabled selected value>Select Exam</option>
        <?php
            if ( $result1->num_rows > 0 ) {
                while( $rows = $result1->fetch_assoc() ) {
                    ?>
        <option data-select2-id="30" value="<?php echo $rows["exam_id"]; ?>"><?php echo $rows["name"];
                    ?></option>
        <?php
                }
            } else {
                ?>
        <option disabled value>No Exam Found.</option>
        <?php
            }
            ?>
    </select>
</div>
<div class="form-group">
    <label>Select Belt</label>
    <select class="form-control select2 " style="width: 100%;" name="belt_id" id="belt_id" required>
        <option disabled selected value>Select Belt</option>
        <?php
            if ( $result2->num_rows > 0 ) {
                while( $rows = $result2->fetch_assoc() ) {
                    ?>
        <option data-select2-id="30" value="<?php echo $rows["belt_id"]; ?>"><?php echo $rows["name"];
                    ?></option>
        <?php
                }
            } else {
                ?>
        <option disabled value>No Belt Found.</option>
        <?php
            }
            ?>
    </select>
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Amount (Total Fees)</label>
    <input type="text" class="form-control" name="amount" id="amount" placeholder="" required>
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Remarks</label>
    <input type="text" class="form-control" name="remarks" id="remarks" placeholder="" required>
</div>
<script type="text/javascript">
    showBtn();
</script>
<?php   
        }
    }
}
?>
