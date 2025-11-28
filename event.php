<?php
include_once ("auth.php"); //include auth.php file on all secure pages
$page = "event";
include_once ("connection.php");
include_once ("page_title.php");
$submitClick = isset($_POST['submit']);
$delClick = isset($_POST['delete']);
$searchClick = isset($_POST['search']);
$publishClick = isset($_POST['publish']);
if ($publishClick)
{
    $id = $_POST['event_id'];
    $query = "UPDATE `event` SET `isPublished` = '1' WHERE `event`.`event_id` = '$id';";
    $result = mysqli_query($con, $query);
    if ($result)
    {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Event Published Successfully.
                </div></div>";
    }
    else
    {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error publishing Event.
                </div></div>";
    }
}
if ($delClick)
{
    $id = $_POST['event_id'];
    $query = "DELETE FROM event WHERE event_id=$id";
    $result = mysqli_query($con, $query);
    if ($result)
    {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Event Deleted Successfully.
                </div></div>";
    }
    else
    {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error deleting Event.
                </div></div>";
    }
}
if ($submitClick)
{
    $ename = $_POST['event_name'];

    $fromDate = $_POST['event_from_date'];
    $tdate = str_replace('/', '-', $fromDate);
    $fromDate = date('Y-m-d', strtotime($tdate));

    $toDate = $_POST['event_to_date'];
    $tdate = str_replace('/', '-', $toDate);
    $toDate = date('Y-m-d', strtotime($tdate));

    $venue = $_POST['event_venue'];
    $type = $_POST['event_type'];
    $desc = $_POST['event_desc'];
    $fees = $_POST['event_fees'];

    $feesDueDate = $_POST['event_due_date'];
    $tdate = str_replace('/', '-', $feesDueDate);
    $feesDueDate = date('Y-m-d', strtotime($tdate));

    $penaltyFees = $_POST['event_penalty_fees'];

    $penaltyDueDate = $_POST['event_penalty_due_date'];
    $tdate = str_replace('/', '-', $penaltyDueDate);
    $penaltyDueDate = date('Y-m-d', strtotime($tdate));

    $sql = "select * from event where name='$ename';";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0)
    {
        // output data of each row
        $row = mysqli_fetch_assoc($res);
        if (isset($ename))
        {
            if ($ename == $row['name'])
            {
                $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Event already exists.
                </div></div>";
            }
        }
    }
    else
    {
        $q = "insert into event (name,from_date,to_date,venue,type,description,fees,fees_due_date,penalty,penalty_due_date) values('$ename','$fromDate','$toDate','$venue','$type','$desc','$fees','$feesDueDate','$penaltyFees','$penaltyDueDate')";

        if (mysqli_query($con, $q))
        {

            $selectStudentIds = "select student_id from students;";
            $resStudentIds = mysqli_query($con, $selectStudentIds);
            $time = date('Y-m-d H:i:s');
            if (mysqli_num_rows($resStudentIds) > 0)
            {
                $bool = false;
                // output data of each row
                while ($row = mysqli_fetch_assoc($resStudentIds))
                {
                    $q = "insert into notification (title,details,student_id,viewed,type,sent,timestamp) VALUES ('Event','".$ename." is on ".$fromDate."','".$row['student_id']."','0','exam','0','".$time."')";
                    if (mysqli_query($con, $q))
                    {
                        $bool = true;
                    }
                    else
                    {
                        $bool = false;
                    }
                }
                if ($bool)
                {
                    $alertMsg = "
                        <div class='col-md-12'>
                        <div class='alert alert-success alert-dismissible'>
                          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                          <h5><i class='icon fas fa-check'></i> Alert!</h5>
                          Event Added Successfully.
                        </div></div>";
                }
            }

        }
    }
}

