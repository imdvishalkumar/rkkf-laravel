<?php
if ( $_POST ) {
    include_once( "connection.php" );
    if ( isset( $_POST['branch_id'] ) ) {
        $branchId = $_POST['branch_id'];

        if ( $branchId != '' ) {
            $sql = "SELECT s.student_id,s.active,CONCAT(s.firstname,' ',s.lastname) as name,(SELECT DISTINCT name FROM belt WHERE belt_id = s.belt_id LIMIT 1) as from_belt, br.name as branch_name, ef.rp_order_id, ef.date, ef.amount, (SELECT DISTINCT name FROM belt WHERE belt_id = ef.exam_belt_id LIMIT 1) as to_belt, ea.attend as attend, certificate_no as certificate FROM students s , branch br , exam_fees ef, exam_attendance ea where s.branch_id = br.branch_id AND ef.student_id = s.student_id AND ef.status = 1 AND ea.exam_id = ef.exam_id and ea.student_id = s.student_id AND ef.exam_id = ".$branchId;
            $result = $con->query( $sql );
?>
<table id="example3" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>GR No</th>
                                                        <th>Active</th>
                                                        <th>Name</th>
                                                        <th>Branch</th>
                                                        <th>Date</th>
                                                        <th>From Belt</th>
                                                        <th>To Belt</th>
                                                        <th>Attend</th>
                                                        <th>Certificate No</th>
                                                        <th>Amount</th>
                                                        <th>Order Id</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
                if ($row["active"] == 0) {
              echo "<tr bgcolor= '#FF0000'>";
          } else {
              echo "<tr>";
          }

    echo "<td>" . $row["student_id"]. "</td>
          <td>";
          if ($row["active"] == 0) {
              echo "No";
          } else {
              echo "Yes";
          }
          echo "</td>
          <td>" . $row["name"] . "</td>
		  <td>" . $row["branch_name"]. "</td>
          <td>" . $row["date"]. "</td>
          <td>" . $row["from_belt"]. "</td>
          <td>" . $row["to_belt"]. "</td>
          <td>" . $row["attend"]. "</td>
          <td>" . $row["certificate"]. "</td>
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
