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

            $sql1 = "SELECT * FROM fees WHERE student_id = ".$student_id." ORDER BY year DESC , months DESC LIMIT 0,1";
            $result = $con->query( $sql1 );
            if ( mysqli_num_rows( $result ) > 0 ) {
                $row = mysqli_fetch_assoc( $result );
                ?>
                <div class = "form-group">
                <?php
                $formattedMonthArray = array(
                    "1" => "January", "2" => "February", "3" => "March", "4" => "April",
                    "5" => "May", "6" => "June", "7" => "July", "8" => "August",
                    "9" => "September", "10" => "October", "11" => "November", "12" => "December",
                );
                $mn = $row['months'];
                $yr = $row['year'];
                ?>
                <input type="text" name="disable_month" value="<?php echo $mn; ?>" hidden />
                <input type="text" name="disable_year" value="<?php echo $yr; ?>" hidden />
                <?php
                echo "<label>Last fee paid till ".$formattedMonthArray[$mn]." ".$yr." on ".$row['date']."</label>\n\n\n";
                ?><br><label>Select Month</label>
                
                    <div class="col-sm-6">
                    <!-- radio -->
                    <div class="form-group clearfix">
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary1" name="increaseMonth" value="1" required>
                        <label for="radioPrimary1">
                            1 Month
                        </label>
                      </div>
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary2" name="increaseMonth" value="2" >
                        <label for="radioPrimary2">
                            2 Months
                        </label>
                      </div>
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary3" name="increaseMonth" value="3" >
                        <label for="radioPrimary3">
                          3 Months
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
