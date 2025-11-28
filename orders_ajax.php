<?php
if ($_POST)
{
    include_once ("connection.php");
    if (isset($_POST['param']))
    {
        $param = $_POST['param'];
        if ($param == 'true')
        {
            $sql = "SELECT @a=@a+1 serial_number, o.order_id,o.name_var,o.qty,o.p_price,o.date,o.status,o.rp_order_id,CONCAT(s.firstname,' ',s.lastname) as student_name, s.student_id,s.email,(SELECT name FROM branch WHERE branch_id = s.branch_id) as branch_name,o.counter, o.flag_delivered, o.viewed FROM orders o, students s WHERE o.student_id = s.student_id AND o.status = 1 ORDER BY o.order_id DESC";
        } else {
            $sql = "SELECT @a=@a+1 serial_number, o.order_id,o.name_var,o.qty,o.p_price,o.date,o.status,o.rp_order_id,CONCAT(s.firstname,' ',s.lastname) as student_name, s.student_id,s.email,(SELECT name FROM branch WHERE branch_id = s.branch_id) as branch_name,o.counter, o.flag_delivered, o.viewed FROM orders o, students s WHERE o.student_id = s.student_id AND o.status != 1 ORDER BY o.order_id DESC";
            
        }
        $result = $con->query($sql);
?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
        	<th>Sr</th>
        	<th>Order No.</th>
        	<th>Gr No.</th>
        	<th>Student Name</th>
        	<th>Student Branch</th>
        	<th>Product Name</th>
        	<th>Quantity</th>
        	<th>Price</th>
        	<th>Date</th>
        	<th>Status</th>
        	<th>Send Mail</th>
        	<th>Viewed</th>
        	<th>RazorPay Order Id</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($result->num_rows > 0)
            {

                while ($row = $result->fetch_assoc())
                {
                    echo "<tr>  <td>" . ++$i . "</td>
                		  <td>" . $row["counter"]. "</td>
                		  <td>" . $row["student_id"]. "</td>
                		  <td>" . $row["student_name"]. "</td>
                		  <td>" . $row["branch_name"]. "</td>
                		  <td>" . $row["name_var"]. "</td>
                		  <td>" . $row["qty"]. "</td>		  
                		  <td>" . $row["p_price"]. "</td>		  
                		  <td>" . $row["date"]. "</td>		  
                		  <td>";
                      if ($row["status"] == 1) {
                          echo "<span class='float-right badge bg-success'>Success</span>";
                      } else {
                          echo "<span class='float-right badge bg-danger'>Failed</span>";
                      }
                          echo "</td>";
                        
                        
                        
                        
                      if ($row["flag_delivered"] == 1 )
                      {
                        echo "<td><span class='float-right badge bg-success'>Delivered</span></td>";
                      }
                      else if($row["status"] == 1)
                      {  
                          
                          echo "
                		  <td><a href='order_delivered_mail.php?name=".$row["student_name"]."&order_date=".$row["date"]."&email=".$row["email"]."&order_no=".$row["counter"]."' class='edit-user action-icon' title='Edit'>
                				<span class='fas fa-paper-plane'></span>
                				</a></td>";
                		
                      }
                      else 
                      {
                         echo "<td><span>-</span></td>";
                      }
                
                      if ($row["viewed"] == 1 )
                      {
                        echo "<td><span class='float-right badge bg-success'>Viewed</span></td>";
                      }
                      else
                      {  
                          
                          echo "
                		  <td><a href='order_viewed.php?order_no=".$row["counter"]."' class='edit-user action-icon' title='Edit'>
                				<span class='far fa-eye'></span>
                				</a></td>";
                		
                      }
                		
                		
                				
                				echo"
                				<td>". $row["rp_order_id"]. "</td>	
                		 
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
}
?>
