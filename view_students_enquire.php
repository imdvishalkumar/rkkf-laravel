<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page = "view_enqurie";
include_once("connection.php");
include_once("page_title.php");

$submitClick   = isset($_POST['submit']);
$delClick = isset($_POST['delete']);
$activeClick = isset($_POST['active']);
if ($activeClick) {
    $id     = $_POST['enquire_id'];
    $query  = "select selfno, direct_entry, amount, email, order_id FROM enquire WHERE enquire_id = '" . $id . "' and inserted_status = '0';";
    $result = mysqli_query($con, $query);
    if ($result) {
        $row    = $result->fetch_assoc();
        $smno   = $row['selfno'];
        $email   = $row['email'];
        $direct_entry   = $row['direct_entry'];
        $_SESSION['passedEmail'] = $email;
        $_SESSION['passedPassword'] = $smno;
        $hash   = password_hash($smno, PASSWORD_DEFAULT);
        $query  = "INSERT INTO `students` (`student_id`, `firstname`, `lastname`, `gender`, `email`, `password`, `belt_id`, `dadno`, `dadwp`, `momno`, `momwp`, `selfno`, `selfwp`, `dob`, `doj`, `address`, `branch_id`, `pincode`, `active`) SELECT NULL,firstname,lastname,gender,email,'" . $hash . "',1,dadno,dadwp,momno,momwp,selfno,selfwp,dob,doj,address,branch_id,pincode,1 FROM enquire WHERE enquire_id = '" . $id . "';";
        $result = mysqli_query($con, $query);
        if ($result) {
            $s_id   = mysqli_insert_id($con);
            $query  = "UPDATE `enquire` SET `inserted_status` = '1' WHERE `enquire`.`enquire_id` = '" . $id . "';";
            $result = mysqli_query($con, $query);
            if ($result) {
                if ($direct_entry == '1') {
                    $monthAllCheckBool = true;
                        $_SESSION["studentAdded"] = "yes";
                        header("Location: student_added.php");
                        exit();
                        $alertMsg = "
                        <div class='col-md-12'>
                        <div class='alert alert-success alert-dismissible'>
                          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                          <h5><i class='icon fas fa-check'></i> Alert!</h5>
                          Student Added Successfully.
                        </div></div>";
                } else {
                    $currentDate  = date("Y-m-d");
                    $fees         = $row['amount'];
                    $order_id     = $row['order_id'];
                    $currentYear  = date("Y");
                    $month = date("m");
                    $feeQuery     = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode,remarks) values ($s_id,$month,'$currentYear','$currentDate','$fees','1','0','0','$order_id','Admission Fees')";
                    if (mysqli_query($con, $feeQuery)) {
                        $monthAllCheckBool = true;
                        $_SESSION["studentAdded"] = "yes";
                        header("Location: student_added.php");
                        exit();
                        $alertMsg = "
                        <div class='col-md-12'>
                        <div class='alert alert-success alert-dismissible'>
                          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                          <h5><i class='icon fas fa-check'></i> Alert!</h5>
                          Student Added Successfully.
                        </div></div>";
                    } else {   
                        echo "something went wrong while inserting fees.";
                    }   
                }
            } else {
                echo "something went wrong while updating enqurie.";
            }
        } else {
            echo "something went wrong while inserting student.";
        }
    } else {
        echo "something went wrong while entering password.";
    }
    if ($result) {
        $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-success alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-check'></i> Alert!</h5>
                      Student Activated Successfully.
                    </div></div>";
    } else {
        $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                      Error Activating Student.
                    </div></div>";
    }
}
if ($delClick) {
     $id     = $_POST['del_enquire_id'];
    $query  = "DELETE FROM enquire WHERE enquire_id = '" . $id . "';";
    $result = mysqli_query($con, $query);
    if ($result) {
        $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-success alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-check'></i> Alert!</h5>
                      Student Deleted Successfully.
                    </div></div>";
    } else {
        $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                      Error Deleting Student.
                    </div></div>";
    }
}
$sql    = "SELECT e.enquire_id, e.direct_entry, e.firstname, e.lastname, e.gender, e.email, e.dob, e.doj, e.dadno, e.dadwp, e.momno, e.momwp, e.selfno, e.selfwp, e.address,(select name from branch where branch_id  = e.branch_id) as branch_name, e.pincode, e.amount FROM enquire e WHERE e.inserted_status = 0 AND (e.payment_status = 1 OR e.direct_entry = 1)";
$result = $con->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <?php include_once("head_link.php"); ?>

</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">
    <div class="wrapper">

        <?php include_once("navbar.php"); ?>
        <?php include_once("side_menu.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <?php include_once("content_header.php"); ?>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <?php
		if ($delClick){
			echo $alertMsg;
		}
		if ($activeClick){
			echo $alertMsg;
		}
		
		?>
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Enquire Details</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body" id="infoDiv">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Branch</th>
                                                        <th>Name</th>
                                                        <th>Gender</th>
                                                        <th>Email</th>
                                                        <th>Direct Entry</th>
                                                        <th>Contact</th>
                                                        <th>WhatsApp <i class='fab fa-whatsapp'></i></th>
                                                        <th>DOB</th>
                                                        <th>Date of Enqurie</th>
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
    $enqId = $row['enquire_id'];

    echo "<tr>
          <td>
          <div class='text-center'>
				<button id='btnModal' value='".$enqId."' onclick='passId(this.value)' data-toggle='modal'  data-target='#activeModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-check'></span></button>
				
				<button id='btnModal' value='".$enqId."' onclick='passDelId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
		  </div>
          </td>
          <td>" . $row["branch_name"]. "</td>
          <td>" . $row["firstname"] . " " . $row["lastname"] . "</td>
		  <td>";
          if ($row["gender"] == 1){
            echo "Male";
          } else {
            echo "Female";
          }
          echo "</td>
		  <td>" . $row["email"]. "</td>
		  <td>";
          if ($row["direct_entry"] == 1){
            echo "Yes";
          } else {
            echo "No";
          }
          echo "</td>
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
                                                    </tr><?php
}
				  ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->

                                </div>
                            </div>
                            <!-- Button trigger modal -->


                            <!-- Modal -->
                            <div class="modal fade" id="activeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Activate User.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="enquire_id" id="enquire_id" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="active" class="btn btn-danger">Activate</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--Delete Modal -->
                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete User.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="del_enquire_id" id="del_enquire_id" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include_once("footer.php"); ?>



        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)

    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
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
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
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

    <script type="text/javascript">
        function passId(val) {
            $("#enquire_id").val(val); //set the id to the input on the modal
        }
        function passDelId(val) {
            $("#del_enquire_id").val(val); //set the id to the input on the modal
        }

    </script>

</body>

</html>
