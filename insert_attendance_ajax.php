<?php
if ( $_POST ) {
    include_once( "connection.php" );
    if ( isset( $_POST['branchId'] ) ) {

        $branchId = $_POST['branchId'];

        if ( !( empty( $branchId ) ) ) {
            $sql = "SELECT s.student_id, CONCAT(s.firstname, ' ' , s.lastname) as name, EXISTS(select student_id from leave_table WHERE CURDATE() BETWEEN from_date AND to_date AND student_id = s.student_id) as present FROM students s , branch br where s.branch_id = br.branch_id AND s.active = 1 AND s.branch_id = ".$branchId." AND student_id NOT IN (SELECT student_id FROM fastrack)";

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

                    <td><span><?php if($rows["present"] == '1') {
                         echo "L";
                    } else {
                         echo "P";
                    }
                    ?></span></td>
                    <?php
                    $present = '';
                    $absent = '';
                    $leave = '';
                    if ($rows["present"] == '0'){
                        $present = 'checked';
                    }
                    if (false){
                        $absent = 'checked';
                    }
                    if ($rows["present"] == '1'){
                        $leave = 'checked';
                    }
                    ?>
                    <td>
                    <input type="hidden" name="attenId[<?php echo $count ?>]" value="<?php echo $rows["student_id"] ?>">
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
