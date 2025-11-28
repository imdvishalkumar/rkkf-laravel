<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page = "users";
include_once( "connection.php" );
include_once("page_title.php");

$submitClick = isset( $_POST['submit'] );
$delClick = isset( $_POST['delete'] );
$searchClick = isset( $_POST['search'] );
if ( $delClick ) {
    $id = $_POST['userid'];
    $query = "UPDATE `users` SET `role` = '0' WHERE `users`.`user_id` = $id";
    $result = mysqli_query( $con, $query ) or die ( mysqli_error() );
    if ( $result ) {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  User Deleted Successfully.
                </div></div>";
    } else {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error deleting User.
                </div></div>";
    }
}
if ( $submitClick ) {
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $num = $_POST['mobileNumber'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $role = $_POST['role'];

    $sql = "select * from users where (mobile='$num' or email='$email');";
    $res = mysqli_query( $con, $sql );
    if ( mysqli_num_rows( $res ) > 0 ) {
        // output data of each row
        $row = mysqli_fetch_assoc( $res );
        if ( $num == $row['mobile'] ) {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Mobile Number exists.
                </div></div>";
        } else if ( $email == $row['email'] ) {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Email already exists.
                </div></div>";
        }
    } else {
        $q = "insert into users (firstname,lastname,mobile,email,password,role) values('$fname','$lname','$num','$email','$pass','$role')";

        if ( mysqli_query( $con, $q ) )
        {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  User Added Successfully.
                </div></div>";
        }
    }
}
$sql = "SELECT * FROM users where role != 0";
$result = $con->query( $sql );

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
                        <?php if ($submitClick)
			echo $alertMsg;
		if ($delClick){
			echo $alertMsg;
		}
		if (isset($_SESSION["userUpdated"])){
			if ($_SESSION["userUpdated"] == "yes"){
			unset($_SESSION['userUpdated']);
			$alertMsg = "<div class='row'>
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  User Updated Successfully.
                </div></div>";
							echo $alertMsg;

		}
		}
		
		?>
                        <!-- left column -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">User Details</h3>


                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Mobile</th>
                                                        <th>Email</th>
                                                        <th>Role</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr>  <td>" . $row["firstname"] . " " . $row["lastname"] . "</td>
		  <td>" . $row["mobile"]. "</td>
		  <td>" . $row["email"]. "</td>
		  <td>";
    if ($row["role"] == 1){
        echo "Admin";
    } else {
        echo "Instructure";
    }
    echo "</td>
		  <td>";
      if ($row["email"] == 'kenseiujwl@gmail.com') {
          echo" - ";
      } else {
          echo "
          <div class='text-center'>
				<a href='edit_user.php?id=".$row['user_id']."' class='edit-user action-icon' title='Edit'>
				<span class='fas fa-edit'></span>
				</a>&nbsp;&nbsp;
				<button id='btnModal' value='".$row['user_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
				
		  </div>";
      }
      
      echo "
          </td>
		  
		  </tr>";
  }
} else {
 ?><tr>

                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td><span class="tag tag-success">-</span></td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr><?php
}
				  ?>
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
                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete User.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="userid" id="userid" hidden />

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
                        <!--/.col (left) -->
                        <!-- right column -->
                        <div class="col-md-4">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Add User</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start   -->
                                <form id="quickForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">First Name</label>
                                            <input type="text" class="form-control" name="firstname" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Last Name</label>
                                            <input type="text" class="form-control" name="lastname" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Mobile Number</label>
                                            <input type="text" class="form-control" name="mobileNumber" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email</label>
                                            <input type="email" class="form-control" name="email" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Password</label>
                                            <input type="password" class="form-control" name="password" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select class="form-control select2" style="width: 100%;" name="role">
                                                <option disabled selected value>Select Role</option>
                                                <option data-select2-id="30" value="1">Admin</option>
                                                <option data-select2-id="31" value="2">Instructure</option>
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

    <!-- jquery-validation -->
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
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
            $("#userid").val(val); //set the id to the input on the modal
        }

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
                        minlength: 4,
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
                        required: "Please provide a password"
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
