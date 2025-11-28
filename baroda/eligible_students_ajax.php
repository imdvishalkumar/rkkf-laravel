<?php
if ($_POST) {
    include_once ("connection.php");
    if (isset($_POST['exam_id']) && isset($_POST['student_id']) && isset($_POST['eligible'])) {
        $exam_id = $_POST['exam_id'];
        $student_id = $_POST['student_id'];
        $eligible = filter_var( $_POST['eligible'], FILTER_VALIDATE_BOOLEAN);
        echo $eligible;
        $check = "SELECT * FROM `special_case_exam` WHERE student_id = '" . $student_id . "' AND exam_id = '" . $exam_id . "'";
        $result = $con->query($check);
        if ($result->num_rows > 0) {
            if ($eligible) {
                $q = "UPDATE `special_case_exam` SET `eligible` = '1' WHERE student_id = '" . $student_id . "' AND exam_id = '" . $exam_id . "'";
            } else {
                $q = "UPDATE `special_case_exam` SET `eligible` = '0' WHERE student_id = '" . $student_id . "' AND exam_id = '" . $exam_id . "'";
            }
        } else {
            if ($eligible) {
                $q = "INSERT INTO `special_case_exam` (`special_id`, `student_id`, `exam_id`, `eligible`) VALUES (NULL, '" . $student_id . "', '" . $exam_id . "', '1');";
            } else {
                $q = "INSERT INTO `special_case_exam` (`special_id`, `student_id`, `exam_id`, `eligible`) VALUES (NULL, '" . $student_id . "', '" . $exam_id . "', '0');";
            }
        }
        $con->query($q);
    }
}
?>
