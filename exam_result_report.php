<?php
include_once ("auth.php"); //include auth.php file on all secure pages
$page = "exam_result";
include_once ("connection.php");
include_once ("page_title.php");

$delClick = isset($_POST['delete']);
if ($delClick)
{
    $id = $_POST['fee_id'];
    $query = "delete from fees WHERE fee_id=$id";
    $result = mysqli_query($con, $query);
    if ($result)
    {
        $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-success alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-check'></i> Alert!</h5>
                      Fee Deleted Successfully.
                    </div></div>";
    }
    else
    {
        $alertMsg = "
                    <div class='col-md-12'>
                    <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                      <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                      Error deleting Fee.
                    </div></div>";
    }
}

$queryForBranch = "select * from exam";
$resultForBranch = $con->query($queryForBranch);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <?php include_once ("head_link.php"); ?>
</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">
    <div class="wrapper">

        <?php include_once ("navbar.php"); ?>

        <?php include_once ("side_menu.php"); ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <?php include_once ("content_header.php"); ?>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Exam Result Details</h3>
                                            <div class="card-tools">
                                                <div class="row">
                                                    
                                                    <div class="col-sm-12 my-auto">
                                                        <select class="form-control select2  " style="width: 100%;" name="branch_id" id="branch_id" onchange="get_studentInfo()" required>
                                                        <option disabled selected value>Select Exam</option>
                                                        <?php
if ($resultForBranch->num_rows > 0)
{
    while ($rows = $resultForBranch->fetch_assoc())
    {
?>
                                                        <option data-select2-id="30" value="<?php echo $rows["exam_id"]; ?>"><?php echo $rows["name"]; ?></option>
                                                        <?php
    }
}
else
{
?>
                                                        <option disabled value>No Exam Found.</option>
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
                                            
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->

                                </div>
                            </div>
                            <!-- Button trigger modal -->

                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include_once ("footer.php"); ?>



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

        function get_studentInfo() { // Call to ajax function
            console.log('func called.');
            var branchId = $('#branch_id').val();
            $.ajax({
                type: "POST",
                url: "exam_result_report_ajax.php", // Name of the php files
                data: {
                    branch_id: branchId
                },
                success: function(html) {
                    console.log('func responsed.');
                    $("#infoDiv").html(html);
                }
            });
        }


    </script>

</body>

</html>
