<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page = "exam";
include_once ("connection.php");
include_once("page_title.php");
$submitClick = isset($_POST['submit']);
$delClick = isset($_POST['delete']);
$publishClick = isset($_POST['publish']);
if ($publishClick)
{
    $id = $_POST['exam_id'];
    $query = "UPDATE `exam` SET `isPublished` = '1' WHERE `exam`.`exam_id` = '$id';";
    $result = mysqli_query($con, $query);
    if ($result)
    {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Exam Published Successfully.
                </div></div>";
    }
    else
    {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error publishing Exam.
                </div></div>";
    }
}if ($delClick)
{
    $id = $_POST['exam_id'];
    $query = "DELETE FROM exam WHERE exam_id=$id";
    $result = mysqli_query($con, $query);
    if ($result)
    {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Exam Deleted Successfully.
                </div></div>";
    }
    else
    {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error deleting Exam.
                </div></div>";
    }
}
if ($submitClick)
{
    $ename = $_POST['exam_name'];
    $date = $_POST['exam_date'];
    $tdate = str_replace('/', '-', $date);
    $exam_date = date('Y-m-d', strtotime($tdate));

    $session_count = $_POST['exam_atten'];

    $from_date = $_POST['from_date_cri'];
    $tdate = str_replace('/', '-', $from_date);
    $from_date = date('Y-m-d', strtotime($tdate));

    $to_date = $_POST['to_date_cri'];
    $tdate = str_replace('/', '-', $to_date);
    $to_date = date('Y-m-d', strtotime($tdate));

    $due_date = $_POST['exam_fees_due_date'];
    $tdate = str_replace('/', '-', $due_date);
    $due_date = date('Y-m-d', strtotime($tdate));

    $fees = $_POST['exam_fees'];
    $nfees = ((100/90)*((int)$fees));
    $rounded = ceil($nfees / 10) * 10;

    $sql = "select * from exam where name='$ename';";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0)
    {
        // output data of each row
        $row = mysqli_fetch_assoc($res);
        if (isset($bname))
        {
            if ($bname == $row['name'])
            {
                $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-danger alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                      Exam already exists.
                    </div></div>";
            }
        }
    }
    else
    {

        $q = "insert into exam (name,date,sessions_count,fees,fess_due_date,from_criteria,to_criteria) values('$ename','$exam_date','$session_count','$rounded','$due_date','$from_date','$to_date')";
        if (mysqli_query($con, $q))
        {
            $selectStudentIds = "select student_id from students;";
            $resStudentIds = mysqli_query($con, $selectStudentIds);
            $time = date('Y-m-d H:i:s');
            if (mysqli_num_rows($resStudentIds) > 0)
            {
                $bool = false;
                // output data of each row
                while($row = mysqli_fetch_assoc($resStudentIds)) 
                {
                    $q = "insert into notification (title,details,student_id,viewed,type,sent,timestamp) VALUES ('Exam','".$ename." is on ".$exam_date."','".$row['student_id']."','0','exam','0','".$time."')";
                    if (mysqli_query($con, $q)) {
                        $bool = true;
                    } else {
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
                          Exam Added Successfully.
                        </div></div>";                    
                }
            }
        }
    }
}

