<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="atten_log";
include_once("connection.php");
include_once("page_title.php");
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
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Select Student</h3> 
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    
<div class="row">
    <div class="col-sm-6">
    <div class = "form-group">
    <div class="input-group date" id="startdate" data-target-input="nearest">
        <input type="text" class="form-control datetimepicker-input" data-target="#startdate" name="startdate" onchange="get_AttenInfo_date()" required />
        <div class="input-group-append" data-target="#startdate" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
        </div>
    </div>
</div>
    </div>
    <div class="col-sm-6">
        
<div class = "form-group">
    <div class="input-group date" id="enddate" data-target-input="nearest">
        <input type="text" class="form-control datetimepicker-input" data-target="#enddate" name="enddate" onchange="get_AttenInfo_date()" required />
        <div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
        </div>
    </div>
</div>
    </div>
</div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Enter GrNO OR Name</label> 
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch1" onchange="get_studentInfo()" checked>
                                            <label class="custom-control-label" for="customSwitch1">Find Active students only</label>
                                        </div>
                                        <input type="text" class="form-control" name="grno" id="grno" placeholder="" onchange="get_studentInfo()" required>
                                    </div>
                                    <div id="infoDiv" class="form-group">

                                    </div>
                                    <div id="infoDiv2" class="form-group">

                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->              
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
            $("#fee_id").val(val); //set the id to the input on the modal
        }

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
            get_AttenInfo_date();
        });

        $("#enddate").on("change.datetimepicker", ({
            date,
            oldDate
        }) => {
            get_AttenInfo_date();
        });


    })

</script>

    <script>
        var timer = null;
        $("#grno").keydown(function() {
            clearTimeout(timer);
            timer = setTimeout(get_studentInfo, 0)
        });

        function get_studentInfo() { // Call to ajax function
        console.log("Called!");
            var param = $("#customSwitch1").is(':checked');
            var grno = $('#grno').val();
            var dataString = "grno=" + grno;
            $.ajax({
                type: "POST",
                url: "attendance_log_ajax.php", // Name of the php files
                data: {
                    grno: grno,
                    check_active: param
                },
                success: function(html) {
                    $("#infoDiv").html(html);
                    $("#infoDiv2").html("");
                }
            });
        }
        
        function get_AttenInfo() { // Call to ajax function
            var grno = $('#disable_student_id').val();
            var startdate = $("input[name=startdate]").val();
            var enddate = $("input[name=enddate]").val();

                $.ajax({
                    type: "POST",
                    url: "attendance_log_ajax.php", // Name of the php files
                    data: {
                        disable_student_id: grno,
                        start_date: startdate,
                        end_date: enddate
                    },
                    success: function(html) {
                        $("#infoDiv2").html(html);
                    }
                });
            
        }
        
        function get_AttenInfo_date() { // Call to ajax function
            var grno = $('#disable_student_id').val();
            var startdate = $("input[name=startdate]").val();
            var enddate = $("input[name=enddate]").val();
            if(startdate == '' || enddate == ''){
                
            } else {
                $.ajax({
                    type: "POST",
                    url: "attendance_log_ajax.php", // Name of the php files
                    data: {
                        disable_student_id: grno,
                        start_date: startdate,
                        end_date: enddate
                    },
                    success: function(html) {
                        $("#infoDiv2").html(html);
                    }
                });
            }
        }

    </script>

</body>

</html>
