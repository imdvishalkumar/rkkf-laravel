<?php
if ($_POST)
{
    include_once ("../connection.php");
    if (isset($_POST['start_date']) && isset($_POST['end_date']))
    {
        $startdate = $_POST['start_date'];
        $enddate = $_POST['end_date'];
        if (true)
        {
//            $sql = "select s.student_id as GRNO, CONCAT(s.firstname, ' ', s.lastname) as name, o.name_var, '-' as feesmonth, (o.p_price*o.qty) as totalprice,CONCAT('ORD_',o.counter) as orderid, o.date from students s, orders o where s.student_id = o.student_id AND o.date >= '" . $startdate . "' AND o.date <= '" . $enddate . "' GROUP BY o.rp_order_id UNION select s.student_id as GRNO, CONCAT(s.firstname, ' ', s.lastname) as name, '-' as name_var, CONCAT(f.months, '-', f.year) as feesmonth, amount as totalprice, CONCAT('FEE_',f.fee_id) as orderid, f.date from students s, fees f where s.student_id = f.student_id AND f.date >= '" . $startdate . "' AND f.date <= '" . $enddate . "'";
            
            if ((!empty($startdate)) && (!empty($enddate)))
            {
                $sql = "select s.student_id as GRNO, CONCAT(s.firstname, ' ', s.lastname) as name, o.name_var, '-' as feesmonth, (o.p_price*o.qty) as totalprice,CONCAT('ORD_',o.counter) as orderid, o.date from students s, orders o where o.status = 1 AND s.active = 1 AND s.student_id = o.student_id AND o.date >= '" . $startdate . "' AND o.date <= '" . $enddate . "' GROUP BY o.rp_order_id UNION select s.student_id as GRNO, CONCAT(s.firstname, ' ', s.lastname) as name, '-' as name_var, CONCAT(f.months, '-', f.year) as feesmonth, amount as totalprice, CONCAT('FEE_',f.fee_id) as orderid, f.date from students s, fees f where s.student_id = f.student_id AND s.active = 1 AND f.date >= '" . $startdate . "' AND f.date <= '" . $enddate . "'";
            }
            if ($mode == 'app') {
                $sql = $sql." AND f.mode != 'cash'";
            } else if ($mode == 'cash') {
                $sql = "select s.student_id as GRNO, CONCAT(s.firstname, ' ', s.lastname) as name, '-' as name_var, CONCAT(f.months, '-', f.year) as feesmonth, amount as totalprice, CONCAT('FEE_',f.fee_id) as orderid, f.date from students s, fees f where s.student_id = f.student_id AND s.active = 1 AND f.mode = 'cash' AND f.date >= '" . $startdate . "' AND f.date <= '" . $enddate . "'";
            }
            $result = $con->query($sql);
?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>GR No</th>
            <th>Name</th>
            <th>Product Name</th>
            <th>Fee Month</th>
            <th>Total</th>
            <th>Order_id</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($result->num_rows > 0)
            {

                while ($row = $result->fetch_assoc())
                {
                    echo "<tr>
          <td>" . $row["GRNO"] . "</td>
          <td>" . $row["name"] . "</td>
		  <td>" . $row["name_var"] . "</td>
          <td>" . $row["feesmonth"] . "</td>
          <td>" . $row["totalprice"] . "</td>
          <td>" . $row["orderid"] . "</td>
          <td>" . $row["date"] . "</td>
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
