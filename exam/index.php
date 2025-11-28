<?php
session_start();
if ( !isset( $_SESSION["user"] ) ) {
    header( "Location: login" );
    exit();
}
$page = "exam";
include_once( "../connection.php" );
include_once( "../page_title.php" );

$student_id = $_SESSION["user"];

die();

$submitClick = isset( $_POST['submit'] );
if ( $submitClick ) {
    $belts = $_POST['belt_id'];
    $certis = $_POST['certi'];
    $edates = $_POST['dob'];

    array_unshift($certis , 'N/A');

    $data_inserted = false;

	for($counter = 0; $counter < sizeof($belts); $counter++)
	{
        $date = str_replace('/', '-', $edates[$counter]);
        $edate = date('Y-m-d', strtotime($date));

        $query = "INSERT INTO `temp_exam` (`id`, `student_id`, `belt_id`, `date`, `certificate_no`, `inserted_at`) VALUES (NULL, '".$student_id."', '".$belts[$counter]."', '".$edate."', '".$certis[$counter]."', current_timestamp());";
        if ( mysqli_query( $con, $query) )
        {
            $data_inserted = true;
        } else {
            $data_inserted = false;
        }
	}

    session_destroy();
    if ( $data_inserted )
    {
        echo '<script language="javascript">';
        echo 'alert("Your response is successfully submitted.")';
        echo 'window.location.href="login";';
        echo '</script>';
    } else {
        echo '<script language="javascript">';
        echo 'alert("Something went worng Plesase try after some time.")';
        echo 'window.location.href="login";';
        echo '</script>';

    }
}
$belt_ids = array();
$query = "select belt_id from students where student_id = $student_id";
$result = $con->query( $query );
$row = $result->fetch_assoc();
$belt_id = (int)$row['belt_id'];
if ($belt_id < 2) {
    echo '<script language="javascript">';
    echo 'alert("You have not cleared Any Exam.")
    window.location.href="login";';
    echo '</script>';
    session_destroy();
} else {

	$queryForBelt = "select * from belt where belt_id < '".$belt_id."' and belt_id > 1;";
	$resultForBelt = $con->query($queryForBelt);
    $limit = $resultForBelt->num_rows;
  
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <?php include_once("../head_link.php"); ?>

</head>

<body class="layout-fixed" style="height: auto;">

    <!-- Content Wrapper. Contains page content -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Exam Details</h1>
                </div><!-- /.col -->
                <!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Enter Your Exam Information</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="card-body">
                                <?php 
                                if ($resultForBelt->num_rows > 0) {
                                    while($rows = $resultForBelt->fetch_assoc()) {
                                        array_push($belt_ids,$rows["belt_id"]);
                                        ?>
                                <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Belt</label>
                                                    <input type="text" class="form-control" value ="<?php echo $rows["name"]; ?>"  required disabled>
                                                    <input type="text" class="form-control" name="belt_id[]" value ="<?php echo $rows["belt_id"]; ?>"  required hidden>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Certificate No</label>
                                                    <input type="text" class="form-control" name="certi[]" placeholder="" <?php
                                                    if ($rows["belt_id"] == '2') {
                                                        echo 'value="N/A" disabled';
                                                    } else {
                                                        echo 'required';
                                                    }?>>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Exam Date</label>
                                                    <div class="input-group date" <?php echo "id='dobdate".$rows["belt_id"]."'"; ?> data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" <?php echo "data-target='#dobdate".$rows["belt_id"]."'"; ?> name="dob[]" required />
                                                        <div class="input-group-append" <?php echo "data-target='#dobdate".$rows["belt_id"]."'"; ?> data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <?php
                                    }
                                } else {
                                    ?>
                                       
                                        <?php
                                }
                                ?>
                                
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
    <footer class="footer">
        <strong>Copyright &copy; 2020 <a href="http://www.rkkf.co.in/">RKKF</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 10.0.1-pre
        </div>
    </footer>


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
    <!-- Select2 -->
    <script src="../plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
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
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- jquery-validation -->
    <script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- Page specific script -->
    
    
    <script>
        <?php
        
    $date_id = "#dobdate";
    foreach ($belt_ids as $value) {
        $id = $date_id.$value;
        echo "$(function() {
            //Date range picker
            $('$id').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        });";
    }
    ?>

    </script>


</body>

</html>
<?php
}
?>