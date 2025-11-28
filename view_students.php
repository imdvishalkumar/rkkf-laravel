<?php

include_once("auth.php"); //include auth.php file on all secure pages
$page="view_students";
include_once("connection.php");
include_once("page_title.php");

$submitClick = isset($_POST['submit']);
$delClick = isset($_POST['delete']);
$resetClick = isset($_POST['reset']);
$deactiveClick = isset($_POST['deactive']);
$searchClick = isset($_POST['search']);
if($deactiveClick) {
	$id=$_POST['student_id'];
    $query = "update students set active = 0 WHERE student_id=$id";
    $result = mysqli_query($con,$query);
    if($result){
                $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-success alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-check'></i> Alert!</h5>
                      Student Deactivated Successfully.
                    </div></div>";
    } else {
                    $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                      Error deactivating Student.
                    </div></div>";
    }
} else if($delClick) {
	$id=$_POST['student_id'];
    $query = "delete from students WHERE student_id=$id";
    $result = mysqli_query($con,$query);
    if($result){
                $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-success alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-check'></i> Alert!</h5>
                      Student Deleted Successfully.
                    </div></div>";
    } else {
                    $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                      Error deleting Student.
                    </div></div>";
    }
} else if($resetClick) {
	$id=$_POST['student_id'];
    $passwordQuery = "SELECT selfno FROM students WHERE student_id = $id";
    $result = mysqli_query($con,$passwordQuery);
    $row = mysqli_fetch_row($result);
    $password = $row[0];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE `students` SET `password` = '".$hash."' WHERE `students`.`student_id` = $id";
    $result = mysqli_query($con,$query);
    if($result){
                $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-success alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-check'></i> Alert!</h5>
                      Student password changed Successfully to $password.
                    </div></div>";
    } else {
                    $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                      Error changing Student's password.
                    </div></div>";
    }
}
	$sql = "SELECT s.*, br.name as branch_name,(select name from belt where belt_id = s.belt_id) as belt_name FROM students s , branch br where s.branch_id = br.branch_id AND s.active = 1";
	$result = $con->query($sql);

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
                        <?php if ($submitClick) {
                            echo $alertMsg;
                        } if ($resetClick) {
                            echo $alertMsg;
                        }
                        if ($delClick) {
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
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Student Details</h3>
                                            <div class="card-tools">
                                                <div class="row">
                                                    <div class="col-sm-3 my-auto">
                                                        <div class="input-group date" id="startdate" data-target-input="nearest">
                                                            <input type="text" placeholder="From Joining Date" class="form-control datetimepicker-input" data-target="#startdate" name="startdate" onchange="get_studentInfo()" required />
                                                            <div class="input-group-append" data-target="#startdate" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 my-auto">
                                                        <div class="input-group date" id="enddate" data-target-input="nearest">
                                                            <input type="text" placeholder="To Joining Date" class="form-control datetimepicker-input" data-target="#enddate" name="enddate" onchange="get_studentInfo()" required />
                                                            <div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 my-auto">
                                                        <select class="form-control select2" name="belt_id" id="belt_id" onchange="get_studentInfo()" required>
                                                            <option disabled selected value="0">Select Belt</option>
                                                            <option data-select2-id="30" value="0">All</option>
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
                                                    <div class="col-sm-3 my-auto">
                                                        <select class="form-control select2" name="branch_id" id="branch_id" onchange="get_studentInfo()" required>
                                                            <option disabled selected value="0">Select Branch</option>
                                                            <option data-select2-id="30" value="0">All</option>
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
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body" id="infoDiv">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>GR No</th>
                                                        <th>Action</th>
                                                        <th>Branch</th>
                                                        <th>Name</th>
                                                        <th>Belt</th>
                                                        <th>Std</th>
                                                        <th>Gender</th>
                                                        <th>Email</th>
                                                        <th>Contact</th>
                                                        <th>WhatsApp <i class='fab fa-whatsapp'></i></th>
                                                        <th>DOB</th>
                                                        <th>DOJ</th>
                                                        <th>Address</th>
                                                        <th>Call Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
     $today = date("Y-m-d");
    $diffyear = date_diff(date_create($row["dob"]), date_create($today));
    $diff = $diffyear->format('%y');
    $tempColor = "#ffffff";
    
    if($diff <= 10)
    {
            $tempColor = "#ffffff";
    }
    else if($diff > 10 && $diff < 14)
    {
                    $tempColor = "#ffef00";
    }
    else 
    {
                  $tempColor = "#bd162c";
    }
    

    echo "<tr>  <td bgcolor='".$tempColor."'>" . $row["student_id"]. "</td>
          <td>
          <div class='text-center'>
				<a href='edit_student.php?id=".$row['student_id']."' class='edit-user action-icon' title='Edit'>
				<span class='fas fa-edit'></span>
				</a><br>
				<button id='btnModal' value='".$row['student_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deactiveModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-times'></span></button><br>
				<button id='btnModal' value='".$row['student_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
				<button id='btnModal' value='".$row['student_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#resetModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-sync-alt'></span></button>
				
		  </div>
          </td>
          <td>" . $row["branch_name"]. "</td>
          <td>" . $row["firstname"] . " " . $row["lastname"] . "</td>
          <td>" . $row["belt_name"] . "</td>
          <td>" . $row["std"] . "</td>
		  <td>";
          if ($row["gender"] == 1){
            echo "Male";
          } else {
            echo "Female";
          }
          echo "</td>
		  <td>" . $row["email"]. "</td>
          <td>";
          if (!empty($row["dadno"])){
            echo $row["dadno"] . " ";
          }
          if (!empty($row["momno"])){
            echo $row["momno"] . " ";
          }
          if (!empty($row["selfno"])){
            echo $row["selfno"] . " ";
          }
          echo "</td><td>";
          if (!empty($row["dadwp"])){ /*<i class='fab fa-whatsapp'></i>*/
            echo $row["dadwp"] . "\n";
          }
          if (!empty($row["momwp"])){
            echo $row["momwp"] . "\n";
          }
          if (!empty($row["selfwp"])){
            echo $row["selfwp"] . "\n";
          }
          $str = 'Call';
          $setClass="";
          if($row["call_flag"] == 1){
            $str = 'Called';
            $setClass = 'disabled';
          }
          echo "</td>
          <td>" . $row["dob"]. "</td>
          <td>" . $row["doj"]. "</td>
          <td>" . $row["address"]. " " . $row["pincode"] . "</td>         
          <td>" . '<a href="javascript:void(0);" class="btn btn-success changeCallFlag '.$setClass.'" data-id="'.$row["student_id"].'">'.$str.'</a>' . "</td>		  
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
                            <div class="modal fade" id="deactiveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Deactive User.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="student_id" id="student_id" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="deactive" class="btn btn-danger">Deactive</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                                                <input type="text" name="student_id" id="del_student_id" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Reset Password.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="student_id" id="reset_student_id" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="reset" class="btn btn-danger">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

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
        //DOB range picker
        $('#startdate').datetimepicker({
            format: "YYYY-MM-DD",
        });
        $('#enddate').datetimepicker({
            format: "YYYY-MM-DD",
        });

        $("#startdate").on("change.datetimepicker", ({
            date,
            oldDate
        }) => {
            get_studentInfo();
        });

        $("#enddate").on("change.datetimepicker", ({
            date,
            oldDate
        }) => {
            get_studentInfo();
        });


    });

