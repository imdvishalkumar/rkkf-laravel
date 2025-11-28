<?php
// include_once("auth.php"); //include auth.php file on all secure pages
$page="payment";
include_once("../connection.php");
include_once("../page_title.php");

// if ($_SESSION["email"] != 'rkkf@gmail.com'){
//     header( "Location: index.php" );
//     exit();
// }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <?php include_once("../head_link.php"); ?>
</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">
    <div class="wrapper">

        <?php include_once("../navbar.php"); ?>

        <?php include_once("side_menu.php"); ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <?php include_once("../content_header.php"); ?>

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
                                            <h3 class="card-title">Payment Details</h3>
                                            <div class="card-tools">
                                                <div class="row">
                                                    <div class="col-sm-3 my-auto">
                                                        <select class="form-control select2  " style="width: 100%;" name="type" id="type" onchange="get_studentInfo()" required>
                                                            <option disabled selected value>Select Option</option>
                                                            <option value="fees">Monthly Fees</option>
                                                            <option value="exam">Exam Fees</option>
                                                            <option value="event">Event Fees</option>
                                                            <option value="order">Orders</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 my-auto">
                                                        <select class="form-control select2  " style="width: 100%;" name="mode" id="mode" onchange="get_studentInfo()" required>
                                                            <option disabled selected value>Select Mode</option>
                                                            <option value="app">App</option>
                                                            <option value="cash">Cash</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 my-auto">
                                                        <div class="input-group date" id="startdate" data-target-input="nearest">
                                                            <input type="text" class="form-control datetimepicker-input" data-target="#startdate" name="startdate" onchange="get_studentInfo()" required />
                                                            <div class="input-group-append" data-target="#startdate" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 my-auto">
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

        <?php include_once("../footer.php"); ?>



        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)

    </script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="../plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="../plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="../plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../dist/js/pages/dashboard.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/jszip/jszip.min.js"></script>
    <script src="../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
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
            var type = $('#type').val();
            var mode = $('#mode').val();
            var startdate = $("input[name=startdate]").val();
            var enddate = $("input[name=enddate]").val();
            var param = $("#customSwitch1").is(':checked');
            $.ajax({
                type: "POST",
                url: "payment_report_ajax.php", // Name of the php files
                data: {
                    type: type,
                    mode: mode,
                    start_date: startdate,
                    end_date: enddate,
                    param: param
                },
                success: function(html) {
                    $("#infoDiv").html(html);
                }
            });
        }
        $('#customSwitch1').on('switchChange.bootstrapSwitch', function(event, state) {
            get_studentInfo();
        });

    </script>

</body>

</html>
