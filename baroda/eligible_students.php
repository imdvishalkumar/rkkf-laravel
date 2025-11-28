<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="eligible_students";
include_once("connection.php");
include_once("page_title.php");
if(isset($_GET['exam_id'])) {
    $examId = $_GET['exam_id'];
    $sql = "SELECT s.student_id, e.exam_id, CONCAT(s.firstname, ' ', s.lastname) as name, b.name as branch_name, (SELECT name FROM belt WHERE belt_id = s.belt_id) as current_belt, IFNULL((SELECT DISTINCT ex.date FROM exam ex, exam_attendance ea WHERE ea.student_id = s.student_id AND ex.exam_id = ea.exam_id AND ea.attend = 'P' AND ex.isPublished = 1 ORDER BY ex.date DESC LIMIT 1),'Not Found') as last_exam_date ,IFNULL((SELECT DISTINCT eligible FROM special_case_exam WHERE exam_id = e.exam_id AND student_id = s.student_id),1) as atten_eligible, (( SELECT COUNT(fee_id) as count FROM fees WHERE student_id = s.student_id AND CAST(CONCAT(year,'-', months,'-01') as date) >= e.from_criteria AND CAST(CONCAT(year,'-', months,'-01') as date) <= e.to_criteria ) >= (SELECT TIMESTAMPDIFF(month, e.from_criteria, e.to_criteria) + 1 AS DateDiff) ) as fee_eligible FROM students s, branch b, exam e WHERE b.branch_id = s.branch_id AND s.active = 1 AND e.exam_id = '".$examId."' AND (SELECT count(student_id) FROM attendance WHERE student_id = s.student_id AND attend = 'P' AND date <= e.to_criteria AND date >= e.from_criteria) >= (e.sessions_count)";
    // echo $sql;
// 	$sql = "SELECT s.student_id, e.exam_id, CONCAT(s.firstname, ' ', s.lastname) as name, b.name as branch_name, IFNULL((SELECT ex.date FROM exam ex, exam_attendance ea WHERE ea.student_id = s.student_id AND ex.exam_id = ea.exam_id AND ea.attend = 'P' AND ex.isPublished = 1 ORDER BY ex.exam_id DESC LIMIT 1),'Not Found') as last_exam_date ,IFNULL((SELECT eligible FROM special_case_exam WHERE exam_id = e.exam_id AND student_id = s.student_id),1) as eligible FROM students s, branch b, exam e WHERE b.branch_id = s.branch_id AND s.active = 1 AND e.exam_id = '".$examId."' AND ( SELECT COUNT(fee_id) as count FROM fees WHERE student_id = s.student_id AND CAST(CONCAT(year,'-', months,'-01') as date) >= e.from_criteria AND CAST(CONCAT(year,'-', months,'-01') as date) <= e.to_criteria ) >= (SELECT TIMESTAMPDIFF(month, e.from_criteria, e.to_criteria) + 1 AS DateDiff);";
	$result = $con->query($sql);
// 	 $result = mysqli_query($con, $sql) or die (mysqli_error($con)); 

} else {
    header("Location: exam.php");
    exit();
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

            <?php include_once("content_header.php"); ?>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- ALERT SPACE -->
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Eligible Student Details</h3>
                                            <div class="card-tools">
                                                
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
                                                        <th>Current Belt</th>
                                                        <th>Name</th>
                                                        <th>Last Exam Date</th>
                                                        <th>Attendance Eligible</th>
                                                        <th>Fees Eligible</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr>  <td>" . $row["student_id"]. "</td>
          <td>
          <div class='text-center' id = '".$row['student_id'].'action'."'>";
          if($row["atten_eligible"] == '1') {
              echo "<button id='btnModal' value='".$row['student_id']."' onclick='eligibleStudent(".$row['student_id'].",".$row['exam_id'].",false)' style='padding: 0;border: none;background: none;'><span class='fas fa-times'></span></button>";
          }	
		echo"	
		  </div>
          </td>
          <td>" . $row["branch_name"]. "</td>
          <td>" . $row["current_belt"]. "</td>
          <td>" . $row["name"] . "</td>	  
          <td>" . $row["last_exam_date"] . "</td>	  
          <td id = '".$row['student_id'].'eligible'."'>";
          if($row["atten_eligible"] == '1') {
            echo "yes";  
          } else {
              echo "no";
          } echo "</td>	  	  
          <td>";
          if($row["fee_eligible"] == '1') {
            echo "yes";  
          } else {
              echo "no";
          } echo "</td>	  
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
        function eligibleStudent(studentId, examId, eligible) {
            var id = '#'+studentId + 'eligible';
            var id2 = '#'+studentId + 'action';
            $.ajax({
                    type: "POST",
                    url: "eligible_students_ajax.php", // Name of the php files
                    data: {
                        student_id: studentId,
                        exam_id: examId,
                        eligible: eligible
                    },
                    success: function(html) {
                        $(id).html('no');
                        $(id2).html('');
                    }
                });
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

    </script>

</body>

</html>
