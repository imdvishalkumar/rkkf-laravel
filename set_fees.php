<?php
session_start();
$page = "set_fees";
if ( !isset( $_SESSION["admin"] ) ) {
    header( "Location: login.php" );
}
include_once( "connection.php" );
$submitClick = isset( $_POST['submit'] );

if ( $submitClick ) {
    $month = $_POST['month'];
    $discount = $_POST['discount'];
    $late = $_POST['late'];
    $cat1 = $_POST['cat1'];
    $cat2 = $_POST['cat2'];
    $cat3 = $_POST['cat3'];
    $cat4 = $_POST['cat4'];
    $cat5 = $_POST['cat5'];
    $cat6 = $_POST['cat6'];
    $cat7 = $_POST['cat7'];
    $cat8 = $_POST['cat8'];

    $sql = "UPDATE `fees_const` SET `amount` = '".$month."' WHERE `fees_const`.`fc_id` = 1;
UPDATE `fees_const` SET `amount` = '".$discount."' WHERE `fees_const`.`fc_id` = 2;
UPDATE `fees_const` SET `amount` = '".$late."' WHERE `fees_const`.`fc_id` = 3;
UPDATE `fees_const` SET `amount` = '".$cat1."' WHERE `fees_const`.`fc_id` = 4;
UPDATE `fees_const` SET `amount` = '".$cat2."' WHERE `fees_const`.`fc_id` = 5;
UPDATE `fees_const` SET `amount` = '".$cat3."' WHERE `fees_const`.`fc_id` = 6;
UPDATE `fees_const` SET `amount` = '".$cat4."' WHERE `fees_const`.`fc_id` = 7;
UPDATE `fees_const` SET `amount` = '".$cat5."' WHERE `fees_const`.`fc_id` = 8;
UPDATE `fees_const` SET `amount` = '".$cat6."' WHERE `fees_const`.`fc_id` = 9;
UPDATE `fees_const` SET `amount` = '".$cat7."' WHERE `fees_const`.`fc_id` = 10;
UPDATE `fees_const` SET `amount` = '".$cat8."' WHERE `fees_const`.`fc_id` = 11;";
    $res = mysqli_multi_query( $con, $sql );
    if ( $res == true ) {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Fees Updated.
                </div></div>";
    } else {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error updating Fees.
                </div></div>";
    }
}
else {
    
$sql1 = "SELECT amount FROM `fees_const` WHERE fc_id = 1 ";
$result1 = $con->query($sql1);
$row1 = mysqli_fetch_row($result1);
//echo ($row1[0]);

$month = $row1[0];

$sql2 = "SELECT amount FROM `fees_const` WHERE fc_id = 2 ";
$result2 = $con->query($sql2);
$row2 = mysqli_fetch_row($result2);
$discount = $row2[0];//$result2['amount'];

$sql3 = "SELECT amount FROM `fees_const` WHERE fc_id = 3";
$result3 = $con->query($sql3);
$row3 = mysqli_fetch_row($result3);
$late = $row3[0];

$sql4 = "SELECT amount FROM `fees_const` WHERE fc_id = 4";
$result4 = $con->query($sql4);
$row4 = mysqli_fetch_row($result4);
$cat1 = $row4[0];

$sql5 = "SELECT amount FROM `fees_const` WHERE fc_id = 5";
$result5 = $con->query($sql5);
$row5 = mysqli_fetch_row($result5);
$cat2 = $row5[0];

$sql6 = "SELECT amount FROM `fees_const` WHERE fc_id = 6";
$result6 = $con->query($sql6);
$row6 = mysqli_fetch_row($result6);
$cat3 = $row6[0];

$sql7 = "SELECT amount FROM `fees_const` WHERE fc_id = 7";
$result7 = $con->query($sql7);
$row7 = mysqli_fetch_row($result7);
$cat4 = $row7[0];

$sql8 = "SELECT amount FROM `fees_const` WHERE fc_id = 8";
$result8 = $con->query($sql8);
$row8 = mysqli_fetch_row($result8);
$cat5 = $row8[0];

$sql9 = "SELECT amount FROM `fees_const` WHERE fc_id = 9";
$result9 = $con->query($sql9);
$row9 = mysqli_fetch_row($result9);
$cat6 = $row9[0];

$sql10 = "SELECT amount FROM `fees_const` WHERE fc_id = 10";
$result10 = $con->query($sql10);
$row10 = mysqli_fetch_row($result10);
$cat7 = $row10[0];

$sql11 = "SELECT amount FROM `fees_const` WHERE fc_id = 11";
$result11 = $con->query($sql11);
$row11 = mysqli_fetch_row($result11);
$cat8 = $row11[0];

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coupons</title>


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
                        <!-- left column -->
                        
                        <!--/.col (left) -->
                        <!-- right column -->
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Set Fees</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Monthly Fees</label>
                                                    <input type="text" class="form-control" name="month" value ="<?php echo $month ?>"required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Discount</label>
                                                    <input type="text" class="form-control" name="discount" value ="<?php echo $discount ?>"required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Late</label>
                                                    <input type="text" class="form-control" name="late" value ="<?php echo $late ?>"required>
                                                </div>
                                            </div>
                                        </div>

                                            <label for="exampleInputEmail1">Category</label>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Single</label>
                                                    <input type="text" class="form-control" name="cat1" value ="<?php echo $cat1 ?>"required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Double</label>
                                                    <input type="text" class="form-control" name="cat2" value ="<?php echo $cat2 ?>"required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Triple</label>
                                                    <input type="text" class="form-control" name="cat3" value ="<?php echo $cat3 ?>"required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Quadruple</label>
                                                    <input type="text" class="form-control" name="cat4" value ="<?php echo $cat4 ?>"required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Quintuple</label>
                                                    <input type="text" class="form-control" name="cat5" value ="<?php echo $cat5 ?>"required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Sextuple</label>
                                                    <input type="text" class="form-control" name="cat6" value ="<?php echo $cat6 ?>"required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Septuple</label>
                                                    <input type="text" class="form-control" name="cat7" value ="<?php echo $cat7 ?>"required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Octuple</label>
                                                    <input type="text" class="form-control" name="cat8" value ="<?php echo $cat8 ?>"required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
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
    <script type="text/javascript">
        function passId(val) {
            $("#coupon_id").val(val); //set the id to the input on the modal
        }
    </script>

</body>

</html>
