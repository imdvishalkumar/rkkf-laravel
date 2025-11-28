<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="special_exam";

include_once("connection.php");
include_once("page_title.php");

$submitClick = isset($_POST['submit']);
$delClick = isset($_POST['delete']);

if($submitClick) {
    $id=$_POST['disable_student_id'];
    $exam_id=$_POST['disable_exam_id'];
    
    $checkQuery = "SELECT * FROM special_case_exam WHERE student_id = '".$id."' AND exam_id = '".$exam_id."';";
    $result = mysqli_query($con,$checkQuery);
    $rowcount=mysqli_num_rows($result);
    if($rowcount > 0) {
        $alertMsg = "
            <div class='col-md-12'>
            <div class='alert alert-danger alert-dismissible' style='height:80px;'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
              <h5><i class='icon fas fa-ban'></i> Alert!</h5>
              Error making Student eligible - Eligible entry already exists.
            </div></div>";
    } else {
        $feeQuery = "INSERT INTO special_case_exam (student_id,exam_id) values ($id,$exam_id)";
        $result = mysqli_query($con,$feeQuery);
        if($result)
        {
            $alertMsg = "
                <div class='col-md-12'>
                <div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Student made eligible Successfully.
                </div></div>";
        } else
        {
            $alertMsg = "
                <div class='col-md-12'>
                <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error making Student eligible.
                </div></div>";
        }
    }

}
if($delClick) {
    $id=$_POST['special_id'];
    $query = "DELETE FROM `special_case_exam` WHERE `special_case_exam`.`special_id` = $id";
    $result = mysqli_query($con,$query);
    if($result){
			$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Removed Successfully.
                </div></div>";
    } else {
				$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error Removing Student.
                </div></div>";
    }
}
$sql= "select f.special_id, f.eligible, e.name as exam_name ,concat(s.firstname,' ',s.lastname) as name, (SELECT name from branch where branch_id = s.branch_id) as branch_name, (SELECT name from belt where belt_id = s.belt_id) as current_blt_name, (SELECT name from belt where belt_id = s.belt_id+1) as next_blt_name from special_case_exam f, students s, exam e WHERE f.student_id = s.student_id AND e.exam_id = f.exam_id";

	//$sql = "select f.special_id, f.eligible, e.name as exam_name ,concat(s.firstname,' ',s.lastname) as name from special_case_exam f, students s, exam e WHERE f.student_id = s.student_id AND e.exam_id = f.exam_id";
	$resultTable = $con->query($sql);
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
                        <div class="col-md-12">
                            <div class="card card-primary collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Add Special eligiblity to Student</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body" style="display: none;">
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Enter GrNO OR Name</label>
                                            <input type="text" class="form-control" name="grno" id="grno" placeholder="" onchange="get_studentInfo()" required>
                                        </div>
                                        <div id="infoDiv" class="form-group">

                                        </div>
                                        <div id="infoDiv2" class="form-group">

                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Special Eligible Details</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Special Id</th>
                                                        <th>Name</th>
                                                        <th>Exam Name</th>
                                                        <th>Branch</th>
                                                        <th>Current Belt</th>
                                                        <th>Next Belt</th>
                                                        <th>Eligiblity</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($resultTable->num_rows > 0) {

  while($row = $resultTable->fetch_assoc()) {
    echo "<tr>  <td>" . $row["special_id"]. "</td>
		  <td>" . $row["name"] . "</td>
		  <td>" . $row["exam_name"]. "</td>
		  <td>" . $row["branch_name"]. "</td>
		  <td>" . $row["current_blt_name"]. "</td>
		  <td>" . $row["next_blt_name"]. "</td>
          <td>";
          if($row["eligible"] == '1') {
              echo "Yes";
          } else {
              echo "No";
          }
          echo "
		  </td><td>" . "
          <div class='text-center'>
            <button id='btnModal' value='".$row['special_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
		  </div>" . "</td>
		  </tr>";
  }
} else {
 ?><tr>

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
                                            <h5 class="modal-title" id="exampleModalLabel">Remove eligibility.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="special_id" id="special_id" hidden />

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="delete" class="btn btn-success">Remove</button>
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
            $("#special_id").val(val); //set the id to the input on the modal
        }

    </script>
    <script type="text/javascript">
        function feesInfo() { // Call to ajax function
            var student_id = $('#student_id').val();
            var dataString = "student_id=" + student_id;
            $.ajax({
                type: "POST",
                url: "getstudents.php", // Name of the php files
                data: dataString,
                success: function(html) {
                    $("#infoDiv2").html(html);
                }
            });
        }

    </script>
    <script>
        var timer = null;
        $("#grno").keydown(function() {
            clearTimeout(timer);
            timer = setTimeout(get_studentInfo, 0)
        });

        function get_studentInfo() { // Call to ajax function
            var grno = $('#grno').val();
            var dataString = "grno=" + grno;
            $.ajax({
                type: "POST",
                url: "special_ajax.php", // Name of the php files
                data: dataString,
                success: function(html) {
                    $("#infoDiv").html(html);
                    $("#infoDiv2").html("");
                }
            });
        }
        function feesInfo() { // Call to ajax function
            var grno = $('#disable_student_id').val();
            var dataString = "disable_student_id=" + grno;
            $.ajax({
                type: "POST",
                url: "special_ajax.php", // Name of the php files
                data: dataString,
                success: function(html) {
                    $("#infoDiv2").html(html);
                }
            });
        }

    </script>
</body>

</html>
