<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="branch";
include_once("connection.php");
include_once("page_title.php");
$submitClick = isset($_POST['submit']);
$delClick = isset($_POST['delete']);
$searchClick = isset($_POST['search']);
if($delClick) {
	$id=$_POST['branch_id'];
$query = "DELETE FROM branch WHERE branch_id=$id";
$result = mysqli_query($con,$query);
if($result){
			$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Branch Deleted Successfully.
                </div></div>";
} else {
				$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error deleting Branch.
                </div></div>";
}
}
if($submitClick) {
    if (isset($_POST['transfer']))  //transfer branch
    {    
        $from = $_POST['from_branch_id'];
        $to = $_POST['to_branch_id'];
        //get student count in from branch
        $queryforCount = "select student_id from students where branch_id = $from";
        $s_count = 0;
        $res=mysqli_query($con,$queryforCount);
        if (mysqli_num_rows($res) > 0) {
            $s_count = mysqli_num_rows($res);
        }
        $update = "update students set branch_id = $to where branch_id = $from";
        if(mysqli_query($con,$update))
        {
            $alertMsg = "
            <div class='col-md-12'>
            <div class='alert alert-success alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
            <h5><i class='icon fas fa-check'></i> Alert!</h5>
            $s_count students transfered.
            </div></div>";
        } else {
            $alertMsg = "
            <div class='col-md-12'>
            <div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
            <h5><i class='icon fas fa-ban'></i> Alert!</h5>
            Error transfering branch.
            </div></div>";
        }
    }
    else  // add branch
    {
        $bname = $_POST['branch_name'];
        $bfees = $_POST['branch_fees'];
        $bdiscount = $_POST['discount'];
        $blate = $_POST['late'];
        $days = array();
        if(!empty($_POST['days'])){
            foreach($_POST['days'] as $day){
                $days[]=$day;
            }
        }
        $daysStr = implode(',', $days); 
        $sql="select * from branch where name='$bname';";
        $res=mysqli_query($con,$sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($bname==$row['name'])
            {
                $alertMsg = "
                <div class='col-md-12'>
                <div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                Branch already exists.
                </div></div>";
            }
        } else {
            $q="insert into branch (name,days,fees,late,discount) values('$bname','$daysStr','$bfees','$blate','$bdiscount')";
            if(mysqli_query($con,$q))
            {
                $alertMsg = "
                <div class='col-md-12'>
                <div class='alert alert-success alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h5><i class='icon fas fa-check'></i> Alert!</h5>
                Branch Added Successfully.
                </div></div>";
            }
        }
    }
}

	$sql = "SELECT * FROM branch";
	$result = $con->query($sql);

	$queryForBranch = "select * from branch";
	$resultForBranch = $con->query($queryForBranch);
	$resultForBranch2 = $con->query($queryForBranch);

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
		if (isset($_SESSION["branchUpdated"])){
			if ($_SESSION["branchUpdated"] == "yes"){
			unset($_SESSION['branchUpdated']);
			$alertMsg = "<div class='row'>
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Branch Updated Successfully.
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
                                            <h3 class="card-title">Branch Details</h3>


                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Branch Name</th>
                                                        <th>Fees</th>
                                                        <th>Late</th>
                                                        <th>Discount</th>
                                                        <th>Days</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr>  <td>" . $row["name"]. "</td>
		  <td>" . $row["fees"]. "</td>
		  <td>" . $row["late"]. "</td>
		  <td>" . $row["discount"]. "</td>
		  <td>" . $row["days"]. "</td>
		  <td>
          <div class='text-center'>
				<a href='edit_branch.php?id=".$row['branch_id']."' class='edit-user action-icon' title='Edit'>
				<span class='fas fa-edit'></span>
				</a>&nbsp;&nbsp;
				<button id='btnModal' value='".$row['branch_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
				
		  </div>
          </td>
		  
		  </tr>";
  }
} else {
 ?><tr>

                                                        <td>-</td>
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
                                                <input type="text" name="branch_id" id="branch_id" hidden />

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
                                    <h3 class="card-title">Add Branch</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form id="quickForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Branch Name</label>
                                            <input type="text" class="form-control" name="branch_name" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Branch Fees</label>
                                            <input type="number" class="form-control" name="branch_fees" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Late Fees</label>
                                            <input type="number" class="form-control" name="late" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Discount Fees</label>
                                            <input type="number" class="form-control" name="discount" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" name="days[]" value="Monday">
                                                <label for="customCheckbox1" class="custom-control-label">Monday</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox2" name="days[]" value="Tuesday">
                                                <label for="customCheckbox2" class="custom-control-label">Tuesday</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox3" name="days[]" value="Wednesday">
                                                <label for="customCheckbox3" class="custom-control-label">Wednesday</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox4" name="days[]" value="Thursday">
                                                <label for="customCheckbox4" class="custom-control-label">Thursday</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox5" name="days[]" value="Friday">
                                                <label for="customCheckbox5" class="custom-control-label">Friday</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox6" name="days[]" value="Saturday">
                                                <label for="customCheckbox6" class="custom-control-label">Saturday</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Transfer Branch</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>From Branch</label>
                                            <select id="branch_id" class="form-control select2" style="width: 100%;" name="from_branch_id" id="branch_id" required>
                                                <option disabled selected value>Select Branch</option>
                                                <?php 
												if ($resultForBranch->num_rows > 0) {
                                                    if ($resultForBranch->num_rows == 1)
                                                    {   ?>
                                                <option disabled value>No Branch Found to Transfer.</option>
                                                <?php
                                                    }
                                                    else {
                                                        while($rows = $resultForBranch->fetch_assoc()) {
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
                                            <label>To Branch</label>
                                            <select id="branch_id" class="form-control select2 " style="width: 100%;" name="to_branch_id" id="branch_id" required>
                                                <option disabled selected value>Select Branch</option>
                                                <?php 
												if ($resultForBranch2->num_rows > 0) {
                                                    if ($resultForBranch2->num_rows == 1)
                                                    {   ?>
                                                <option disabled value>No Branch Found to Transfer.</option>
                                                <?php
                                                    } 
                                                    else {
                                                        while($rows = $resultForBranch2->fetch_assoc()) {
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
                                        <input name="transfer" value="yes" hidden>

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
            $('select').on('change', function() {
            $('option').prop('disabled', false);
            $('select').each(function() {
                var val = this.value;
                $('select').not(this).find('option').filter(function() {
                    return this.value === val;
                }).prop('disabled', true);
            });
        }).change();
        });

    </script>
    <script type="text/javascript">
        function passId(val) {
            $("#branch_id").val(val); //set the id to the input on the modal
        }
    </script>
    
    <script>
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^\w+$/i.test(value);
        }, "Letters, numbers, and underscores only please");
        $.validator.addMethod("alphanumericspace", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s]*$/i.test(value);
        }, "Letters, numbers, and spaces only please");
        $.validator.addMethod("alphanumericspecial", function(value, element) {
            return this.optional(element) || /^[ A-Za-z0-9_@./#&+-\s]*$/i.test(value);
        }, "Letters, numbers, and special characters only please");
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z ]+$/i.test(value);
        }, "Letters only please");
        $.validator.addMethod("numbersonly", function(value, element) {
            return this.optional(element) || /^[0-9]+$/i.test(value);
        }, "Numbers only please");
        $(function() {
            $('#quickForm').validate({
                rules: {
                    branch_name: {
                        required: true,
                        alphanumericspecial: true,
                        rangelength: [1, 32]
                    },
                    'days[]': {
                        required: true
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
