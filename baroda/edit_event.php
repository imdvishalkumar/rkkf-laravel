<?php
session_start();
$page="edit_event";
if(!isset($_SESSION["admin"])) {
	header("Location: login.php");
}

include_once("connection.php");
if (isset($_REQUEST['id'])){
	$id=$_REQUEST['id'];
$_SESSION["savedId"] = $id;
$query = "SELECT * from event where event_id='".$id."'"; 

$result = mysqli_query($con, $query) or die ( mysqli_error());
$row = mysqli_fetch_assoc($result);	
      if (mysqli_num_rows($result) > 0) {
	  } else {
		  echo "else";
	  }
}

$submitClick = isset($_POST['submit']);
if($submitClick) {
	$ename=$_POST['event_name'];
	$from_date=$_POST['from_event_date'];
	$to_date=$_POST['to_event_date'];
    $fdate = str_replace('/', '-', $from_date);
    $from_date = date('Y-m-d', strtotime($fdate));
    $tdate = str_replace('/', '-', $to_date);
    $to_date = date('Y-m-d', strtotime($tdate));
	$venue=$_POST['event_venue'];
	$type=$_POST['event_type'];
	$desc=$_POST['event_desc'];
	$fees=$_POST['event_fees'];

    $feesDueDate = $_POST['event_due_date'];
    $tdate = str_replace('/', '-', $feesDueDate);
    $feesDueDate = date('Y-m-d', strtotime($tdate));

    $penaltyFees = $_POST['event_penalty_fees'];

    $penaltyDueDate = $_POST['event_penalty_due_date'];
    $tdate = str_replace('/', '-', $penaltyDueDate);
    $penaltyDueDate = date('Y-m-d', strtotime($tdate));
    
	$id=$_SESSION["savedId"];
	unset($_SESSION['savedId']);
	
    $sql="select * from event where name='$ename' AND event_id != $id;";

	$res=mysqli_query($con,$sql);
      if (mysqli_num_rows($res) > 0) {
        // output data of each row
        $row = mysqli_fetch_assoc($res);
        if ($ename==$row['name'])
        {
           		$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Event already exists.
                </div></div>";
        }
       } else{

		    $update="update event set name='".$ename."', from_date='".$from_date."', to_date='".$to_date."', venue='".$venue."', type='".$type."', description='".$desc."', fees='".$fees."', fees_due_date='".$feesDueDate."', penalty='".$penaltyFees."', penalty_due_date='".$penaltyDueDate."' where event_id='".$id."'";

            if(mysqli_query($con,$update))
            {
                $_SESSION["eventUpdated"] = "yes";
                header("Location: event.php");
                exit();
            }
        }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>


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
            <!-- Content Header (Page header) -->

            <?php include_once("content_header.php"); ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <?php if ($submitClick)
			echo $alertMsg;
		?>
                        <div class="col-12">

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Event</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->

                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Event Name</label>
                                                    <input type="text" class="form-control" name="event_name" placeholder="" required value="<?php echo $row['name'];?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>From Date</label>
                                                    <?php
                                                    $timestamp = strtotime($row['from_date']);
                                                    $new_date = date("d-m-Y", $timestamp);
                                                    ?>
                                                    <div class="input-group date" id="from_event_date" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#from_event_date" name="from_event_date" required value="<?php echo $new_date;?>" />
                                                        <div class="input-group-append" data-target="#from_event_date" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>To Date</label>
                                                    <?php
                                                    $timestamp = strtotime($row['to_date']);
                                                    $new_date = date("d-m-Y", $timestamp);
                                                    ?>
                                                    <div class="input-group date" id="to_event_date" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#to_event_date" name="to_event_date" required value="<?php echo $new_date;?>" />
                                                        <div class="input-group-append" data-target="#to_event_date" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Venue</label>
                                                    <input type="text" class="form-control" name="event_venue" placeholder="" required value="<?php echo $row['venue'];?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Type</label>
                                                    <input type="text" class="form-control" name="event_type" placeholder="" required value="<?php echo $row['type'];?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Description</label>
                                                    <input type="text" class="form-control" name="event_desc" placeholder="" required value="<?php echo $row['description'];?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Fees</label>
                                                    <input type="number" class="form-control" name="event_fees" placeholder="" required value="<?php echo $row['fees'];?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                        <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Fees Due Date</label>
                                            <?php
                                            $timestamp = strtotime($row['fees_due_date']);
                                            $new_date = date("d-m-Y", $timestamp);
                                            ?>
                                            <div class="input-group date" id="event_due_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#event_due_date" name="event_due_date" required  value="<?php echo $new_date;?>" />
                                                <div class="input-group-append" data-target="#event_due_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Penalty Amount</label>
                                            <input type="number" class="form-control" name="event_penalty_fees" placeholder="" required value="<?php echo $row['penalty'];?>">
                                        </div>
                                        </div>
                                        <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Penalty Due Date</label>
                                            <?php
                                            $timestamp = strtotime($row['penalty_due_date']);
                                            $new_date = date("d-m-Y", $timestamp);
                                            ?>
                                            <div class="input-group date" id="event_penalty_due_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#event_penalty_due_date" name="event_penalty_due_date" required  value="<?php echo $new_date;?>" />
                                                <div class="input-group-append" data-target="#event_penalty_due_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
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
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

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
            $('#from_event_date').datetimepicker({
                format: 'DD/MM/YYYY'
            }); 
            $('#to_event_date').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            $('#event_due_date').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            $('#event_penalty_due_date').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        })

    </script>
</body>

</html>
