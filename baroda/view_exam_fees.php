<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="view_fees";
include_once("connection.php");
include_once("page_title.php");

$delClick = isset($_POST['delete']);
if($delClick) {
	$id=$_POST['fee_id'];
    $query = "delete from fees WHERE fee_id=$id";
    $result = mysqli_query($con,$query);
    if($result){
                $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-success alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-check'></i> Alert!</h5>
                      Fee Deleted Successfully.
                    </div></div>";
    } else {
                    $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                      Error deleting Fee.
                    </div></div>";
    }
}

	$sql = "SELECT ef.exam_fees_id ,CONCAT(s.firstname, ' ' , s.lastname) as name, s.student_id,(SELECT name FROM exam WHERE exam_id = ef.exam_id ) as exam_name, IFNULL((SELECT exam_attendance_id FROM exam_attendance WHERE exam_id = ef.exam_id AND student_id = s.student_id ),0) as found, IFNULL((SELECT ef.mode WHERE ef.mode != 'razorpay'),1) as paid_online,ef.amount FROM students s, exam_fees ef WHERE s.student_id = ef.student_id AND ef.status = 1 ORDER BY ef.exam_fees_id DESC";
	$result = $con->query($sql);

	$queryForBranch = "select * from branch;";
	$resultForBranch = $con->query($queryForBranch);

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
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Exam Fees Details</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body" id="infoDiv">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Exam Fee No.</th>
                                                        <th>Action</th>
                                                        <th>GR No</th>
                                                        <th>Name</th>
                                                        <th>Exam Name</th>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr>
          <td>" . $row["exam_fees_id"]. "</td>
        <td>"; 
          if ($row["found"] == 0 && $row[""] != 1)
          {
              echo "<div class='text-center'>
				<button id='btnModal' value='".$row['fee_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
		  </div>";
          } else 
          {
              echo "No"; 
          }
          echo "
          
          </td>
          <td>" . $row["student_id"]. "</td>
          <td>" . $row["name"] . "</td>
          <td>" . $row["exam_name"] . "</td>
          <td>" . $row["date"]. "</td>
          <td>" . $row["amount"]. "</td>
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
                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Fees.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="fee_id" id="fee_id" hidden />

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
    <script>
        $(function() {
            //DOB range picker
            $('#startdate').datetimepicker({
                format: "yyyy-MM",
                viewMode: "months",
                minViewMode: "months"
            });
            $('#enddate').datetimepicker({
                format: "yyyy-MM",
                viewMode: "months",
                minViewMode: "months"
            });

            $("#startdate").on("change.datetimepicker", ({
                date,
                oldDate
            }) => {
                get_studentInfo();
            });

            $("#enddate").on("change.datetimepicker", ({
                date,
                oldDate
            }) => {
                get_studentInfo();
            });


        })

    </script>

    <script type="text/javascript">
        function passId(val) {
            $("#fee_id").val(val); //set the id to the input on the modal
        }

    </script>
    <script>
        function get_studentInfo() { // Call to ajax function
            var branchId = $('#branch_id').val();
            var startdate = $("input[name=startdate]").val();
            var enddate = $("input[name=enddate]").val();
            var param = $("#customSwitch1").is(':checked');
            $.ajax({
                type: "POST",
                url: "view_fees_ajax.php", // Name of the php files
                data: {
                    branch_id: branchId,
                    start_date: startdate,
                    end_date: enddate,
                    param: param
                },
                success: function(html) {
                    $("#infoDiv").html(html);
                }
            });
        }
        $('#customSwitch1').on('switchChange.bootstrapSwitch', function (event, state) {
            get_studentInfo();
        }); 


    </script>

</body>

</html>
