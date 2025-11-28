<?php
include("auth.php"); 
$page="exam_atten";
include_once("connection.php");
include_once("page_title.php");
$submitClick = isset($_POST['submit']);
if($submitClick) {
    
    $success_var = false;

    if(isset($_POST['update'])) {

        $attenArray = $_POST['r1'];
        $attenIDs = $_POST['attenId'];
        $examId=$_POST['branch_id'];

        $i = 0;

        foreach($attenArray as $index => $value) {
            $queryExam = "SELECT b.code, e.date, ef.exam_belt_id FROM belt b, exam e, students s, exam_fees ef WHERE ef.exam_belt_id = b.belt_id AND e.exam_id = ef.exam_id AND ef.student_id = s.student_id AND s.student_id = ".$attenIDs[$i]." AND ef.exam_id = ".$examId." AND ef.status = 1";
            
            $queryExam_stmt = $con->query( $queryExam );
            $row1 = $queryExam_stmt->fetch_assoc();

            $code = $row1['code'];
            $date = $row1['date'];
            $date = str_replace('-', '', $date);
            $cat = $row1['exam_belt_id'];
            //$certificate_no = "EXAMDATE|BELTCODE|GRNO|CATEGORY";
            $certificate_no = $date.$code.$attenIDs[$i]."VDR".$cat;
            if ($value != 'P')
            {
                $certificate_no = "";
            } else {
                $update = "UPDATE students SET belt_id = ".$cat." WHERE student_id = ".$attenIDs[$i].";";
                $query_stmt = $con->prepare( $update );
                $query_stmt->execute();
            }
            $insert = "INSERT INTO `exam_attendance` (`exam_attendance_id`, `exam_id`, `student_id`, `attend`, `user_id`, `certificate_no`) VALUES ('', $examId, '".$attenIDs[$i]."',  '".$value."', '0', '".$certificate_no."');";
            $query_stmt = $con->prepare( $insert );
            if ( $query_stmt->execute() ) {
                $success_var = true;
            } else {
                $success_var = false;
            }
            $i++;
        }

    } else {

        $ename=$_POST['branch_id'];
        $attenArray = $_POST['r1'];
        $attenIDs = $_POST['attenId'];
    
        foreach($attenArray as $index => $value) {
            $query = "UPDATE exam_attendance SET attend = '".$attenArray[$index]."' WHERE exam_attendance_id = ".$attenIDs[$index].";";
            $query_stmt = $con->prepare( $query );
            if ( $query_stmt->execute() ) {
                $success_var = true;
            } else {
                $success_var = false;
            }
        }
    }
    if ( $success_var ) {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Attendance Updated Successfully.
                </div></div>";
    } else {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error Updating Attendance.
                </div></div>";
    }
}
$queryForBranch = "select * from exam";
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
                        <?php 
                        if ($submitClick){
                            if (isset($alertMsg))
                            {
                                echo $alertMsg;
                            }
                        }?>
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Attendance</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Exam</label>
                                                    <select id="branch_id" class="form-control select2 " style="width: 100%;" name="branch_id" onchange="onBranchChange()" required>
                                                        <option disabled selected value>Select Exam</option>
                                                        <?php 
												if ($resultForBranch->num_rows > 0) {
													while($rows = $resultForBranch->fetch_assoc()) {
														?>
                                                        <option data-select2-id="30" value="<?php echo $rows["exam_id"]; ?>"><?php echo $rows["name"]; ?></option>
                                                        <?php
													}
												} else {
													?>
                                                        <option disabled value>No Exam Found.</option>
                                                        <?php
												}
												?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="infoDiv" class="form-group">
                                    </div>
                                    <!-- /.card-body -->
                                </form>
                            </div>
                            <!-- Button trigger modal -->


                            <!-- Modal -->

                        </div>
                        <!--/.col (left) -->
                        <!-- right column -->
                        <div class="col-md-12">

                            <!-- general form elements -->

                            <!-- /.card -->
                        </div>
                        <!--/.col (right) -->
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
        function onBranchChange() { // Call to ajax function
            var branchId = $('#branch_id').val();
            $.ajax({
                type: "POST",
                url: "exam_attendance_ajax.php", // Name of the php files
                data: {
                    branchId: branchId
                },
                success: function(html) {
                    $("#infoDiv").html(html);
                }
            });
        }

    </script>

</body>

</html>
