<?php
if ($_POST)
{
    include_once ("connection.php");
    if (isset($_POST['branch_id']))
    {
        $branchId = $_POST['branch_id'];
        $startdate = $_POST['start_date'];
        $enddate = $_POST['end_date'];
        $param = $_POST['param'];
        if ($param == 'true')
        {
            $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name , c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND s.active = 1 AND f.coupon_id = c.coupon_id";
            if ($branchId != '')
            {
                $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name , c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND s.active = 1 AND f.coupon_id = c.coupon_id AND s.branch_id = '" . $branchId . "';";
                if ((!empty($startdate)) && (!empty($enddate)))
                {
                    $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name , c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND s.active = 1 AND f.coupon_id = c.coupon_id AND s.branch_id = '" . $branchId . "' AND Date(CONCAT('" . $startdate . "', '-01')) <= Date(CONCAT(f.year , '-' , f.months , '-01')) AND Date(CONCAT('" . $enddate . "', '-01')) >= Date(CONCAT(f.year , '-' , f.months , '-01'))";
                }
                if ($branchId == '0')
                {
                    $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name , c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND s.active = 1 AND f.coupon_id = c.coupon_id";
                    if ((!empty($startdate)) && (!empty($enddate)))
                    {
                        $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name , c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND s.active = 1 AND f.coupon_id = c.coupon_id AND Date(CONCAT('" . $startdate . "', '-01')) <= Date(CONCAT(f.year , '-' , f.months , '-01')) AND Date(CONCAT('" . $enddate . "', '-01')) >= Date(CONCAT(f.year , '-' , f.months , '-01'))";
                    }
                }
            }
            else if ((!empty($startdate)) && (!empty($enddate)))
            {
                $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name , c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND s.active = 1 AND f.coupon_id = c.coupon_id AND Date(CONCAT('" . $startdate . "', '-01')) <= Date(CONCAT(f.year , '-' , f.months , '-01')) AND Date(CONCAT('" . $enddate . "', '-01')) >= Date(CONCAT(f.year , '-' , f.months , '-01'))";
            }

            $result = $con->query($sql);
?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Action</th>
            <th>GR No</th>
            <th>Name</th>
            <th>Branch</th>
            <th>Invoice No</th>
            <th>Date</th>
            <th>Month</th>
            <th>Year</th>
            <th>Amount</th>
            <th>Remarks</th>
            <th>Discount</th>
            <th>Additional</th>
            <th>Disabled</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($result->num_rows > 0)
            {

                while ($row = $result->fetch_assoc())
                {
                    $remarks = $row["remarks"];
                    $amount = $row["amount"];
                    if($remarks == "Admission Fees") {
                        $amount = $amount - 300;
                    }
                    echo "<tr>
        <td>
          <div class='text-center'>
				<button id='btnModal' value='" . $row['fee_id'] . "' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
		  </div>
          </td>
          <td>" . $row["student_id"] . "</td>
          <td>" . $row["name"] . "</td>
          <td>" . $row["branch_name"] . "</td>
		  <td>" . $row["fee_id"] . "</td>
          <td>" . $row["date"] . "</td>
          <td>" . $row["months"] . "</td>
          <td>" . $row["year"] . "</td>
          <td>" . $amount . "</td>
          <td>" . $remarks . "</td>
          <td>" . $row["discount"] . "</td>
          <td>";
                    if ($row["additional"] == 1)
                    {
                        echo "Yes";
                    }
                    else
                    {
                        echo "No";
                    }
                    echo "</td> <td>";
                    if ($row["disabled"] == 1)
                    {
                        echo "Yes";
                    }
                    else
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
        else
        {
            $sql = "select s.student_id,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name,s.dadno,s.dadwp,s.momno,s.momwp,s.selfno,s.selfwp from students s where s.active = 1 AND s.student_id NOT IN (SELECT student_id from fees)";
            if ($branchId != '')
            {
                $sql = "select s.student_id,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name,s.dadno,s.dadwp,s.momno,s.momwp,s.selfno,s.selfwp from students s where s.active = 1 AND s.student_id NOT IN (SELECT student_id from fees) AND s.branch_id = '" . $branchId . "';";
                if ((!empty($startdate)) && (!empty($enddate)))
                {
                    $sql = "select s.student_id,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name,s.dadno,s.dadwp,s.momno,s.momwp,s.selfno,s.selfwp from students s where s.active = 1 AND s.student_id NOT IN (SELECT student_id from fees WHERE Date(CONCAT('" . $startdate . "', '-01')) <= Date(CONCAT(year , '-' , months , '-01')) AND Date(CONCAT('" . $enddate . "', '-01')) >= Date(CONCAT(year , '-' , months , '-01')) ) AND s.branch_id = '" . $branchId . "'";
                }
                if ($branchId == '0')
                {
                    $sql = "select s.student_id,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name,s.dadno,s.dadwp,s.momno,s.momwp,s.selfno,s.selfwp from students s where s.active = 1 AND s.student_id NOT IN (SELECT student_id from fees)";
                    if ((!empty($startdate)) && (!empty($enddate)))
                    {
                        $sql = "select s.student_id,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name,s.dadno,s.dadwp,s.momno,s.momwp,s.selfno,s.selfwp from students s where s.active = 1 AND s.student_id NOT IN (SELECT student_id from fees WHERE Date(CONCAT('" . $startdate . "', '-01')) <= Date(CONCAT(year , '-' , months , '-01')) AND Date(CONCAT('" . $enddate . "', '-01')) >= Date(CONCAT(year , '-' , months , '-01')) )";
                    }
                }
            }
            else if ((!empty($startdate)) && (!empty($enddate)))
            {
                $sql = "select s.student_id,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name,s.dadno,s.dadwp,s.momno,s.momwp,s.selfno,s.selfwp from students s where s.active = 1 AND s.student_id NOT IN (SELECT student_id from fees WHERE Date(CONCAT('" . $startdate . "', '-01')) <= Date(CONCAT(year , '-' , months , '-01')) AND Date(CONCAT('" . $enddate . "', '-01')) >= Date(CONCAT(year , '-' , months , '-01')) )";
            }
            $result = $con->query($sql);
?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>GR No</th>
            <th>Name</th>
            <th>Branch</th>
            <th>Contact</th>
            <th>WhatsApp <i class='fab fa-whatsapp'></i></th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($result->num_rows > 0)
            {

                while ($row = $result->fetch_assoc())
                {
                    echo "<tr>
          <td>" . $row["student_id"] . "</td>
          <td>" . $row["name"] . "</td>
          <td>" . $row["branch_name"] . "</td>
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
