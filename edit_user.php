<?php
include_once ("auth.php"); //include auth.php file on all secure pages
$page = "edit_user";
include_once ("connection.php");
include_once ("page_title.php");

$submitClick = isset($_POST['submit']);

if (isset($_REQUEST['id']))
{
    $id = $_REQUEST['id'];
    $_SESSION["savedUserId"] = $id;
    $query = "SELECT * from users where user_id='" . $id . "'";
    $result = mysqli_query($con, $query) or die(mysqli_error());
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) <= 0)
    {
        header("Location: users.php");
        exit();
    }
}
else
{
    if (isset($_SESSION["savedUserId"]))
    {
        $id = $_SESSION["savedUserId"];
        $query = "SELECT * from users where user_id='" . $id . "'";
        $result = mysqli_query($con, $query) or die(mysqli_error());
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) <= 0)
        {
            header("Location: users.php");
            exit();
        }
    }
    if (!$submitClick)
    {
        header("Location: users.php");
        exit();
    }
}

if ($submitClick)
{
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $num = $_POST['mobileNumber'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $role = $_POST['role'];
    $id = $_SESSION["savedUserId"];
    $sql = "select * from users where (mobile='$num' or email='$email') AND user_id != $id;";

    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0)
    {
        // output data of each row
        $row1 = mysqli_fetch_assoc($res);
        if ($num == $row1['mobile'])
        {
            $alertMsg = "   
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Mobile Number exists.
                </div></div>";
        }
        else if ($email == $row1['email'])
        {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Email already exists.
                </div></div>";
        }
    }
    else
    {
        $update = "update users set firstname='" . $fname . "', lastname='" . $lname . "', mobile='" . $num . "', email='" . $email . "', password='" . $pass . "', role='" . $role . "' where user_id='" . $id . "'";

        if (mysqli_query($con, $update))
        {
            unset($_SESSION["savedUserId"]);
            $_SESSION["userUpdated"] = "yes";
            header("Location: users.php");
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
                                    <h3 class="card-title">Edit User</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
									<div class="row">
									 <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">First Name</label>
                                            <input type="text" class="form-control" name="firstname" placeholder="" required value="<?php echo $row['firstname'];?>">
                                        </div>
                                        </div>
										 <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Last Name</label>
                                            <input type="text" class="form-control" name="lastname" placeholder="" required value="<?php echo $row['lastname'];?>">
                                        </div>
                                        </div>
                                        </div>
										
									<div class="row">
									 <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Mobile Number</label>
                                            <input type="text" class="form-control" name="mobileNumber" placeholder="" required value="<?php echo $row['mobile'];?>">
                                        </div>
                                        </div>
										
									 <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email</label>
                                            <input type="email" class="form-control" name="email" placeholder="" required value="<?php echo $row['email'];?>">
                                        </div>
                                        </div>
                                    </div>
									<div class="row">
										<div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Password</label>
                                            <input type="password" class="form-control" name="password" placeholder="" required value="<?php echo $row['password'];?>">
                                        </div>
                                        </div>
										<div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select class="form-control select2 " style="width: 100%;" name="role" required>
											<?php 
											if ($row['role'] == 1) {
												?>
												<option disabled value>Select Role</option>
                                                <option data-select2-id="30" value="1" selected>Admin</option>
                                                <option data-select2-id="31" value="2">Instructure</option>
												<?php
											} else {
												?>
												<option disabled value>Select Role</option>
                                                <option data-select2-id="30" value="1">Admin</option>
                                                <option data-select2-id="31" value="2" selected>Instructure</option>
												<?php
											}
											?>
                                                
                                            </select>
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
</body>
</html>