</script>

    <script type="text/javascript">
        function passId(val) {
            $("#student_id").val(val); //set the id to the input on the modal
            $("#del_student_id").val(val); //set the id to the input on the modal
            $("#reset_student_id").val(val); //set the id to the input on the modal
        }

    </script>
     <script>

        function get_studentInfo() { // Call to ajax function
            var branchId = $('#branch_id').val();
            var beltId = $('#belt_id').val();
            var startdate = $("input[name=startdate]").val();
            var enddate = $("input[name=enddate]").val();
             
                $.ajax({
                    type: "POST",
                    url: "view_students_by_branch_ajax.php", // Name of the php files
                    data: {
                        branch_id: branchId,
                        belt_id: beltId,
                        start_date: startdate,
                        end_date: enddate
                    },
                    success: function(html) {
                        $("#infoDiv").html(html);
                    }
                });
            
        }

        $(document).on("click",".changeCallFlag",function() {
            var stuId = $(this).attr('data-id');
            swal({
                title: "Change Flag",
                text: "Are you sure ? You want to change flag for this particular student ?  ",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm){
              if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: "set_status_ajax.php", 
                    data: {
                        'stuId': stuId,
                        'from': 1, //update student table
                    },
                    success: function(response) {
                        console.log(response);
                        window.location.reload(true);
                        /*swal({
                            title: "Flag Changed..",
                            text: "Student flag set Successfully",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Okay",
                            closeOnConfirm: true,
                        }).then(function(isConfirm) {
                            if (isConfirm) {
                                window.location.reload(true);
                            } 
                        })*/
                    }
                });
              } 
            });
        });

    </script>

</body>

</html>
