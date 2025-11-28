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
            <select class = "form-control select2 " style = "width: 100%;" name = "disable_student_id" id = "disable_student_id" onchange = "feesInfo()" required>
            <option readonly selected value>Select Student</option>
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
                <option readonly value>No Student Found.</option>
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
            $sql1 = "SELECT b.name , b.belt_id from students s, belt b where b.belt_id = s.belt_id AND s.student_id =  ".$student_id;
            $result1 = $con->query( $sql1 );
            $data = mysqli_fetch_row($result1);
            $sql2 = "SELECT b.name , b.belt_id FROM belt b , students s where b.belt_id > s.belt_id AND s.student_id =  ".$student_id;
            $result2 = $con->query( $sql2 );
           ?>
        <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                   <label>From Date</label>
                   <div class="input-group date" id="dobdate" data-target-input="nearest">
                       <input type="text" class="form-control datetimepicker-input" data-target="#dobdate" name = "dob" required />
                       <div class="input-group-append" data-target="#dobdate" data-toggle="datetimepicker">
                           <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                       </div>
                   </div>
               </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                   <label>To Date</label>
                   <div class="input-group date" id="dojdate" data-target-input="nearest">
                       <input type="text" class="form-control datetimepicker-input" data-target="#dojdate" name = "doj" required />
                       <div class="input-group-append" data-target="#dojdate" data-toggle="datetimepicker">
                           <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
    <script>
        $(function() {
            //Date range picker
            $('#dobdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            //DOB range picker
            $('#dojdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        })

    </script>
       <div class="row">
           <div class="col-sm-6">
           <div class = "form-group">
           <label for="exampleInputEmail1">Current belt</label>
                <input type="text" class="form-control" value="<?php echo $data[0];?>" name = "current_belt" id="current_belt" placeholder="" required readonly>
                <input type="text" class="form-control" value="<?php echo $data[1];?>" name = "current_belt_id" id="current_belt_id" placeholder="" required readonly hidden>
            </div>
           </div>
           <div class="col-sm-6">
           <div class = "form-group">
            <label>Select Belt</label>
            <select class = "form-control select2 " style = "width: 100%;" name = "selected_belt_id" id = "selected_belt_id" onchange = "calculateFeesForBelt()" required>
            <option readonly disabled selected value>Select Belt</option>
            <?php
            if ( $result2->num_rows > 0 ) {
                while( $rows = $result2->fetch_assoc() ) {
                    ?>
                    <option data-select2-id = "30" value = "<?php echo $rows["belt_id"]; ?>"><?php echo $rows["belt_id"]." - ".$rows["name"];
                    ?></option>
                    <?php
                }
            } else {
                ?>
                <option readonly value>No more belts Found.</option>
                <?php
            }
            ?>
            </select>
            </div>
           </div>
       </div>
   
        <?php   
        }
    }
    if ( isset( $_POST['belt'] ) ) {
        $currentBeltId = $_POST['belt'];
        $sBeltId = $_POST['selected_belt'];
        $student_id = $_POST['student_id'];
         if ( $currentBeltId != '' ) {
             $sql3 = "SELECT b.fees FROM students s , branch b WHERE b.branch_id = s.branch_id AND s.student_id = ".$student_id;
             $result3 = $con->query( $sql3 );
             $data3 = mysqli_fetch_row($result3);             
        //     $sql2 = "SELECT b.name , b.belt_id FROM belt b , students s where b.belt_id > s.belt_id AND s.student_id =  ".$student_id;
        //     $result2 = $con->query( $sql2 );
           ?>
        
       <div class="row">
            <div class="col-sm-6">
                 <div class = "form-group">
                     <label for="exampleInputEmail1">Upgrade Belt</label>
                     <?php
                    // aya fees ni query branch mathi  levanu
                     $diff_belt = $sBeltId - $currentBeltId; 
                     $months = 6 * $diff_belt; 
             
                     $amountFees = $data3[0];
                     $totalFees = $months * $amountFees;
                     ?>
                     <input type="text" class="form-control" value="<?php echo $diff_belt; ?>" name = "diff_belt" id="current_belt" placeholder="" required readonly>
                </div>
           </div>
           <div class="col-sm-6">
             <div class = "form-group">
                    <label for="exampleInputEmail1">Month To Pay</label>
                 <input type="number" class="form-control" name = "cal_months"  value="<?php echo $months;?>" id="current_belt" placeholder="" required readonly>
              </div>
           </div>
         </div>
           <div class="row">
           <div class="col-sm-6">
             <div class = "form-group">
                    <label for="exampleInputEmail1">Total Fees</label>
                 <input type="number" class="form-control"  name = "cal_totalFees" value="<?php echo $totalFees;?>"  placeholder="" required readonly>
              </div>
           </div>
               <div class="col-sm-6">
             <div class = "form-group">
                    <label for="exampleInputEmail1">Total Hours</label>
                 <input type="number" class="form-control"  name = "totalHours" value="" id="current_belt" placeholder="" required>
              </div>
           </div>
           </div> 

        <?php
        }
    }
}
?>
