<?php
if ( $_POST ) {
    include_once( "connection.php" );
    if ( isset( $_POST['grno'] ) ) {
        $grno = $_POST['grno'];
        if ( $grno != '' ) {
            $student_id = '';
            $sql = "SELECT CONCAT(firstname ,' ',lastname) as name , student_id FROM students WHERE CAST(student_id AS VARCHAR(9)) LIKE '".$grno."%' or CONCAT(firstname ,' ',lastname) like '%".$grno."%'";
            $result = $con->query( $sql );
            ?>
            <div class = "form-group">
            <label>Select Student</label>
            <select class = "form-control select2 select2-hidden-accessible" style = "width: 100%;" name = "disable_student_id" id = "disable_student_id" onchange = "feesInfo()" required>
            <option disabled selected value>Select Student</option>
            <?php
            if ( $result->num_rows > 0 ) {
                while( $rows = $result->fetch_assoc() ) {
                    ?>
                    <option data-select2-id = "30" value = "<?php echo $rows["student_id"]; ?>"><?php echo $rows["student_id"]." - ".$rows["name"];
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

            $sql1 = "SELECT * FROM exam WHERE date > CURDATE()";
            $result = $con->query( $sql1 );
            if ( mysqli_num_rows( $result ) > 0 ) {
                ?>
                <div class = "form-group">
                <label>Select Exam</label>
            <select class = "form-control select2 select2-hidden-accessible" style = "width: 100%;" name = "disable_exam_id" id = "disable_exam_id" required>
            <option disabled selected value>Select Exam</option>
            <?php
            if ( $result->num_rows > 0 ) {
                while( $rows = $result->fetch_assoc() ) {
                    ?>
                    <option data-select2-id = "30" value = "<?php echo $rows["exam_id"]; ?>"><?php echo $rows["exam_id"]." - ".$rows["name"];
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
            
                
                    <div class="col-sm-6">
                    <!-- radio -->
                    <div class="form-group clearfix">
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary1" name="markEligible" value="1" required>
                        <label for="radioPrimary1">
                            Make Eligible
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
            }
        }
    }
}
?>