$sql = "SELECT * FROM exam";
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
                        <?php if ($submitClick)
    if (isset($alertMsg))
			echo $alertMsg;
		if ($delClick){
			echo $alertMsg;
		}
		if (isset($_SESSION["examUpdated"])){
			if ($_SESSION["examUpdated"] == "yes"){
			unset($_SESSION['examUpdated']);
			$alertMsg = "<div class='row'>
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Exam Updated Successfully.
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
                                            <h3 class="card-title">Exam Details</h3>

                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Exam Name</th>
                                                        <th>Date</th>
                                                        <th>Sessions Count</th>
                                                        <th>Fees</th>
                                                        <th>Fees Due Date</th>
                                                        <th>Published</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr>  <td>" . $row["name"]. "</td>
		  <td>" . $row["date"]. "</td>
		  <td>" . $row["sessions_count"]. "</td>
		  <td>" . $row["fees"]. "</td>
		  <td>" . $row["fess_due_date"]. "</td> <td>";
          if ($row["isPublished"] == '1') {
              echo "Yes";
          } else {
            echo "No";
          }
          echo"
          </td>
		  <td>
          <div class='text-center'>
				<a href='eligible_students.php?exam_id=".$row['exam_id']."' class='edit-user action-icon' title='Edit'>
				<span class='fas fa-file'></span>
				</a>&nbsp;&nbsp;
                <a href='edit_exam.php?id=".$row['exam_id']."' class='edit-user action-icon' title='Edit'>
				<span class='fas fa-edit'></span>
				</a>&nbsp;&nbsp;
				<button id='btnModal' value='".$row['exam_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#publishModal' class='publish-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-upload'></span></button>
				<button id='btnModal' value='".$row['exam_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
		  </div>
          </td>
		  
		  </tr>";
  }
} else {
 ?><tr>

                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
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
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Exam.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="exam_id" id="exam_id1" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- Modal -->
                            <div class="modal fade" id="publishModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Publish Exam.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="exam_id" id="exam_id" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="publish" class="btn btn-danger">Publish</button>
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
                                    <h3 class="card-title">Add Exam</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" id="quickForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Exam Name</label>
                                            <input type="text" class="form-control" name="exam_name" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Date</label>
                                            <div class="input-group date" id="exam_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#exam_date" name="exam_date" required />
                                                <div class="input-group-append" data-target="#exam_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Session Count</label>
                                            <input type="number" class="form-control" name="exam_atten" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label>From Date Criteria</label>
                                            <div class="input-group date" id="from_date_cri" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#from_date_cri" name="from_date_cri" required />
                                                <div class="input-group-append" data-target="#from_date_cri" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>To Date Criteria</label>
                                            <div class="input-group date" id="to_date_cri" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#to_date_cri" name="to_date_cri" required />
                                                <div class="input-group-append" data-target="#to_date_cri" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Fees</label>
                                            <input type="number" class="form-control" name="exam_fees" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Fees Due Date</label>
                                            <div class="input-group date" id="exam_fees_due_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#exam_fees_due_date" name="exam_fees_due_date" required />
                                                <div class="input-group-append" data-target="#exam_fees_due_date" data-toggle="datetimepicker">
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
    <script>
        $(function() {
            //Date range picker
            $('#exam_date').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            $('#from_date_cri').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            $('#to_date_cri').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            $('#exam_fees_due_date').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        })

    </script>
    
    <script type="text/javascript">
        function passId(val) {
            $("#exam_id").val(val);
            $("#exam_id1").val(val); //set the id to the input on the modal
        }
    </script>

</body>
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
                    exam_name: {
                        required: true,
                        rangelength: [1, 32]
                    },
                    exam_date: {
                        required: true,
                    },
                    exam_atten: {
                        required: true,
                        numbersonly: true,
                    },
                    from_date_cri: {
                        required: true,
                    },
                   to_date_cri: {
                        required: true,
                    },
                    exam_fees: {
                        required: true,
                    },
                    exam_fees_due_date: {
                        required: true,
                    }, 
                },
                messages: {
                    exam_name: {
                        required: "Please enter a Name",
                        rangelength: "Please enter Valid Name"
                    },
                    exam_date: {
                        required: "Please Enter Date",
                    },
                    exam_atten: {
                        required: "Please Enter Value",
                    },
                   from_date_cri: {
                        required: "Please Enter Date",
                    },
                    to_date_cri: {
                        required: "Please Enter Date",
                    },
                    exam_fees: {
                        required: "Please Enter Value",
                    },
                    exam_fees_due_date: {
                        required: "Please Enter Date",
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

</html>
