<?php
include("auth_tmc.php");
$page="view_atten";
include_once("connection.php");
include_once("page_title.php");

$queryForBranch = "SELECT * FROM branch WHERE branch_id = 33 OR branch_id = 81;";
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

        <?php include_once("side_menu_tmc.php"); ?>


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
                                            <h3 class="card-title">Attendance Details</h3>
                                            <div class="card-tools">
                                                <div class="row">
                                                    <div class="col-sm-4 my-auto">
                                                        <select class="form-control select2  " style="width: 100%;" name="branch_id" id="branch_id" onchange="get_studentInfo()" required>
                                                            <option disabled selected value>Select Branch</option>
                                                            <?php 
                                                                if ($resultForBranch->num_rows > 0) {
                                                                    while($rows = $resultForBranch->fetch_assoc()) {
                                                                        ?>
                                                            <option data-select2-id="30" value="<?php echo $rows["branch_id"]; ?>"><?php echo $rows["name"]; ?></option>
                                                            <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                            <option disabled value>No Branch Found.</option>
                                                            <?php
                                                                }
                                                                ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4 my-auto">
                                                        <div class="input-group date" id="startdate" data-target-input="nearest">
                                                            <input type="text" class="form-control datetimepicker-input" data-target="#startdate" name="startdate" onchange="get_studentInfo()" required />
                                                            <div class="input-group-append" data-target="#startdate" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 my-auto">
                                                        <div class="input-group date" id="enddate" data-target-input="nearest">
                                                            <input type="text" class="form-control datetimepicker-input" data-target="#enddate" name="enddate" onchange="get_studentInfo()" required />
                                                            <div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body" id="infoDiv">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>GR No</th>
                                                        <th>Name</th>
                                                        <th>Branch</th>
                                                        <th>Date</th>
                                                        <th>Attend</th>
                                                        <th>Instructor</th>
                                                        <th>Additional</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
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
            //DOB range picker
            $('#startdate').datetimepicker({
                format: "YYYY-MM-DD",
            });
            $('#enddate').datetimepicker({
                format: "YYYY-MM-DD",
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
    
    <script>
        function get_studentInfo() { // Call to ajax function
            var branchId = $('#branch_id').val();
            var startdate = $("input[name=startdate]").val();
            var enddate = $("input[name=enddate]").val();
            $.ajax({
                type: "POST",
                url: "view_attendance_ajax.php", // Name of the php files
                data: {
                    branch_id: branchId,
                    start_date: startdate,
                    end_date: enddate,
                },
                success: function(html) {
                    $("#infoDiv").html(html);
                }
            });
        }
    </script>

</body>

</html>
