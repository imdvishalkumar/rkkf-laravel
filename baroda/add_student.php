<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page = "add_student";
include_once( "connection.php" );
include_once("page_title.php");
$submitClick = isset( $_POST['submit'] );
if ( $submitClick ) {
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $_SESSION['passedEmail'] = $email;
    $belt = $_POST['belt'];
    $dmno = $_POST['dmno'];
    $dwno = $_POST['dwno'];
    $mmno = $_POST['mmno'];
    $mwno = $_POST['mwno'];
    $smno = $_POST['smno'];
    $_SESSION['passedPassword'] = $smno;
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
    $fees = $_POST['fees'];
    $std = $_POST['std'];

    $sqlForStudents = "select * from students where email='$email';";
    $sqlForUsers = "select * from users where email='$email';";
    $res1 = mysqli_query( $con, $sqlForStudents );
    $res2 = mysqli_query( $con, $sqlForUsers );
    if ( mysqli_num_rows( $res1 ) > 0 ) {
        // output data of each row
        $row = mysqli_fetch_assoc( $res1 );
        if ( $email == $row['email'] ) {
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
        $row = mysqli_fetch_assoc( $res2 );
        if ( $email == $row['email'] ) {
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Email already exists in instructor.
                </div></div>";
        }
    } else {
        $hash = password_hash($smno,PASSWORD_DEFAULT); 
        $query = "INSERT INTO `students` (`student_id`, `firstname`, `lastname`, `gender`, `email`, `password`, `belt_id`, `dadno`, `dadwp`, `momno`, `momwp`, `selfno`, `selfwp`, `dob`, `doj`, `address`, `branch_id`, `pincode`, `active`) VALUES (NULL, '".$fname."', '".$lname."', '".$gender."', '".$email."', '".$hash."', '".$belt."', '".$dmno."', '".$dwno."', '".$mmno."', '".$mwno."', '".$smno."', '".$swno."', '".$dob."', '".$doj."', '".$address."', '".$branch_id."','".$pincode."','1');";
        if ( mysqli_query( $con, $query) )
        {   
            $monthAllCheckBool = false;
            $s_id = mysqli_insert_id($con);
            $currentDate = date("Y-m-d");
            $currentYear = date("Y");
            if(!empty($_POST['months'])){
                $mon = $_POST['months'];
                $fees = ($fees / count($mon));
                foreach($_POST['months'] as $month){
                    $feeQuery = "INSERT INTO fees (student_id,months,year,date,amount,coupon_id,additional,disabled,mode) values ($s_id,$month,'$currentYear','$currentDate','$fees','1','0','0','cash')";
                    if ( mysqli_query( $con, $feeQuery) )
                    {
                        $monthAllCheckBool = true;
                    } else {
                        $studentDeleteQuery = "DELETE FROM `students` WHERE student_id =".$s_id;
                        mysqli_query( $con, $studentDeleteQuery);
                        $monthAllCheckBool = false;
                    }
                }
            }
            if ($monthAllCheckBool) {
                $_SESSION["studentAdded"] = "yes";
                header("Location: student_added.php");
                exit();
                $alertMsg = "
                <div class='col-md-12'>
                    <div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                        <h5><i class='icon fas fa-check'></i> Alert!</h5>
                        Student Added Successfully.
                    </div>
                </div>";
            } else {
                $alertMsg = "
                <div class='col-md-12'>
                    <div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                        <h5><i class='icon fas fa-check'></i> Alert!</h5>
                        Error while adding student.
                    </div>
                </div>";
            }
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
                                    <h3 class="card-title">Add Student</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form id="quickForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">First Name</label>
                                                    <input type="text" class="form-control" name="firstname" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Last Name</label>
                                                    <input type="text" class="form-control" name="lastname" placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select class="form-control select2" style="width: 100%;" name="gender" required>
                                                        <option disabled selected value>Select Gender</option>
                                                        <option data-select2-id="30" value="1">Male</option>
                                                        <option data-select2-id="31" value="2">Female</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Email</label>
                                                    <input type="text" class="form-control" name="email" placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Belt</label>
                                                    <select id="belt" class="form-control select2" style="width: 100%;" name="belt" required>
                                                        <option disabled selected value>Select Belt</option>
                                                        <?php 
												if ($resultForBelt->num_rows > 0) {
													while($rows = $resultForBelt->fetch_assoc()) {
														?>
                                                        <option data-select2-id="30" value="<?php echo $rows["belt_id"]; ?>"><?php echo $rows["name"]; ?></option>
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
                                                    <input type="text" maxlength="10" class="form-control" name="dmno" placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Dad WhatsApp No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="dwno" placeholder="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Mom Mobile No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="mmno" placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Mom WhatsApp No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="mwno" placeholder="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Self Mobile No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="smno" placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Self WhatsApp No</label>
                                                    <input type="text" maxlength="10" class="form-control" name="swno" placeholder="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date of Birth:</label>
                                                    <div class="input-group date" id="dobdate" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#dobdate" name="dob" required />
                                                        <div class="input-group-append" data-target="#dobdate" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date of Join:</label>
                                                    <div class="input-group date" id="dojdate" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#dojdate" name="doj" required />
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
                                            <textarea style="resize:none" class="form-control" rows="3" name="address"></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Branch</label>
                                                    <select class="form-control select2  " style="width: 100%;" name="branch_id" id="branch_id" required>
                                                        <option disabled selected value>Select Branch</option>
                                                        <?php 
												if ($resultForBranch->num_rows > 0) {
													while($rows = $resultForBranch->fetch_assoc()) {
														?>
                                                        <option data-select2-id="30" value="<?php echo $rows["branch_id"]; ?>"><?php echo $rows["name"]; ?></option>
                                                        <?php
													}
												} else {
													?>
                                                        <option disabled value>No Branch Found.</option>
                                                        <?php
												}
												?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Pincode</label>
                                                    <input type="number" maxlength="6" class="form-control" name="pincode" placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Education</label>
                                                    <select id="std" class="form-control select2 " style="width: 100%;" name="std" required>
                                                        <option disabled selected value>Select Education</option>
                                                        <option data-select2-id="30" value="N/A">N/A</option>
                                                        <option data-select2-id="30" value="1">1</option>
                                                        <option data-select2-id="30" value="2">2</option>
                                                        <option data-select2-id="30" value="3">3</option>
                                                        <option data-select2-id="30" value="4">4</option>
                                                        <option data-select2-id="30" value="5">5</option>
                                                        <option data-select2-id="30" value="6">6</option>
                                                        <option data-select2-id="30" value="7">7</option>
                                                        <option data-select2-id="30" value="8">8</option>
                                                        <option data-select2-id="30" value="9">9</option>
                                                        <option data-select2-id="30" value="10">10</option>
                                                        <option data-select2-id="30" value="11">11</option>
                                                        <option data-select2-id="30" value="12">12</option>
                                                        <option data-select2-id="30" value="Under Graduate">Under Graduate</option>
                                                        <option data-select2-id="30" value="Graduate">Graduate</option>
                                                        <option data-select2-id="30" value="Masters">Masters</option>
                                                        <option data-select2-id="30" value="Phd">Phd</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Fees</label>
                                                    <input type="number" maxlength="6" class="form-control" name="fees" placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-8">

                                                <label for="exampleInputEmail1">Select Paid Months</label>
                                                <div class="row form-group">
                                                    <?php
                                                    $month = date("m");
                                                    $date = date("d");
                                                    $disabled1 = "";
                                                    $disabled2 = "";
                                                    $disabled3 = "";
                                                    if (($month == 1) || ($month == 2) || ($month == 3)){
                                                        if ($month == 1){
                                                            if ($date >20){
                                                            $disabled1 = "disabled";
                                                            include_once( "quarter_1.php" );
                                                            }
                                                            include_once( "quarter_1.php" );
                                                        } elseif ($month == 2){
                                                            $disabled1 = "disabled";
                                                            if ($date >20){
                                                                $disabled2 = "disabled";
                                                                include_once( "quarter_1.php" );
                                                            } else {
                                                                $disabled2 = "";
                                                                include_once( "quarter_1.php" );
                                                            }
                                                        } elseif ($month == 3){
                                                            if ($date >20){
                                                                $tempbool = false;
                                                                $disabled1 = "";
                                                                $disabled2 = "";
                                                                include_once( "quarter_2.php" );
                                                            } else {
                                                                $disabled1 = "disabled";
                                                                $disabled2 = "disabled";
                                                                include_once( "quarter_1.php" );
                                                            }
                                                        }
                                                    }
                                                    if (($month == 4) || ($month == 5) || ($month == 6)){
                                                        if ($month == 4){
                                                            if ($date >20){
                                                            $disabled1 = "disabled";
                                                            }
                                                            include_once( "quarter_2.php" );
                                                        } elseif ($month == 5){
                                                            $disabled1 = "disabled";
                                                            if ($date >20){
                                                                $disabled2 = "disabled";
                                                                include_once( "quarter_2.php" );
                                                            } else {
                                                                $disabled2 = "";
                                                                include_once( "quarter_2.php" );
                                                            }
                                                        } elseif ($month == 6){
                                                            if ($date >20){
                                                                $tempbool = false;
                                                                $disabled1 = "";
                                                                $disabled2 = "";
                                                                include_once( "quarter_3.php" );
                                                            } else {
                                                                $disabled1 = "disabled";
                                                                $disabled2 = "disabled";
                                                                include_once( "quarter_2.php" );
                                                            }
                                                        }
                                                    }
                                                    if (($month == 7) || ($month == 8) || ($month == 9)){
                                                        if ($month == 7){
                                                            if ($date >20){
                                                            $disabled1 = "disabled";
                                                            }
                                                            include_once( "quarter_3.php" );
                                                        } elseif ($month == 8){
                                                            $disabled1 = "disabled";
                                                            if ($date >20){
                                                                $disabled2 = "disabled";
                                                                include_once( "quarter_3.php" );
                                                            } else {
                                                                $disabled2 = "";
                                                                include_once( "quarter_3.php" );
                                                            }
                                                        } elseif ($month == 9){
                                                            if ($date >20){
                                                                $tempbool = false;
                                                                $disabled1 = "";
                                                                $disabled2 = "";
                                                                include_once( "quarter_4.php" );
                                                            } else {
                                                                $disabled1 = "disabled";
                                                                $disabled2 = "disabled";
                                                                include_once( "quarter_3.php" );
                                                            }
                                                        }
                                                    }
                                                    if (($month == 10) || ($month == 11) || ($month == 12)){
                                                        if ($month == 10 ){
                                                            if ($date >20){
                                                            $disabled1 = "disabled";
                                                            }
                                                            include( "quarter_4.php" );
                                                        } elseif ($month == 11){
                                                            $disabled1 = "disabled";
                                                            if ($date >20){
                                                                $disabled2 = "disabled";
                                                                include( "quarter_4.php" );
                                                            } else {
                                                                $disabled2 = "";
                                                                include( "quarter_4.php" );
                                                            }
                                                        } elseif ($month == 12){
                                                            if ($date >20){
                                                                $tempbool = false;
                                                                $disabled1 = "";
                                                                $disabled2 = "";
                                                                include( "quarter_1.php" );
                                                            } else {
                                                                $disabled1 = "disabled";
                                                                $disabled2 = "disabled";
                                                                include( "quarter_4.php" );
                                                            }
                                                        }
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
    <script type="text/javascript">
        function passId(val) {
            $("#userid").val(val); //set the id to the input on the modal
        }

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
