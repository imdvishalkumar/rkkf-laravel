<?php
include_once ("auth.php"); //include auth.php file on all secure pages
$page = "edit_ins_time_table";
include_once ("connection.php");
include_once ("page_title.php");

$submitClick = isset($_POST['submit']);

if (isset($_REQUEST['id']))
{
    $id = $_REQUEST['id'];
    $_SESSION["savedUserId"] = $id;
    $query = "SELECT * from ins_timetable where id ='" . $id . "'";
    $result = mysqli_query($con, $query) or die(mysqli_error());
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) <= 0)
    {
        header("Location: ins_time_table.php");
        exit();
    }
}
else
{
    if (isset($_SESSION["savedUserId"]))
    {
        $id = $_SESSION["savedUserId"];
        $query = "SELECT * from ins_timetable where id='" . $id . "'";
        $result = mysqli_query($con, $query) or die(mysqli_error());
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) <= 0)
        {
            header("Location: ins_time_table.php");
            exit();
        }
    }
    if (!$submitClick)
    {
        header("Location: ins_time_table.php");
        exit();
    }
}

if ($submitClick)
{
    
    $dob = $_POST['dob'];
    $date1 = str_replace('/', '-', $dob);
    $date = date('Y-m-d', strtotime($date1));
    $user_id = $_POST['user_id'];
    $branch_id = $_POST['branch_id'];
    $id = $_SESSION["savedUserId"];

    $sql = "select * from ins_timetable where (date='$date' AND user_id='$user_id' AND branch_id='$branch_id')AND id != $id;";
    $res = mysqli_query( $con, $sql );
    if ( mysqli_num_rows( $res ) > 0 ) {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Record already exists.
                </div></div>";
    } else {
        
        $update = "update ins_timetable set date ='" . $date . "', branch_id='" . $branch_id . "', user_id='" . $user_id . "' where id='" . $id . "'";

        if (mysqli_query($con, $update))
        {
            unset($_SESSION["savedUserId"]);
            $_SESSION["userUpdated"] = "yes";
            header("Location: ins_time_table.php");
            exit();
        }
    }
}

$queryForBranch = "select * from branch";
$resultForBranch = $con->query($queryForBranch);

$queryForUsers = "SELECT * FROM users where role = 2";
$resultForUsers = $con->query($queryForUsers);

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
                                    <h3 class="card-title">Edit User</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="card-body">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <?php
                                                    $timestamp = strtotime($row['date']);
                                                    $new_date = date("d-m-Y", $timestamp);
                                                    ?>
                                            <div class="input-group date" id="dobdate" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#dobdate" name="dob" value="<?php echo $new_date;?>" required />
                                                <div class="input-group-append" data-target="#dobdate" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                                    <label>Branch</label>
                                                    <select class="form-control select2  " style="width: 100%;" name="branch_id" id="branch_id" required>
                                                        <option disabled selected value>Select Branch</option>
                                                        <?php 
												if ($resultForBranch->num_rows > 0) {
													while($rows = $resultForBranch->fetch_assoc()) {
														if ($row['branch_id'] == $rows["branch_id"]) { 
                                                            ?>
                                                            <option data-select2-id="30" selected value="<?php echo $rows["branch_id"]; ?>"><?php echo $rows["name"]; ?></option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option data-select2-id="30" value="<?php echo $rows["branch_id"]; ?>"><?php echo $rows["name"]; ?></option>
                                                            <?php
                                                        }
													}
												} else {
													?>
                                                        <option disabled value>No Branch Found.</option>
                                                        <?php
												}
												?>
                                                    </select>
                                        </div>
                                        <div class="form-group">
                                                    <label>Insctructor</label>
                                                    <select class="form-control select2  " style="width: 100%;" name="user_id" id="user_id" required>
                                                        <option disabled selected value>Select Instructor</option>
                                                        <?php 
												if ($resultForUsers->num_rows > 0) {
													while($rows = $resultForUsers->fetch_assoc()) {
                                                        if ($row['user_id'] == $rows["user_id"]) {  
                                                            ?>
                                                            <option data-select2-id="30" selected value="<?php echo $rows["user_id"]; ?>"><?php echo $rows["firstname"]." ".$rows["lastname"]; ?></option>
                                                            <?php
                                                        } else {                                              
                                                            ?>
                                                            <option data-select2-id="30" value="<?php echo $rows["user_id"]; ?>"><?php echo $rows["firstname"]." ".$rows["lastname"]; ?></option>
                                                            <?php
                                                        }
													}
												} else {
													?>
                                                        <option disabled value>No Insctructor Found.</option>
                                                        <?php
												}
												?>
                                                    </select>
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
<!-- jquery-validation -->
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
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
<script>
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^\w+$/i.test(value);
        }, "Letters, numbers, and underscores only please");
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z]+$/i.test(value);
        }, "Letters only please");
        $.validator.addMethod("numbersonly", function(value, element) {
            return this.optional(element) || /^[0-9]+$/i.test(value);
        }, "Numbers only please");
        $.validator.addMethod("passwordcheck", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9!@#$%^&*]{8,16}$/i.test(value);
        }, "Password only contains alphabets, numbers and special characters");
        $(function() {
            $('#quickForm').validate({
                rules: {
                    firstname: {
                        required: true,
                        lettersonly: true,
                        rangelength: [2, 25]
                    },
                    lastname: {
                        required: true,
                        lettersonly: true,
                        rangelength: [2, 25]
                    },
                    mobileNumber: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 16,
                        passwordcheck: true
                    },
                    role: {
                        required: true
                    },
                },
                messages: {
                    email: {
                        required: "Please enter a email address",
                        email: "Please enter a vaild email address"
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 8 characters long"
                    },
                    firstname: {
                        required: "Please Enter Firstname",
                        rangelength: "Please Enter Characters between 2 to 25"
                    },
                    lastname: {
                        required: "Please Enter Lastname",
                        rangelength: "Please Enter Characters between 2 to 25"
                    },
                    mobileNumber: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    role: "Please Select Role"
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });

    </script>
    
    <script>
        $(function() {
            //Date range picker
            $('#dobdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        })

    </script>
</body>
</html>