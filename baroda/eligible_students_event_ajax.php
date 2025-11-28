<?php
if ($_POST) {
    include_once ("connection.php");
    if (isset($_POST['event_id']) && isset($_POST['student_id']) && isset($_POST['eligible'])) {
        $event_id = $_POST['event_id'];
        $student_id = $_POST['student_id'];
        $eligible = filter_var( $_POST['eligible'], FILTER_VALIDATE_BOOLEAN);
        $check = "SELECT * FROM `special_case_event` WHERE student_id = '" . $student_id . "' AND event_id = '" . $event_id . "'";
        $result = $con->query($check);
        if ($result->num_rows > 0) {
            if ($eligible) {
                $q = "UPDATE `special_case_event` SET `eligible` = '1' WHERE student_id = '" . $student_id . "' AND event_id = '" . $event_id . "'";
            } else {
                $q = "UPDATE `special_case_event` SET `eligible` = '0' WHERE student_id = '" . $student_id . "' AND event_id = '" . $event_id . "'";
            }
        } else {
            if ($eligible) {
                $q = "INSERT INTO `special_case_event` (`special_id`, `student_id`, `event_id`, `eligible`) VALUES (NULL, '" . $student_id . "', '" . $event_id . "', '1');";
            } else {
                $q = "INSERT INTO `special_case_event` (`special_id`, `student_id`, `event_id`, `eligible`) VALUES (NULL, '" . $student_id . "', '" . $event_id . "', '0');";
            }
        }
        $s = $con->query($q);
        if ($s) {
            if(!$eligible) {
              echo "<button id='btnModal' value='".$student_id."' onclick='eligibleStudent(".$student_id.",".$event_id.",true)' style='padding: 0;border: none;background: none;'><span class='fas fa-check'></span></button>";
          }	else {
              echo "<button id='btnModal' value='".$student_id."' onclick='eligibleStudent(".$student_id.",".$event_id.",false)' style='padding: 0;border: none;background: none;'><span class='fas fa-times'></span></button>";
          }
        }
    }
}
?>
