<?php
include_once("auth.php"); //include auth.php file on all secure pages
if ( $_POST ) {
    include_once( "connection.php" );
    if ( isset( $_POST['branchId'] ) && isset( $_POST['date'] ) ) {

        $branchId = $_POST['branchId'];
        $date = $_POST['date'];
        if ( !( empty( $branchId ) && empty( $date ) ) ) {
            $sql = "SELECT CONCAT(s.firstname, ' ' , s.lastname) as name, a.* FROM students s, attendance a WHERE s.student_id = a.student_id AND a.branch_id = ".$branchId." AND a.date = '".$date."'  AND a.is_additional = 0 AND s.active = 1";
            $result = $con->query( $sql );
            ?>
            <div class = "card-body p-0">

            <?php
            if ( $result->num_rows > 0 ) {
                ?>
                <table class = "table table-striped">
                <thead>
                <tr>
                <th style = "width: 10px">#</th>
                <th>Name</th>
                <th>Attend</th>
                <th>Select Attend</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 0;
                while( $rows = $result->fetch_assoc() ) {
                    ?>
                    <tr>
                    <td><?php echo ($count + 1).".";
                    ?></td>
                    <td><?php echo $rows["name"];
                    ?></td>

                    <td><span><?php echo $rows["attend"];
                    ?></span></td>
                    <?php
                    $present = '';
                    $absent = '';
                    $leave = '';
                    if (trim($rows["attend"]) == 'P'){
                        $present = 'checked';
                    }
                    if ($rows["attend"] == 'A'){
                        $absent = 'checked';
                    }
                    if ($rows["attend"] == 'L'){
                        $leave = 'checked';
                    }
                    ?>
                    <td>
                    <input type="hidden" name="attenId[<?php echo $count ?>]" value="<?php echo $rows["attendance_id"] ?>">
                    <div class = "form-group clearfix">
                    <div class = "icheck-success d-inline">
                    <input type = "radio" name = "r1[<?php echo $count ?>]" value="P" <?php echo $present ?> id = "radioSuccess1[<?php echo $count ?>]">
                    <label for = "radioSuccess1[<?php echo $count ?>]">
                    Present
                    </label>
                    </div>
                    <div class = "icheck-danger d-inline">
                    <input type = "radio" name = "r1[<?php echo $count ?>]" value="A" <?php echo $absent ?> id = "radioSuccess2[<?php echo $count ?>]">
                    <label for = "radioSuccess2[<?php echo $count ?>]">
                    Absent
                    </label>
                    </div>
                    <div class = "icheck-wetasphalt d-inline">
                    <input type = "radio" name = "r1[<?php echo $count ?>]" value="L" <?php echo $leave ?> id = "radioSuccess3[<?php echo $count ?>]">
                    <label for = "radioSuccess3[<?php echo $count ?>]">
                    Leave
                    </label>
                    </div>
                    </div>
                    </td>
                    </tr>

                    <?php
                        $count++;
                }
                ?>
                </tbody>
                </table>
                <center><button type="submit" name="submit" class="btn btn-primary">Submit</button></center>
                <?php
            } else {
                ?>
                <center>
                <h3>No Student Found</h3>
                </center>
                <?php
            }
            ?>
            </div>
            <?php
        }
    }
}
?>
