<?php
if ( $_POST ) {
    include_once( "connection.php" );
    if ( isset( $_POST['branch_id'] ) ) {
        $branchId = $_POST['branch_id'];
        $param = $_POST['param'];

        if ( $branchId != '' ) {
            $sql = "SELECT s.student_id,CONCAT(s.firstname,' ',s.lastname) as name,(SELECT name FROM belt WHERE belt_id = s.belt_id) as from_belt, br.name as branch_name, ef.rp_order_id, ef.date, ef.amount, (SELECT name FROM belt WHERE belt_id = ef.exam_belt_id) as to_belt FROM students s , branch br , exam_fees ef where s.branch_id = br.branch_id AND s.active = 1 AND ef.student_id = s.student_id AND ef.status = 1 AND ef.exam_id = ".$branchId;
            if ($param == 'true') {
                $sql = "SELECT s.student_id,CONCAT(s.firstname,' ',s.lastname) as name,(SELECT name FROM belt WHERE belt_id = s.belt_id) as from_belt, br.name as branch_name, ef.rp_order_id, ef.date, ef.amount, (SELECT name FROM belt WHERE belt_id = ef.exam_belt_id) as to_belt FROM students s , branch br , exam_fees ef where s.branch_id = br.branch_id AND s.active = 1 AND ef.student_id = s.student_id AND ef.status = 1 AND ef.exam_id = ".$branchId;
            } else {
                $sql = "select s.student_id,concat(s.firstname,' ',s.lastname) as name,s.dadno,s.dadwp,s.momno,s.momwp,s.selfno,s.selfwp from students s where s.student_id NOT IN (SELECT student_id from exam_fees WHERE status = 1 AND exam_id = ".$branchId.")";
            }
            $result = $con->query( $sql );
            if ($param == 'true') {
?>
<table id="example3" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
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
    echo "<tr id='".$row['fee_id']."'>  
        <td>
          <div class='text-center'>
				<button id='btnModal' value='".$row['fee_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
		  </div>
          </td>
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
            else {
                ?>
<table id="example3" class="table table-bordered table-striped">
                                                <thead>
        <tr>
            <th>GR No</th>
            <th>Name</th>
            <th>Contact</th>
            <th>WhatsApp <i class='fab fa-whatsapp'></i></th>
        </tr>
    </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
                    echo "<tr>
          <td>" . $row["student_id"] . "</td>
          <td>" . $row["name"] . "</td>
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
}
?>
