<?php
if ($_POST)
{
    include_once ("connection.php");
    if (isset($_POST['branch_id']))
    {
        $branchId = $_POST['branch_id'];
        $startdate = $_POST['start_date'];
        $enddate = $_POST['end_date'];
        if (true)
        {
            $sql = "SELECT a.*,CONCAT(s.firstname,' ',s.lastname) as name, (SELECT name from branch WHERE branch_id = a.branch_id) as branch_name, (SELECT CONCAT(firstname,' ',lastname) from users WHERE user_id = a.user_id) as ins_name FROM attendance a, students s WHERE a.student_id = s.student_id AND s.active = 1";
            if ($branchId != '')
            {
                $sql = "SELECT a.*,CONCAT(s.firstname,' ',s.lastname) as name, (SELECT name from branch WHERE branch_id = a.branch_id) as branch_name, (SELECT CONCAT(firstname,' ',lastname) from users WHERE user_id = a.user_id) as ins_name FROM attendance a, students s WHERE a.student_id = s.student_id AND a.branch_id = '" . $branchId . "' AND s.active = 1;";
                if ((!empty($startdate)) && (!empty($enddate)))
                {
                    $sql = "SELECT a.*,CONCAT(s.firstname,' ',s.lastname) as name, (SELECT name from branch WHERE branch_id = a.branch_id) as branch_name, (SELECT CONCAT(firstname,' ',lastname) from users WHERE user_id = a.user_id) as ins_name FROM attendance a, students s WHERE a.student_id = s.student_id AND a.branch_id = '" . $branchId . "'  AND a.date >= '" . $startdate . "' AND a.date <= '" . $enddate . "' AND s.active = 1;";
                }
                if ($branchId == '0')
                {
                    $sql = "SELECT a.*,CONCAT(s.firstname,' ',s.lastname) as name, (SELECT name from branch WHERE branch_id = a.branch_id) as branch_name, (SELECT CONCAT(firstname,' ',lastname) from users WHERE user_id = a.user_id) as ins_name FROM attendance a, students s WHERE a.student_id = s.student_id AND s.active = 1";
                    if ((!empty($startdate)) && (!empty($enddate)))
                    {
                        $sql = "SELECT a.*,CONCAT(s.firstname,' ',s.lastname) as name, (SELECT name from branch WHERE branch_id = a.branch_id) as branch_name, (SELECT CONCAT(firstname,' ',lastname) from users WHERE user_id = a.user_id) as ins_name FROM attendance a, students s WHERE a.student_id = s.student_id AND a.date >= '" . $startdate . "' AND a.date <= '" . $enddate . "' AND s.active = 1;";
                    }
                }
            }
            else if ((!empty($startdate)) && (!empty($enddate)))
            {
                $sql = "SELECT a.*,CONCAT(s.firstname,' ',s.lastname) as name, (SELECT name from branch WHERE branch_id = a.branch_id) as branch_name, (SELECT CONCAT(firstname,' ',lastname) from users WHERE user_id = a.user_id) as ins_name FROM attendance a, students s WHERE a.student_id = s.student_id AND a.date >= '" . $startdate . "' AND a.date <= '" . $enddate . "' AND s.active = 1;";
            }

            $result = $con->query($sql);
?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>GR No</th>
            <th>Name</th>
            <th>Branch</th>
            <th>Date</th>
            <th>Attend</th>
            <th>Instructor</th>
            <th>Additional</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($result->num_rows > 0)
            {

                while ($row = $result->fetch_assoc())
                {
                    echo "<tr>
          <td>" . $row["attendance_id"]. "</td>
          <td>" . $row["student_id"]. "</td>
          <td>" . $row["name"] . "</td>
		  <td>" . $row["branch_name"]. "</td>
          <td>" . $row["date"]. "</td>
          <td>" . $row["attend"]. "</td>
          <td>" . $row["ins_name"]. "</td>
          <td>";
          if ($row["is_additional"] == 1)
          {
              echo "Yes";
          } else 
          {
              echo "No"; 
          }
          echo "</td> 
		  </tr>";
                }
            }
            else
            {
?><tr>

            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr><?php
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
