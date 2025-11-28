<?php
if ($_POST)
{
    include_once ("../connection.php");
    if (isset($_POST['exam_atten']) && isset($_POST['start_date']) && isset($_POST['end_date']))
    {
        $count = $_POST['exam_atten'];
        $startdate = $_POST['start_date'];
        $enddate = $_POST['end_date'];
        
        $sql = "SELECT s.student_id, CONCAT(s.firstname,' ',s.lastname) as name, (SELECT name FROM branch WHERE branch_id = s.branch_id) as branch, (SELECT name FROM belt WHERE belt_id = s.belt_id) as belt, IFNULL((SELECT DISTINCT ex.date FROM exam ex, exam_attendance ea WHERE ea.student_id = s.student_id AND ex.exam_id = ea.exam_id AND ea.attend = 'P' AND ex.isPublished = 1 ORDER BY ex.date DESC LIMIT 1),'Not Found') as last_exam_date, IFNULL((SELECT COUNT(*) FROM attendance WHERE student_id = s.student_id AND date <= '".$enddate."' AND date >= '".$startdate."' AND attend = 'P' ),'0') as atten, IFNULL(DATE_FORMAT((SELECT DATE_FORMAT(CONCAT(year,'-',months,'-01'), '%Y-%m-%d') as mdate FROM fees WHERE student_id = s.student_id ORDER BY mdate DESC LIMIT 1), '%m-%Y'),'Not Found') as last_fees_paid, s.dadno, s.dadwp, s.momno, s.momwp, s.selfno, s.selfwp FROM students s WHERE s.active = 1 AND IFNULL((SELECT COUNT(*) FROM attendance WHERE student_id = s.student_id AND date <= '".$enddate."' AND date >= '".$startdate."' AND attend = 'P' ),'0') >= ".$count;
        
        echo $sql;

        // $result = $con->query($sql);
        ?>
        <table id="example3" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>GR No</th>
                    <th>Name</th>
                    <th>Branch</th>
                    <th>Belt</th>
                    <th>Last Exam Date</th>
                    <th>Last Fees Paid</th>
                    <th>Contact</th>
                    <th>WhatsApp <i class='fab fa-whatsapp'></i></th>
                    <th>Attendance</th>
                </tr>
            </thead>
            <tbody>
        <?php
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                echo "<tr>
                        <td>" . $row["student_id"]. "</td>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["branch"] . "</td>
                        <td>" . $row["belt"] . "</td>
                        <td>" . $row["last_exam_date"] . "</td>
                        <td>" . $row["last_fees_paid"] . "</td>
                          <td>";
                          if (!empty($row["dadno"])){
                            echo $row["dadno"] . " ";
                          }
                          if (!empty($row["momno"])){
                            echo $row["momno"] . " ";
                          }
                          if (!empty($row["selfno"])){
                            echo $row["selfno"] . " ";
                          }
                          echo "</td><td>";
                          if (!empty($row["dadwp"])){ /*<i class='fab fa-whatsapp'></i>*/
                            echo $row["dadwp"] . "\n";
                          }
                          if (!empty($row["momwp"])){
                            echo $row["momwp"] . "\n";
                          }
                          if (!empty($row["selfwp"])){
                            echo $row["selfwp"] . "\n";
                          }
                          echo "</td>
                        <td>" . $row["atten"]. "</td>
                        </tr>";
            }
        }
        else
        {
            echo "<tr>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  </tr>";
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
    } else {
        echo "Incompelete Filter...";
    }
}
?>
