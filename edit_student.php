<?php
include_once ("auth.php"); //include auth.php file on all secure pages
$page = "edit_student";
include_once ("connection.php");
include_once ("page_title.php");

$submitClick = isset($_POST['submit']);

if (isset($_REQUEST['id']))
{
    $id = $_REQUEST['id'];
    $_SESSION["savedStudentId"] = $id;
    $query = "SELECT * from students where student_id='" . $id . "'";
    $result = mysqli_query($con, $query) or die(mysqli_error());
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) <= 0)
    {
        header("Location: view_students.php");
        exit();
    }
}
else
{
    if (isset($_SESSION["savedStudentId"]))
    {
        $id = $_SESSION["savedStudentId"];
        $query = "SELECT * from students where student_id='" . $id . "'";
        $result = mysqli_query($con, $query) or die(mysqli_error());
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) <= 0)
        {
            header("Location: view_students.php");
            exit();
        }
    }
    if (!$submitClick)
    {
        header("Location: view_students.php");
        exit();
    }
}

if ($submitClick)
{
    $id = $_SESSION["savedStudentId"];
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $belt = $_POST['belt'];
    $dmno = $_POST['dmno'];
    $dwno = $_POST['dwno'];
    $mmno = $_POST['mmno'];
    $mwno = $_POST['mwno'];
    $smno = $_POST['smno'];
    $swno = $_POST['swno'];
    $dob = $_POST['dob'];
    $date = str_replace('/', '-', $dob);
    $dob = date('Y-m-d', strtotime($date));
    $doj = $_POST['doj'];
    $date = str_replace('/', '-', $doj);
    $doj = date('Y-m-d', strtotime($date));
    $address = $_POST['address'];
    $branch_id = $_POST['branch_id'];
    $pincode = $_POST['pincode'];
    
    $sqlForStudents = "select * from students where email='$email' AND student_id != $id;";
    $sqlForUsers = "select * from users where email='$email' AND user_id != $id;";
    $res1 = mysqli_query( $con, $sqlForStudents );
    $res2 = mysqli_query( $con, $sqlForUsers );
    if ( mysqli_num_rows( $res1 ) > 0 ) {
        // output data of each row
        $row1 = mysqli_fetch_assoc( $res1 );
        if ( $email == $row1['email'] ) {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Email already exists.
                </div></div>";
        }
    }elseif ( mysqli_num_rows( $res2 ) > 0 ) {
        // output data of each row
        $row2 = mysqli_fetch_assoc( $res2 );
        if ( $email == $row2['email'] ) {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Email already exists in instructor.
                </div></div>";
        }
    } else {
        
        $update = "update students set firstname='" . $fname . "', lastname='" . $lname . "', gender='" . $gender . "', email='" . $email . "', belt_id='" . $belt . "', dadno='" . $dmno . "', dadwp='" . $dwno . "', momno='" . $mmno . "', momwp='" . $mwno . "', selfno='" . $smno . "', selfwp='" . $swno . "', dob='" . $dob . "', doj='" . $doj . "', address='" . $address . "', branch_id='" . $branch_id . "', pincode='" . $pincode . "' where student_id='" . $id . "'";

        if (mysqli_query($con, $update))
        {
            unset($_SESSION["savedStudentId"]);
            header("Location: view_students.php");
            exit();
            $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-success alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-check'></i> Alert!</h5>
                      Student Updated Successfully.
                    </div></div>";
        }
    }
}
$queryForBranch = "select * from branch";
$resultForBranch = $con->query($queryForBranch);

