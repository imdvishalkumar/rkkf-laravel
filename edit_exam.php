<?php
include_once ("auth.php"); //include auth.php file on all secure pages
$page="edit_exam";
include_once("connection.php");
include_once ("page_title.php");

$submitClick = isset($_POST['submit']);

if (isset($_REQUEST['id']))
{
    $id=$_REQUEST['id'];
    $_SESSION["savedExamId"] = $id;
    $query = "SELECT * from exam where exam_id='".$id."'";
    $result = mysqli_query($con, $query) or die ( mysqli_error());
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) <= 0)
    {
        header("Location: exam.php");
        exit();
    }
}
else {
    if (isset($_SESSION["savedExamId"]))
    {
        $id = $_SESSION["savedExamId"];
    }
    $query = "SELECT * from exam where exam_id='".$id."'";
    $result = mysqli_query($con, $query) or die ( mysqli_error());
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) <= 0)
    {
        header("Location: exam.php");
        exit();
    }
    if (!$submitClick)
    {
        header("Location: exam.php");
        exit();
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
    
	$id=$_SESSION["savedExamId"];
    $sql="select * from exam where name='$ename' AND exam_id != $id;";
    
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0)
    {
        $alertMsg = "
            <div class='col-md-12'>
            <div class='alert alert-danger alert-dismissible' style='height:80px;'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
              <h5><i class='icon fas fa-ban'></i> Alert!</h5>
              Exam already exists.
            </div></div>";
    } else {
        $update="update exam set name='".$ename."', date='".$exam_date."', sessions_count='".$session_count."', from_criteria='".$from_date."', to_criteria='".$to_date."', fees='".$fees."', fess_due_date='".$due_date."' where exam_id='".$id."'";

        if (mysqli_query($con, $update))
        {
            unset($_SESSION['savedId']);
            $_SESSION["examUpdated"] = "yes";
            header("Location: exam.php");
            exit();
        }
    }
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
            <!-- Content Header (Page header) -->

            <?php include_once("content_header.php"); ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <?php if ($submitClick)
			echo $alertMsg;
		?>
                        <div class="col-12">

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Exam</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->

                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Exam Name</label>
                                                    <input type="text" class="form-control" name="exam_name" placeholder="" required value="<?php echo $row['name'];?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <?php
                                                    $timestamp = strtotime($row['date']);
                                                    $new_date = date("d-m-Y", $timestamp);
                                                    ?>
                                                    <div class="input-group date" id="exam_date" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#exam_date" name="exam_date" required value="<?php echo $new_date;?>" />
                                                        <div class="input-group-append" data-target="#exam_date" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Session Count</label>
                                                    <input type="number" class="form-control" name="exam_atten" placeholder="" required value="<?php echo $row['sessions_count'];?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Fees</label>
                                                    <input type="number" class="form-control" name="exam_fees" placeholder="" required value="<?php echo $row['fees'];?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>From Date Criteria</label>
                                                    <?php
                                                    $timestamp = strtotime($row['from_criteria']);
                                                    $new_date = date("d-m-Y", $timestamp);
                                                    ?>
                                                    <div class="input-group date" id="from_date_cri" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#from_date_cri" name="from_date_cri" required value="<?php echo $new_date;?>" />
                                                        <div class="input-group-append" data-target="#from_date_cri" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>To Date Criteria</label>
                                                    <?php
                                                    $timestamp = strtotime($row['to_criteria']);
                                                    $new_date = date("d-m-Y", $timestamp);
                                                    ?>
                                                    <div class="input-group date" id="to_date_cri" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#to_date_cri" name="to_date_cri" required value="<?php echo $new_date;?>" />
                                                        <div class="input-group-append" data-target="#to_date_cri" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Fees Due Date</label>
                                                    <?php
                                                    $timestamp = strtotime($row['fess_due_date']);
                                                    $new_date = date("d-m-Y", $timestamp);
                                                    ?>
                                                    <div class="input-group date" id="exam_fees_due_date" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#exam_fees_due_date" name="exam_fees_due_date" required value="<?php echo $new_date;?>" />
                                                        <div class="input-group-append" data-target="#exam_fees_due_date" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
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
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

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
</body>

</html>
