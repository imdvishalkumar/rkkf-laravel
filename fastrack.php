<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="fastrack";
include_once("connection.php");
include_once("page_title.php");

$submitClick = isset($_POST['submit']);
$delClick = isset($_POST['delete']);

if($submitClick) {
    $gr_no=$_POST['grno'];
    $s_student=$_POST['disable_student_id'];
    $dob = $_POST['dob'];
    $date = str_replace('/', '-', $dob);
    $dob = date('Y-m-d', strtotime($date));
    $doj = $_POST['doj'];
    $date = str_replace('/', '-', $doj);
    $doj = date('Y-m-d', strtotime($date));
    $c_belt=$_POST['current_belt_id'];
    $s_belt_id=$_POST['selected_belt_id'];
    $diff_belt=$_POST['diff_belt'];
    $cal_months=$_POST['cal_months'];
    $cal_totalFees=$_POST['cal_totalFees'];
    $totalHours=$_POST['totalHours'];

    $feeQuery = "INSERT INTO fastrack (student_id, from_belt_id, to_belt_id, from_date, to_date, months_count, total_fees, total_hours) values (".$s_student.",".$c_belt.",".$s_belt_id.",'".$dob."','".$doj."',".$cal_months.",".$cal_totalFees.",".$totalHours.")";
    $result = mysqli_query($con,$feeQuery);
    if($result)
    {
        $alertMsg = "
            <div class='col-md-12'>
            <div class='alert alert-success alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h5><i class='icon fas fa-check'></i> Alert!</h5>
                Fastrack Inserted Successfully.
            </div></div>";
    } else
    {
        $alertMsg = "
            <div class='col-md-12'>
            <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                Error while Adding Fastrack.
            </div></div>";
    }
    
}
if($delClick) {
    $id=$_POST['student_id'];
    $query = "DELETE FROM fastrack WHERE fastrack_id=$id";
    $result = mysqli_query($con,$query);
    if($result) {
			$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Fastrack Deleted Successfully.
                </div></div>";
    } else {
				$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error Deleting Fastrack.
                </div></div>";
    }
}
	$sql = "SELECT f.fastrack_id ,f.student_id , CONCAT(s.firstname , ' ' , s.lastname) as name, b.name as from_belt,(select name from belt where belt_id = f.to_belt_id) as to_belt_name, f.from_date, f.to_date, TIMESTAMPDIFF(MONTH, f.from_date, f.to_date) as months_diff, f.total_fees, f.total_hours, (SELECT IFNULL(SUM(hours), 0) from fastrack_attendance where student_id = f.student_id AND student_id = s.student_id) as fast_hrs FROM fastrack f , students s , belt b WHERE s.student_id = f.student_id AND b.belt_id = f.from_belt_id";
	$resultTable = $con->query($sql);
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
                         if ($submitClick)
		 	echo $alertMsg;
		 if ($delClick){
		 	echo $alertMsg;
		 }		
		?>
                        <div class="col-md-12">
                            <div class="card card-primary collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Enable Fastrack</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body" style="display: none;">
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Enter GrNO OR Name</label>
                                            <input type="text" class="form-control" name="grno" id="grno" placeholder="" onchange="get_studentInfo()" required>
                                        </div>
                                        <div id="infoDiv" class="form-group">

                                        </div>
                                        <div id="infoDiv2" class="form-group">

                                        </div>
                                        <div id="infoDiv3" class="form-group">

                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Fastrack Details</h3>


                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>GR No</th>
                                                        <th>Action</th>
                                                        <th>Name</th>
                                                        <th>From Belt</th>
                                                        <th>To Belt</th>
                                                        <th>From Date</th>
                                                        <th>To Date</th>
                                                        <th>Amount</th>
                                                        <th>Monthly Fees</th>
                                                        <th>Hours</th>
                                                        <th>Attended Hours</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($resultTable->num_rows > 0) {

  while($row = $resultTable->fetch_assoc()) {
    echo "<tr>  <td>" . $row["student_id"]. "</td>
          <td>
          <div class='text-center'>
				<button id='btnModal' value='".$row['fastrack_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
		  </div>
          </td>
		  <td>" . $row["name"] . "</td>
		  <td>" . $row["from_belt"]. "</td>
		  <td>" . $row["to_belt_name"]. "</td>
          <td>" . $row["from_date"]. "</td>
          <td>" . $row["to_date"]. "</td>
          <td>" . $row["total_fees"]. "</td>
         <td>" . round($row["total_fees"]/($row["months_diff"]+1)). "</td>
          <td>" . $row["total_hours"]. "</td>
          <td>" . $row["fast_hrs"]. "</td>
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
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Fastrack.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="student_id" id="student_id" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="delete" class="btn btn-success">Delete</button>
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
            //Date range picker
            $('#dobdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            //DOB range picker
            $('#dojdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        })

    </script>
    
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
            $("#student_id").val(val); //set the id to the input on the modal
        }

    </script>
    <script type="text/javascript">
        $(function() {
            //DOB range picker
            $('#dojdate').datetimepicker({
                format: "MM/yyyy",
                viewMode: "months",
                minViewMode: "months"
            });
        })

    </script>
    <script>
        $(function() {
            //Date range picker
            $('#dobdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            //DOB range picker
            $('#dojdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        })

    </script>
    <script type="text/javascript">
        function feesInfo() { // Call to ajax function
            var student_id = $('#student_id').val();
            var dataString = "student_id=" + student_id;
            $.ajax({
                type: "POST",
                url: "getstudents.php", // Name of the php files
                data: dataString,
                success: function(html) {
                    $("#infoDiv2").html(html);
                }
            });
        }

    </script>
    <script>
        var timer = null;
        $("#grno").keydown(function() {
            clearTimeout(timer);
            timer = setTimeout(get_studentInfo, 0)
        });

        function get_studentInfo() { // Call to ajax function
            var grno = $('#grno').val();
            var dataString = "grno=" + grno;
            student_id
            $.ajax({
                type: "POST",
                url: "getstudentsFastrack.php", // Name of the php files
                data: dataString,
                success: function(html) {
                    $("#infoDiv").html(html);
                    $("#infoDiv2").html("");
                }
            });
        }
        function feesInfo() { // Call to ajax function
            var grno = $('#disable_student_id').val();
            var dataString = "disable_student_id=" + grno;
            student_id
            $.ajax({
                type: "POST",
                url: "getstudentsFastrack.php", // Name of the php files
                data: dataString,
                success: function(html) {
                    $("#infoDiv2").html(html);
                }
            });
        }
        function calculateFeesForBelt() { // Call to ajax function
            var belt_id = $('#current_belt_id').val();
            var s_belt_id = $('#selected_belt_id').val();
            var student_id = $('#disable_student_id').val();
            var dataString = { belt : belt_id , selected_belt : s_belt_id , student_id : student_id };
            student_id
            $.ajax({
                type: "POST",
                url: "getstudentsFastrack.php", // Name of the php files
                data: dataString,
                success: function(html) {
                    $("#infoDiv3").html(html);
                }
            });
        }

    </script>
</body>

</html>
