<?php
if ( $_POST ) {
    include_once( "connection.php" );
    if ( isset( $_POST['branchId'] ) && isset( $_POST['date'] ) ) {

        $branchId = $_POST['branchId'];
        $date = $_POST['date'];
        if ( !( empty( $branchId ) && empty( $date ) ) ) {
            $sql = "SELECT CONCAT(s.firstname, ' ' , s.lastname) as name, a.* FROM students s, event_attendance a WHERE s.student_id = a.student_id AND a.event_id = ".$branchId;
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
                <th>Winner / Loser</th>
                <th>Medal</th>
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
                    if (trim($rows["attend"]) == 'P'){
                        $present = 'checked';
                    }
                    if ($rows["attend"] == 'A'){
                        $absent = 'checked';
                    }
                    ?>
                    <td>
                    <input type="hidden" name="attenId[<?php echo $count ?>]" value="<?php echo $rows["event_attendance_id"] ?>">
                    <div class = "form-group clearfix">
                    <div class = "icheck-success d-inline">
                    <input type = "radio" name = "r1[<?php echo $count ?>]" onchange="disableResult(<?php echo $count ?>)" value="P" <?php echo $present ?> id = "radioSuccess1[<?php echo $count ?>]">
                    <label for = "radioSuccess1[<?php echo $count ?>]">
                    Present
                    </label>
                    </div>
                    <div class = "icheck-danger d-inline">
                    <input type = "radio" name = "r1[<?php echo $count ?>]" onchange="disableResult(<?php echo $count ?>)" value="A" <?php echo $absent ?> id = "radioSuccess2[<?php echo $count ?>]">
                    <label for = "radioSuccess2[<?php echo $count ?>]">
                    Absent
                    </label>
                    </div>
                    </div>
                    </td>
                    <td>
                    <div class="form-group">
                        <select class="form-control select2 " style="width: 100%;" name="result[<?php echo $count ?>]" id="result[<?php echo $count ?>]" <?php if ($absent == 'checked') { echo "disabled"; } ?> onchange="disableMedal(<?php echo $count ?>)" required>
                            <option disabled selected value="">Select Result</option>
                            <option data-select2-id="30" value="Winner">Winner</option>
                            <option data-select2-id="31" value="Loser">Loser</option>
                        </select>
                    </div>    
                    </td>
                    <td>
                    <div class="form-group">
                        <select class="form-control select2 " style="width: 100%;" name="medal[<?php echo $count ?>]" id="medal[<?php echo $count ?>]" <?php if ($absent == 'checked') { echo "disabled"; } ?> required>
                            <option disabled selected value="">Select Medal</option>
                            <option data-select2-id="30" value="Gold">Gold</option>
                            <option data-select2-id="31" value="Silver">Silver</option>
                            <option data-select2-id="31" value="Bronze1">Bronze1</option>
                            <option data-select2-id="31" value="Bronze2">Bronze2</option>
                        </select>
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