$sql = "SELECT * FROM event";
$result = $con->query($sql);

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
                        <?php if ($submitClick) if (isset($alertMsg))
			echo $alertMsg;
		if ($delClick){
			echo $alertMsg;
		}
		if ($publishClick){
			echo $alertMsg;
		}
		if (isset($_SESSION["eventUpdated"])){
			if ($_SESSION["eventUpdated"] == "yes"){
			unset($_SESSION['eventUpdated']);
			$alertMsg = "<div class='row'>
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Event Updated Successfully.
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
                                            <h3 class="card-title">Event Details</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Event Name</th>
                                                        <th>Date</th>
                                                        <th>Venue</th>
                                                        <th>Type</th>
                                                        <th>Description</th>
                                                        <th>Fees</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr>  <td>" . $row["name"]. "</td>
		  <td>" . $row["from_date"]. "</td>
		  <td>" . $row["venue"]. "</td>
		  <td>" . $row["type"]. "</td>
		  <td>" . $row["description"]. "</td>
		  <td>" . $row["fees"]. "</td>
		  <td>
          <div class='text-center'>
				<a href='eligible_students_event.php?event_id=".$row['event_id']."' class='edit-user action-icon' title='Edit'>
				<span class='fas fa-file'></span>
				</a>&nbsp;&nbsp;
				<a href='edit_event.php?id=".$row['event_id']."' class='edit-user action-icon' title='Edit'>
				<span class='fas fa-edit'></span>
				</a>&nbsp;&nbsp;";
				if ($row["isPublished"] == "0") {
        			echo "
        			<button id='btnModal' value='".$row['event_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#publishModal' class='publish-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-upload'></span></button>";
        			    
				}
				echo "
				<button id='btnModal' value='".$row['event_id']."' onclick='passId(this.value)' data-toggle='modal' data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
		  </div>
          </td>
		  
		  </tr>";
  }
} else {
 ?>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <?php
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
                            <!-- Button trigger modal --><!-- Modal -->
                            <div class="modal fade" id="publishModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Publish Event.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="event_id" id="event_id" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="publish" class="btn btn-danger">Publish</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Event.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body"> Are you sure?
                                                <input type="text" name="event_id" id="event_id" hidden />
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
                                    <h3 class="card-title">Add Event</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" id="quickForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Event Name</label>
                                            <input type="text" class="form-control" name="event_name" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <div class="input-group date" id="event_from_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#event_from_date" name="event_from_date" required />
                                                <div class="input-group-append" data-target="#event_from_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <div class="input-group date" id="event_to_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#event_to_date" name="event_to_date" required />
                                                <div class="input-group-append" data-target="#event_to_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Venue</label>
                                            <input type="text" class="form-control" name="event_venue" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Type</label>
                                            <input type="text" class="form-control" name="event_type" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Description</label>
                                            <input type="text" class="form-control" name="event_desc" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Fees</label>
                                            <input type="number" class="form-control" name="event_fees" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Fees Due Date</label>
                                            <div class="input-group date" id="event_due_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#event_due_date" name="event_due_date" required />
                                                <div class="input-group-append" data-target="#event_due_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Penalty Amount</label>
                                            <input type="number" class="form-control" name="event_penalty_fees" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Penalty Due Date</label>
                                            <div class="input-group date" id="event_penalty_due_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#event_penalty_due_date" name="event_penalty_due_date" required />
                                                <div class="input-group-append" data-target="#event_penalty_due_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
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
                </div>
                <!-- /.container-fluid -->
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
    <script>
        $(function() {
            //Date range picker
            $('#event_from_date').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            $('#event_to_date').datetimepicker({
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
    <script type="text/javascript">
        function passId(val) {
            $("#event_id").val(val); //set the id to the input on the modal
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
        $(function() {
            $('#quickForm').validate({
                rules: {
                    event_name: {
                        required: true,
                        rangelength: [4, 32]
                    },
                    event_date: {
                        required: true,
                    },
                    event_venue: {
                        required: true
                    },
                    event_type: {
                        required: true

                    },
                    event_desc: {
                        required: true
                    },
                    event_fees: {
                        required: true,
                        numbersonly: true,
                    },
                },
                messages: {
                    event_name: {
                        required: "Please enter a Name",
                        rangelength: "Please enter Valid Name"
                    },
                    event_date: {
                        required: "Please Enter Date",
                    },
                    event_venue: {
                        required: "Please Enter Value",
                    },
                    event_type: {
                        required: "Please Enter Date",
                    },
                    event_desc: {
                        required: "Please Enter Date",
                    },
                    event_fees: {
                        required: "Please Enter Value",
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
