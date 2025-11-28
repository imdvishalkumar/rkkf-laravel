<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="enter_fees";
include_once("page_title.php");
include_once("connection.php");
$submitClick = isset($_POST['submit']);

if($submitClick) {
    $id=$_POST['disable_student_id'];
    if (isset($_POST['disable_month'])) {
        $month=$_POST['disable_month'];
        $year=$_POST['disable_year'];
    } else {
        $date_string = $_POST['doj'];
        $datearr = explode('/', $date_string);
        $month = (int)$datearr[0];
        $year = (int)$datearr[1];
    }
    $caseFeesPaid = isset($_POST['disable_month']);
    $selectedmonth=$_POST['increaseMonth'];
    $currentDate = date("Y-m-d");
    $feeQuery = "";
    $count = $selectedmonth;
    $amount = $_POST['amount'];
    $remarks = $_POST['remarks'];
    $check = $amount % $count;
    $fees = ( $amount-$check ) / $count;
    $temp = 0;

    while ($selectedmonth > 0) {
        if ($caseFeesPaid) {
            $month++;
            $caseFeesPaid = false;
            if ($month == 13){
                $month = 1;
                $year = $year +1;
            }
        }
        if ( $temp == 0 ) {
            $temp++;
            $feeQuery .= "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode,remarks) values ($id,".$month.",$year,'$currentDate','".( $fees + $check )."',1,0,0,'cash','$remarks');";
        } else {
            $feeQuery .= "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode,remarks) values ($id,".$month.",$year,'$currentDate','$fees',1,0,0,'cash','$remarks');";
        }
        if ($month == 12){
            $month = 0;
            $year = $year +1;
        }
        $month++;
        $selectedmonth--;
    }
        $result = $con->multi_query($feeQuery);
        if($result)
        {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Fees Inserted Successfully.
                </div></div>";
        } else
        {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error Inserting Fees.
                </div></div>";
        }
    
}
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
                        <?php if ($submitClick) { echo $alertMsg; } ?>
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Enter Fees</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Enter GrNO OR Name</label>
                                            <input type="text" class="form-control" name="grno" id="grno" placeholder="" onchange="get_studentInfo()" required>
                                        </div>
                                        <div id="infoDiv" class="form-group">

                                        </div>
                                        <div id="infoDiv2" class="form-group">

                                        </div>

                                        <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- left column -->
                        <div class="col-md-12">
                            
                            <!-- Button trigger modal -->


                            <!-- Modal -->
                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                <input type="text" name="student_id" id="student_id" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="delete" class="btn btn-success">Activate</button>
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
    
     <script type="text/javascript">
        hideBtn();

        function showBtn() {
            $(document).ready(function() {
                $("#submit").show();
            });
        }

        function hideBtn() {
            $(document).ready(function() {
                $("#submit").hide();
            });
        }

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

    <script>
        $(function() {
            //DOB range picker
            $('#dojdate').datetimepicker({
                format: "MM/yyyy",
                viewMode: "months",
                minViewMode: "months"
            });
        })

    </script>

    <script type="text/javascript">
        function passId(val) {
            $("#student_id").val(val); //set the id to the input on the modal
        }

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
            $.ajax({
                type: "POST",
                url: "enter_fees_ajax.php", // Name of the php files
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
                url: "enter_fees_ajax.php", // Name of the php files
                data: dataString,
                success: function(html) {
                    $("#infoDiv2").html(html);
                    $('#dojdate').datetimepicker({
                        format: "MM/yyyy",
                        viewMode: "months",
                        minViewMode: "months"
                    });
                }
            });
        }

    </script>
</body>

</html>
