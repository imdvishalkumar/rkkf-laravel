<?php
if ( $_POST ) {
    include_once( "connection.php" );
    if ( isset( $_POST['branchId'] ) ) {

        $branchId = $_POST['branchId'];
        if ( !( empty( $branchId ) ) ) {
            $sql = "SELECT CONCAT(s.firstname, ' ' , s.lastname) as name,s.student_id, s.active, a.* FROM students s, exam_attendance a WHERE s.student_id = a.student_id AND a.exam_id = ".$branchId." GROUP BY s.student_id";
            $result = $con->query( $sql );
            ?>
            <div class = "card-body p-0">

            <?php
            if ( $result->num_rows > 0 ) {
                ?>
                <table class = "table table">
                <thead>
                <tr>
                <th style = "width: 10px">#</th>
                <th>GrNo</th>
                <th>Name</th>
                <th>Attend</th>
                <th>Select Attend</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 0;
                while( $rows = $result->fetch_assoc() ) {
                    if ($rows["active"] == 1) {
                        echo "<tr>";
                    } else {
                        echo "<tr bgcolor='#FF0000'>";
                    }
                    ?>
                    <td><?php echo ($count + 1).".";
                    ?></td>
                    <td><?php echo $rows["student_id"];
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
                    <input type="hidden" name="attenId[<?php echo $count ?>]" value="<?php echo $rows["exam_attendance_id"] ?>">
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
                <center><button type="submit" name="submit" class="btn btn-primary">Update</button></center>
                <?php
            } else {
                $sql = "SELECT CONCAT(s.firstname, ' ' , s.lastname) as name, s.active, s.student_id FROM students s, exam_fees ef WHERE s.student_id = ef.student_id AND ef.status = 1 AND ef.exam_id = ".$branchId." GROUP BY s.student_id";
                // echo $sql;
                $result = $con->query( $sql );
                ?>
                <div class = "card-body p-0">

                <?php
                if ( $result->num_rows > 0 ) {
                    ?>
                    <table class = "table table">
                    <thead>
                    <tr>
                    <th style = "width: 10px">#</th>
                    <th>GrNo</th>
                    <th>Name</th>
                    <th>Select Attend</th>
                    </tr>
                    </thead>
                    <tbody>
                    <input type="hidden" name="update" value="1">
                    <?php
                    $count = 0;
                    while( $rows = $result->fetch_assoc() ) {
                        if ($rows["active"] == 0) {
                            echo "<tr bgcolor='#FF0000'>";
                        } else {
                            echo "<tr>";
                        }
                        ?>
                        <td><?php echo ($count + 1).".";
                        ?></td>
                        <td><?php echo $rows["student_id"];
                        ?></td>
                        <td><?php echo $rows["name"];
                        ?></td>

                        <td>
                        <input type="hidden" name="attenId[<?php echo $count ?>]" value="<?php echo $rows["student_id"] ?>">
                        <div class = "form-group clearfix">
                        <div class = "icheck-success d-inline">
                        <input type = "radio" name = "r1[<?php echo $count ?>]" value="P" checked id = "radioSuccess1[<?php echo $count ?>]">
                        <label for = "radioSuccess1[<?php echo $count ?>]">
                        Present
                        </label>
                        </div>
                        <div class = "icheck-danger d-inline">
                        <input type = "radio" name = "r1[<?php echo $count ?>]" value="A" id = "radioSuccess2[<?php echo $count ?>]">
                        <label for = "radioSuccess2[<?php echo $count ?>]">
                        Absent
                        </label>
                        </div>
                        <div class = "icheck-wetasphalt d-inline">
                        <input type = "radio" name = "r1[<?php echo $count ?>]" value="L" id = "radioSuccess3[<?php echo $count ?>]">
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
                }
            }
            ?>
            </div>
            <?php
        }
    }
}
?>
