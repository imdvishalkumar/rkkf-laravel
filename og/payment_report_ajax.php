<?php
if ($_POST)
{
    include_once ("../connection.php");
    if (isset($_POST['type']))
    {
        $type = $_POST['type'];
        $mode = $_POST['mode'];
        $startdate = $_POST['start_date'];
        $enddate = $_POST['end_date'];
        if ($type == 'fees')
        {
            $sql = "select f.* ,f.mode, br.name as bname, concat(s.firstname,' ',s.lastname) as name , c.amount as discount from fees f, students s, coupon c, branch br WHERE f.student_id = s.student_id AND f.coupon_id = c.coupon_id AND s.branch_id = br.branch_id";
            if ((!empty($startdate)) && (!empty($enddate)))
            {
                $sql = "select f.* ,f.mode, br.name as bname,concat(s.firstname,' ',s.lastname) as name , c.amount as discount from fees f, students s, coupon c, branch br WHERE f.student_id = s.student_id AND f.coupon_id = c.coupon_id AND s.branch_id = br.branch_id AND f.date >= '" . $startdate . "' AND f.date <= '" . $enddate . "'";
            }
            if ($mode == 'app') {
                $sql = $sql." AND f.mode != 'cash'";
            } else {
                $sql = $sql." AND f.mode = 'cash'";
            }

            $result = $con->query($sql);
?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>GR No</th>
            <th>Name</th>
            <th>Branch</th>
            <th>Invoice No</th>
            <th>RP OrderID</th>
            <th>Remarks</th>
            <th>Date</th>
            <th>Month</th>
            <th>Year</th>
            <th>Amount</th>
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
                    echo "<tr>
          <td>" . $row["student_id"] . "</td>
          <td>" . $row["name"] . "</td>
          <td>" . $row["bname"] . "</td>
		  <td>" . $row["fee_id"] . "</td>
		   <td>" . $row["mode"] . "</td>
		   <td>" . $row["remarks"] . "</td>
          <td>" . $row["date"] . "</td>
          <td>" . $row["months"] . "</td>
          <td>" . $row["year"] . "</td>
          <td>" . $row["amount"] . "</td>
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
        else if ($type == 'order')
        {
            $sql = "SELECT o.order_id,o.name_var,o.qty,o.p_price,o.date,o.status,o.rp_order_id,CONCAT(s.firstname,' ',s.lastname) as student_name, s.student_id,s.email,(SELECT name FROM branch WHERE branch_id = s.branch_id) as branch_name,o.counter FROM orders o, students s WHERE o.student_id = s.student_id AND o.status = 1";
            
            if ((!empty($startdate)) && (!empty($enddate)))
            {
                $sql = "SELECT o.order_id,o.name_var,o.qty,o.p_price,o.date,o.status,o.rp_order_id,CONCAT(s.firstname,' ',s.lastname) as student_name, s.student_id,s.email,(SELECT name FROM branch WHERE branch_id = s.branch_id) as branch_name,o.counter FROM orders o, students s WHERE o.student_id = s.student_id AND o.status = 1 AND o.date >= '" . $startdate . "' AND o.date <= '" . $enddate . "'";
            }
            
            if ($mode == 'app') {
                $sql = $sql." AND o.rp_order_id LIKE '%order%'";
            } else {
                $sql = $sql." AND o.rp_order_id NOT LIKE '%order%'";
            }
            
            $sql = $sql." ORDER BY o.order_id DESC";
            
            $result = $con->query($sql);
?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Order No.</th>
            <th>Gr No.</th>
            <th>Student Name</th>
            <th>Student Branch</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Date</th>
            <th>RazorPay Order Id</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($result->num_rows > 0)
            {

                while ($row = $result->fetch_assoc())
                {
                    echo "<tr>
          <td>" . $row["counter"]. "</td>
		  <td>" . $row["student_id"]. "</td>
		  <td>" . $row["student_name"]. "</td>
		  <td>" . $row["branch_name"]. "</td>
		  <td>" . $row["name_var"]. "</td>
		  <td>" . $row["qty"]. "</td>		  
		  <td>" . $row["p_price"]. "</td>		  
		  <td>" . $row["date"]. "</td>
          <td>" . $row["rp_order_id"]. "</td>
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
        else if ($type == 'exam')
        {
            $sql = "SELECT s.student_id,CONCAT(s.firstname,' ',s.lastname) as name,(SELECT name FROM belt WHERE belt_id = s.belt_id) as from_belt, br.name as branch_name, ef.rp_order_id, ef.date, ef.amount, (SELECT name FROM belt WHERE belt_id = ef.exam_belt_id) as to_belt FROM students s , branch br , exam_fees ef where s.branch_id = br.branch_id AND ef.student_id = s.student_id AND ef.status = 1";
            
            if ((!empty($startdate)) && (!empty($enddate)))
            {
                $sql = "SELECT s.student_id,CONCAT(s.firstname,' ',s.lastname) as name,(SELECT name FROM belt WHERE belt_id = s.belt_id) as from_belt, br.name as branch_name, ef.rp_order_id, ef.date, ef.amount, (SELECT name FROM belt WHERE belt_id = ef.exam_belt_id) as to_belt FROM students s , branch br , exam_fees ef where s.branch_id = br.branch_id AND ef.student_id = s.student_id AND ef.status = 1 AND ef.date >= '" . $startdate . "' AND ef.date <= '" . $enddate . "'";
            }
            
            if ($mode == 'app') {
                $sql = $sql." AND ef.rp_order_id LIKE '%order%'";
            } else {
                $sql = $sql." AND ef.rp_order_id NOT LIKE '%order%'";
            }

            $result = $con->query($sql);
?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>GR No</th>
            <th>Name</th>
            <th>Branch</th>
            <th>Date</th>
            <th>From Belt</th>
            <th>To Belt</th>
            <th>Amount</th>
            <th>Order Id</th>
        </tr>
    </thead>
    <tbody>
        <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr>
          <td>" . $row["student_id"]. "</td>
          <td>" . $row["name"] . "</td>
		  <td>" . $row["branch_name"]. "</td>
          <td>" . $row["date"]. "</td>
          <td>" . $row["from_belt"]. "</td>
          <td>" . $row["to_belt"]. "</td>
          <td>" . $row["amount"]. "</td>
          <td>" . $row["rp_order_id"]. "</td>
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
        else if ($type == 'event')
        {
            $sql = "SELECT s.student_id,CONCAT(s.firstname,' ',s.lastname) as name, br.name as branch_name, ef.rp_order_id, ef.date, ef.amount FROM students s , branch br , event_fees ef where s.branch_id = br.branch_id AND ef.student_id = s.student_id AND ef.status = 1";
            
            if ((!empty($startdate)) && (!empty($enddate)))
            {
                $sql = "SELECT s.student_id,CONCAT(s.firstname,' ',s.lastname) as name, br.name as branch_name, ef.rp_order_id, ef.date, ef.amount FROM students s , branch br , event_fees ef where s.branch_id = br.branch_id AND ef.student_id = s.student_id AND ef.status = 1 AND ef.date >= '" . $startdate . "' AND ef.date <= '" . $enddate . "'";
            }
            $result = $con->query($sql);
?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>GR No</th>
            <th>Name</th>
            <th>Branch</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Order Id</th>
        </tr>
    </thead>
    <tbody>
        <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
   echo "<tr>
          <td>" . $row["student_id"]. "</td>
          <td>" . $row["name"] . "</td>
		  <td>" . $row["branch_name"]. "</td>
          <td>" . $row["date"]. "</td>
          <td>" . $row["amount"]. "</td>
          <td>" . $row["rp_order_id"]. "</td>
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
