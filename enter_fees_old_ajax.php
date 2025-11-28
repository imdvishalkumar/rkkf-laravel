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
<div class="form-group">
    <label>Select Student</label>
    <select class="form-control select2 " style="width: 100%;" name="disable_student_id" id="disable_student_id" onchange="feesInfo()" required>
        <option disabled selected value>Select Student</option>
        <?php
            if ( $result->num_rows > 0 ) {
                while( $rows = $result->fetch_assoc() ) {
                    ?>
        <option data-select2-id="30" value="<?php echo $rows["student_id"]; ?>"><?php echo $rows["student_id"]." - ".$rows["name"];
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

           
                ?>
                <div class="form-group">
                    <label>Select Month:</label>
                    <div class="input-group date" id="dojdate" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#dojdate" name="doj" required />
                        <div class="input-group-append" data-target="#dojdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                
<div class="form-group">
    <label>Select Month</label>
    <select class="form-control select2 " style="width: 100%;" name="increaseMonth" id="increaseMonth" required>
        <option disabled selected value>Select Months</option>
        <option data-select2-id="30" value="1">1</option>
        <option data-select2-id="30" value="2">2</option>
        <option data-select2-id="30" value="3">3</option>
        <option data-select2-id="30" value="4">4</option>
        <option data-select2-id="30" value="5">5</option>
        <option data-select2-id="30" value="6">6</option>
        <option data-select2-id="30" value="7">7</option>
        <option data-select2-id="30" value="8">8</option>
        <option data-select2-id="30" value="9">9</option>
        <option data-select2-id="30" value="10">10</option>
        <option data-select2-id="30" value="11">11</option>
        <option data-select2-id="30" value="12">12</option>
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