$queryForBelt = "select * from belt";
$resultForBelt = $con->query($queryForBelt);

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
                        ?>
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Student</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" id="quickForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">First Name</label>
                                                    <input type="text" class="form-control" name="firstname" placeholder="" value="<?php echo $row['firstname'];?>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Last Name</label>
                                                    <input type="text" class="form-control" name="lastname" placeholder="" value="<?php echo $row['lastname'];?>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select class="form-control select2 " style="width: 100%;" name="gender" required>
                                                        <?php 
											             if ($row['gender'] == 1) {
												        ?>
                                                        <option disabled value>Select Gender</option>
                                                        <option data-select2-id="30" value="1" selected>Male</option>
                                                        <option data-select2-id="31" value="2">Female</option>
                                                        <?php
                                                        } else {
												        ?>
                                                        <option disabled value>Select Gender</option>
                                                        <option data-select2-id="30" value="1">Male</option>
                                                        <option data-select2-id="31" value="2" selected>Female</option>
                                                        <?php
											             }
											             ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Email</label>
                                                    <input type="text" class="form-control" name="email" placeholder="" value="<?php echo $row['email'];?>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Belt</label>
                                                    <select id="belt" class="form-control select2 " style="width: 100%;" name="belt" required>
                                                        <option disabled selected value>Select Belt</option>
                                                        <?php 
												if ($resultForBelt->num_rows > 0) {
													while($rows = $resultForBelt->fetch_assoc()) {
                                                        if ($row['belt_id'] == $rows["belt_id"]) {
                                                        ?>
                                                            <option data-select2-id="30" selected value="<?php echo $rows["belt_id"]; ?>"><?php echo $rows["name"]; ?></option>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <option data-select2-id="30" value="<?php echo $rows["belt_id"]; ?>"><?php echo $rows["name"]; ?></option>
                                                        <?php
                                                        }
														?>
                                                        
                                                        <?php
													}
												} else {
													?>
                                                        <option disabled value>No Belt Found.</option>
                                                        <?php
												}
												?>
                                                    </select>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Dad Mobile No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="dmno" placeholder="" value="<?php echo $row['dadno'];?>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Dad WhatsApp No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="dwno" placeholder="" value="<?php echo $row['dadwp'];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Mom Mobile No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="mmno" placeholder="" value="<?php echo $row['momno'];?>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Mom WhatsApp No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="mwno" placeholder="" value="<?php echo $row['momwp'];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Self Mobile No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="smno" placeholder="" value="<?php echo $row['selfno'];?>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Self WhatsApp No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="swno" placeholder="" value="<?php echo $row['selfwp'];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date of Birth:</label>
                                                    <?php
                                                    $timestamp = strtotime($row['dob']);
                                                    $new_date = date("d-m-Y", $timestamp);
                                                    ?>
                                                    <div class="input-group date" id="dobdate" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#dobdate" name="dob" value="<?php echo $new_date;?>" required />
                                                        <div class="input-group-append" data-target="#dobdate" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date of Join:</label>
                                                    <?php
                                                    $timestamp = strtotime($row['doj']);
                                                    $new_date = date("d-m-Y", $timestamp);
                                                    ?>
                                                    <div class="input-group date" id="dojdate" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#dojdate" name="doj" value="<?php echo $new_date;?>" required />
                                                        <div class="input-group-append" data-target="#dojdate" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Date -->
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea style="resize:none" class="form-control" rows="3" name="address"><?php echo $row['address'];?></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Branch</label>
                                                    <select id="branch_id" class="form-control select2 " style="width: 100%;" name="branch_id" id="branch_id" required >
                                                        <option disabled selected value>Select Branch</option>
                                                        <?php 
												        if ($resultForBranch->num_rows > 0) {
                                                            while($rows1 = $resultForBranch->fetch_assoc()) {
                                                                if ($row['branch_id'] == $rows1["branch_id"]) {
                                                        ?>
                                                                    <option data-select2-id="30" selected value="<?php echo $rows1["branch_id"]; ?>"><?php echo $rows1["name"]; ?></option>
                                                        <?php
                                                                }
                                                                else {
                                                        ?>
                                                                    <option data-select2-id="30" value="<?php echo $rows1["branch_id"]; ?>"><?php echo $rows1["name"]; ?></option>
                                                        <?php
                                                                }
                                                            }
                                                        }
                                                        else {
                                                        ?>
                                                            <option disabled value>No Branch Found.</option>
                                                        <?php
                                                        }
												        ?>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Pincode</label>
                                                    <input type="number" maxlength="6" class="form-control" name="pincode" placeholder="" value="<?php echo $row['pincode'];?>"required>
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
    <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
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
        $("#btnModal").click(function() {
            var passedID = $(this).data('id'); //get the id of the selected button
            $("#userid").val(passedID); //set the id to the input on the modal
        });

    </script>
    <script>
        $(function() {
            //Date range picker
            $('#dobdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            //DOB range picker
            $('#dojdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        })

    </script>
    
    
    <script>
        $.validator.addMethod("alphanumericspecial", function(value, element) {
            return this.optional(element) || /^[ A-Za-z0-9_@.,/#&+-\s]*$/i.test(value);
        }, "Letters, numbers, and special characters only please");
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^\w+$/i.test(value);
        }, "Letters, numbers, and underscores only please");
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z]+$/i.test(value);
        }, "Letters only please");
        $.validator.addMethod("numbersonly", function(value, element) {
            return this.optional(element) || /^[0-9]+$/i.test(value);
        }, "Numbers only please");
        $(function() {
            $('#quickForm').validate({
                rules: {
                    firstname: {
                        required: true,
                        alphanumericspecial: true,
                        rangelength: [2, 25]
                    },
                    lastname: {
                        required: true,
                        lettersonly: true,
                        rangelength: [2, 25]
                    },
                    gender: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    belt: {
                        required: true,
                    },
                    dmno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    dwno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    mmno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    mwno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    smno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    swno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    dob: {
                        required: true,
                    },
                    doj: {
                        required: true,
                    },
                    address: {
                        required: true,
                        alphanumericspecial: true,
                        rangelength: [1, 448],
                    },
                    branch_id: {
                        required: true,
                    },
                    pincode: {
                        required: true,
                        numbersonly: true,
                        rangelength: [6, 6],
                    },
                    fees: {
                        required: true,
                        numbersonly: true,
                    },
                },
                messages: {
                    firstname: {
                        required: "Please Enter Firstname",
                        rangelength: "Please Enter Characters between 2 to 25"
                    },
                    lastname: {
                        required: "Please Enter Lastname",
                        rangelength: "Please Enter Characters between 2 to 25"
                    },
                    gender: {
                        required: "Please Select Gender",
                    },
                    email: {
                        required: "Please Enter Email address",
                        email: "Please Enter a vaild Email address"
                    },
                    belt: {
                        required: "Please Select Belt",
                    },
                    dmno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    dwno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    mmno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    mwno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    smno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    swno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    dob: {
                        required: "Please Enter Your Birthdate"
                    },
                    doj: {
                        required: "Please Enter Your Joining Date"
                    },
                    address: {
                        required: "Please Enter Your Address",
                    },
                    branch_id: {
                        required: "Please Select Branch",
                    },
                    pincode: {
                        required: "Please Enter Your Pincode",
                        rangelength: "Please Enter Valid Pincode"
                    },
                    fees: {
                        required: "Please Enter Fees Amount",
                    },
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
