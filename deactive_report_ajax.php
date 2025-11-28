<?php
if ($_POST) {
    include_once("connection.php");
    if (isset($_POST['branch_id'])) {
        $branchId  = $_POST['branch_id'];
        $startdate = $_POST['start_date'];
        $enddate   = $_POST['end_date'];
        if ($branchId != '' || $startdate != '' || $enddate != '') {
            $sql = "SELECT s.student_id,s.dadno,s.dadwp,s.momno,s.momwp,s.selfno,s.selfwp, CONCAT(s.firstname, ' ', s.lastname) as name, b.name as branch_name, IFNULL((SELECT CONCAT(`months`, '-', `year`) as last_paid FROM fees WHERE student_id = s.student_id ORDER BY year DESC , months DESC LIMIT 0,1),'Not Found') as last_fees_paid, (SELECT count(student_id) FROM attendance WHERE student_id = s.student_id AND attend = 'P') as attendance_count FROM students s, branch b WHERE b.branch_id = s.branch_id AND s.active = 0;";
            if ($startdate != '' && $enddate != '') {
                $sql = "SELECT s.student_id,s.dadno,s.dadwp,s.momno,s.momwp,s.selfno,s.selfwp, CONCAT(s.firstname, ' ', s.lastname) as name, b.name as branch_name, IFNULL((SELECT CONCAT(`months`, '-', `year`) as last_paid FROM fees WHERE student_id = s.student_id ORDER BY year DESC , months DESC LIMIT 0,1),'Not Found') as last_fees_paid, (SELECT count(student_id) FROM attendance WHERE student_id = s.student_id AND attend = 'P' AND s.date <= '" . $enddate . "' AND s.date >= '" . $startdate . "') as attendance_count FROM students s, branch b WHERE b.branch_id = s.branch_id AND s.active = 0;";
            }
            if ($branchId != '0' && $branchId != '') {
                $sql = $sql . " AND s.branch_id = '" . $branchId . "'";
            }
            
            $result = $con->query($sql);
?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>GR No</th>
            <th>Branch</th>
            <th>Name</th>
            <th>Contact</th>
            <th>WhatsApp <i class='fab fa-whatsapp'></i></th>
            <th>Last fees paid</th>
            <th>Attendance</th>
        </tr>
    </thead>
    <tbody>
<?php
            if ($result->num_rows > 0) {
                
                while ($row = $result->fetch_assoc()) {
                    
                    
                    echo "<tr>  <td>" . $row["student_id"] . "</td>
          <td>" . $row["branch_name"] . "</td>
          <td>" . $row["name"] . "</td>
          <td>";
                    if (!empty($row["dadno"])) {
                        echo $row["dadno"] . " ";
                    }
                    if (!empty($row["momno"])) {
                        echo $row["momno"] . " ";
                    }
                    if (!empty($row["selfno"])) {
                        echo $row["selfno"] . " ";
                    }
                    echo "</td><td>";
                    if (!empty($row["dadwp"])) {
                        /*<i class='fab fa-whatsapp'></i>*/
                        echo $row["dadwp"] . "\n";
                    }
                    if (!empty($row["momwp"])) {
                        echo $row["momwp"] . "\n";
                    }
                    if (!empty($row["selfwp"])) {
                        echo $row["selfwp"] . "\n";
                    }
                    echo "</td>
          <td>" . $row["last_fees_paid"] . "</td>
          <td>" . $row["attendance_count"] . "</td>
		  </tr>";
                }
            } else {
?><tr>

                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>


<?php
            }
?>
                                                </tbody>
                                            </table>


    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- Page specific script -->
<script>
    
    
        $(function() {
            $("#example3").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
            $('#example4').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

    </script>


<?php
        }
    }
}
?>
