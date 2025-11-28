<?php
include("auth.php"); 
$page="event_atten";
include_once("connection.php");
include_once("page_title.php");
$submitClick = isset($_POST['submit']);
if($submitClick) {
	$ename=$_POST['branch_id'];
	$date=$_POST['s_date'];
    $tdate = str_replace('/', '-', $date);
    $date = date('Y-m-d', strtotime($tdate));
    $attenArray = $_POST['r1'];
    $resultArray = $_POST['result'];
    $medalArray = $_POST['medal'];
    $attenIDs = $_POST['attenId'];
                    $success_var = false;

    foreach($attenArray as $index => $value){
        $query = "UPDATE event_attendance SET attend = '".$attenArray[$index]."' WHERE event_attendance_id = ".$attenIDs[$index].";";
        if ($attenArray[$index] == 'P') {
            $query = "UPDATE event_attendance SET attend = '".$resultArray[$index]."' WHERE event_attendance_id = ".$attenIDs[$index].";";
            if ($resultArray[$index] == 'Winner') {
                $query = "UPDATE event_attendance SET attend = '".$medalArray[$index]."' WHERE event_attendance_id = ".$attenIDs[$index].";";
            }
        }
        $query_stmt = $con->prepare( $query );
        if ( $query_stmt->execute() ) {
            $success_var = true;
        } else {
            $success_var = false;
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
$queryForBranch = "select * from event";
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
                                                        <option disabled selected value>Select Event</option>
                                                        <?php 
												if ($resultForBranch->num_rows > 0) {
													while($rows = $resultForBranch->fetch_assoc()) {
														?>
                                                        <option data-select2-id="30" value="<?php echo $rows["event_id"]; ?>"><?php echo $rows["name"]; ?></option>
                                                        <?php
													}
												} else {
													?>
                                                        <option disabled value>No Event Found.</option>
                                                        <?php
												}
												?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <div class="input-group date" id="s_date" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#s_date" name="s_date" onchange="onDateChange()" required />
                                                        <div class="input-group-append" data-target="#s_date" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
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
        $(function() {
            //Date range picker
            $('#s_date').datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: new Date()
            });
            $("#s_date").on("change.datetimepicker", ({
                date,
                oldDate
            }) => {
                onDateChange();
            });


            $('#exam_fees_due_date').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        })

    </script>
    
    <script>
        function onBranchChange() { // Call to ajax function
            var branchId = $('#branch_id').val();
            var date = $('#s_date').datetimepicker('viewDate').format('YYYY-MM-DD');
            $.ajax({
                type: "POST",
                url: "event_attendance_ajax.php", // Name of the php files
                data: {
                    branchId: branchId,
                    date: date
                },
                success: function(html) {
                    $("#infoDiv").html(html);
                }
            });
        }

        function onDateChange() { // Call to ajax function
            var branchId = $('#branch_id').val();
            var date = $('#s_date').datetimepicker('viewDate').format('YYYY-MM-DD');
            $.ajax({
                type: "POST",
                url: "event_attendance_ajax.php", // Name of the php files
                data: {
                    branchId: branchId,
                    date: date
                },
                success: function(html) {
                    $("#infoDiv").html(html);
                }
            });
        }

    </script>
    
    
    <script type="text/javascript">
        function disableResult(val) {
            var selected = $('input[name="r1['+val+']"]:checked').val();
            if (selected == 'A') {
                document.getElementById('result['+val+']').disabled = true;
                document.getElementById('medal['+val+']').disabled = true;
            } else if (selected == 'P'){
                document.getElementById('result['+val+']').disabled = false;
                document.getElementById('medal['+val+']').disabled = false;
            }
        }
        function disableMedal(val) {
            var selected = document.getElementById('result['+val+']').value;
            if (selected == 'Loser') {
                document.getElementById('medal['+val+']').disabled = true;
            } else if (selected == 'Winner'){
                document.getElementById('medal['+val+']').disabled = false;
            }
        }
    </script>

</body>

</html>
