<?php
include_once ("auth.php"); //include auth.php file on all secure pages
$page = "edit_branch";
include_once ("connection.php");
include_once ("page_title.php");

$submitClick = isset($_POST['submit']);

if (isset($_REQUEST['id']))
{
    $id = $_REQUEST['id'];
    $_SESSION["savedBranchId"] = $id;
    $query = "SELECT * from branch where branch_id='" . $id . "'";
    $result = mysqli_query($con, $query) or die(mysqli_error());
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) <= 0)
    {
        header("Location: branch.php");
        exit();
    }
}
else
{
    if (isset($_SESSION["savedBranchId"]))
    {
        $id = $_SESSION["savedBranchId"];
        $query = "SELECT * from branch where branch_id='" . $id . "'";
        $result = mysqli_query($con, $query) or die(mysqli_error());
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) <= 0)
        {
            header("Location: branch.php");
            exit();
        }
    }
    if (!$submitClick)
    {
        header("Location: branch.php");
        exit();
    }
}

if ($submitClick)
{
    $bname = $_POST['branch_name'];
    $bfees = $_POST['branch_fees'];
    $late = $_POST['late'];
    $discount = $_POST['discount'];
    $days = array();
    if (!empty($_POST['days']))
    {
        foreach ($_POST['days'] as $day)
        {
            $days[] = $day;
        }
    }
    $daysStr = implode(',', $days);
    $id = $_SESSION["savedBranchId"];
    $sql = "select * from branch where name='$bname' AND branch_id != $id;";

    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0)
    {
        // output data of each row
        $row1 = mysqli_fetch_assoc($res);
        if ($bname == $row1['name'])
        {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Branch already exists.
                </div></div>";
        }
    }
    else
    {
        $update = "update branch set name='" . $bname . "', fees='" . $bfees . "', discount='" . $discount . "', late='" . $late . "', days='" . $daysStr . "' where branch_id='" . $id . "'";

        if (mysqli_query($con, $update))
        {
            unset($_SESSION['savedId']);
            $_SESSION["branchUpdated"] = "yes";
            header("Location: branch.php");
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
    <?php include_once("head_link.php"); ?>

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
                                    <h3 class="card-title">Edit Branch</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
									<div class="row">
									 <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Branch Name</label>
                                            <input type="text" class="form-control" name="branch_name" placeholder="" required value="<?php echo $row['name'];?>">
                                        </div>
                                         <div class="form-group">
                                            <label for="exampleInputEmail1">Branch Fees</label>
                                            <input type="number" class="form-control" name="branch_fees" placeholder="" required value="<?php echo $row['fees'];?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Late Fees</label>
                                            <input type="number" class="form-control" name="late" placeholder="" required value="<?php echo $row['late'];?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Discount Fees</label>
                                            <input type="number" class="form-control" name="discount" placeholder="" required value="<?php echo $row['discount'];?>">
                                        </div>
                                    </div>
										 <div class="col-sm-12">
                                        <div class="form-group">
                                             <?php
                                             // use of explode 
                                            $string = $row['days']; 
                                            $days_arr = explode (",", $string);  
                                             if (in_array("Monday", $days_arr))   //1
                                             {
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" name="days[]" value="Monday" checked>
                                                <label for="customCheckbox1" class="custom-control-label">Monday</label>
                                                </div>
                                            
                                            <?php     
                                             }
                                            else{
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" name="days[]" value="Monday">
                                                <label for="customCheckbox1" class="custom-control-label">Monday</label>
                                                </div>
                                                <?php
                                            }
                                             if (in_array("Tuesday", $days_arr))   //2
                                             {
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox2" name="days[]" value="Tuesday" checked>
                                                <label for="customCheckbox2" class="custom-control-label">Tuesday</label>
                                                </div>
                                            
                                            <?php     
                                             }
                                            else{
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox2" name="days[]" value="Tuesday">
                                                <label for="customCheckbox2" class="custom-control-label">Tuesday</label>
                                                </div>
                                                <?php
                                            }
                                             if (in_array("Wednesday", $days_arr))   //3
                                             {
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox3" name="days[]" value="Wednesday" checked>
                                                <label for="customCheckbox3" class="custom-control-label">Wednesday</label>
                                                </div>
                                            
                                            <?php     
                                             }
                                            else{
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox3" name="days[]" value="Wednesday">
                                                <label for="customCheckbox3" class="custom-control-label">Wednesday</label>
                                                </div>
                                                <?php
                                            }
                                             if (in_array("Thursday", $days_arr))   //4
                                             {
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox4" name="days[]" value="Thursday" checked>
                                                <label for="customCheckbox4" class="custom-control-label">Thursday</label>
                                                </div>
                                            <?php     
                                             }
                                            else{
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox4" name="days[]" value="Thursday">
                                                <label for="customCheckbox4" class="custom-control-label">Thursday</label>
                                                </div>
                                                <?php
                                            }
                                             if (in_array("Friday", $days_arr))   //5
                                             {
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox5" name="days[]" value="Friday" checked>
                                                <label for="customCheckbox5" class="custom-control-label">Friday</label>
                                                </div>
                                            
                                            <?php     
                                             }
                                            else{
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox5" name="days[]" value="Friday">
                                                <label for="customCheckbox5" class="custom-control-label">Friday</label>
                                                </div>
                                                <?php
                                            }
                                             if (in_array("Saturday", $days_arr))   //6
                                             {
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox6" name="days[]" value="Saturday" checked>
                                                <label for="customCheckbox6" class="custom-control-label">Saturday</label>
                                                </div>
                                            
                                            <?php     
                                             }
                                            else{
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox6" name="days[]" value="Saturday">
                                                <label for="customCheckbox6" class="custom-control-label">Saturday</label>
                                                </div>
                                                <?php
                                            }
                                             ?>
                                            
                                            
                                            
                                            
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
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
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
</body>
</html>