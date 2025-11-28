<?php
if ( $_POST ) {
  include_once( "connection.php" );
  if ( isset( $_POST['branch_id'] ) ) {
    $branchId = $_POST['branch_id'];
    $beltId = $_POST['belt_id'];
    $startdate = $_POST['start_date'];
    $enddate = $_POST['end_date'];
    if ( $branchId != '' || $beltId != '' || $startdate != '' || $enddate != '' ) {
      $sql = "SELECT s.*, br.name as branch_name,(select name from belt where belt_id = s.belt_id) as belt_name FROM students s , branch br where s.branch_id = br.branch_id AND s.active = 1";
      if ( $branchId != '0' && $branchId != '' ) {
        $sql = $sql." AND s.branch_id = '".$branchId."'";
      }if ( $beltId != '0' && $beltId != ''  ) {
        $sql = $sql." AND s.belt_id = '".$beltId."'";
      }      
      if ( $branchId == '0' && $beltId == '0') {
        $sql = "SELECT s.*, br.name as branch_name,(select name from belt where belt_id = s.belt_id) as belt_name FROM students s , branch br where s.branch_id = br.branch_id AND s.active = 1";
      }
      if ( $startdate != '' && $enddate != '' ) {
        $sql = $sql." AND s.doj <= '".$enddate."' AND s.doj >= '".$startdate."'";
      }
      $result = $con->query( $sql );
?>
<table id="example3" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>GR No</th>
                                                        <th>Action</th>
                                                        <th>Branch</th>
                                                        <th>Name</th>
                                                        <th>Belt</th>
                                                        <th>Std</th>
                                                        <th>Gender</th>
                                                        <th>Email</th>
                                                        <th>Contact</th>
                                                        <th>WhatsApp <i class='fab fa-whatsapp'></i></th>
                                                        <th>DOB</th>
                                                        <th>DOJ</th>
                                                        <th>Address</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
<?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
     $today = date("Y-m-d");
    $diffyear = date_diff(date_create($row["dob"]), date_create($today));
    $diff = $diffyear->format('%y');
    $tempColor = "#ffffff";
    
    if($diff <= 10)
    {
            $tempColor = "#ffffff";
    }
    else if($diff > 10 && $diff < 14)
    {
                    $tempColor = "#ffef00";
    }
    else 
    {
                  $tempColor = "#bd162c";
    }
    

    echo "<tr>  <td bgcolor='".$tempColor."'>" . $row["student_id"]. "</td>
          <td>
          <div class='text-center'>
				<a href='edit_student.php?id=".$row['student_id']."' class='edit-user action-icon' title='Edit'>
				<span class='fas fa-edit'></span>
				</a><br>
				<button id='btnModal' value='".$row['student_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deactiveModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-times'></span></button><br>
				<button id='btnModal' value='".$row['student_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
				
		  </div>
          </td>
          <td>" . $row["branch_name"]. "</td>
          <td>" . $row["firstname"] . " " . $row["lastname"] . "</td>
          <td>" . $row["belt_name"] . "</td>
          <td>" . $row["std"] . "</td>
		  <td>";
          if ($row["gender"] == 1){
            echo "Male";
          } else {
            echo "Female";
          }
          echo "</td>
		  <td>" . $row["email"]. "</td>
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
          <td>" . $row["dob"]. "</td>
          <td>" . $row["doj"]. "</td>
          <td>" . $row["address"]. " " . $row["pincode"] . "</td>		  
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
