<?php
include_once ("auth.php"); //include auth.php file on all secure pages
$page = "notification";

include_once ("connection.php");
include_once ("page_title.php");

$submitClick = isset($_POST['submit']);
$delClick = isset($_POST['delete']);
$searchClick = isset($_POST['search']);

if ($delClick)
{
    $id = $_POST['userid'];
    $query = "DELETE FROM notification WHERE timestamp='".$id."'";
    $result = mysqli_query($con, $query) or die(mysqli_error());
    if ($result)
    {
        $alertMsg = "
        <div class='col-md-12'>
            <div class='alert alert-success alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h5><i class='icon fas fa-check'></i> Alert!</h5>
                Notification Deleted Successfully.
            </div>
        </div>";
    }
    else
    {
        $alertMsg = "
        <div class='col-md-12'>
            <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                Error deleting Notification.
            </div>
        </div>";
    }
}
if ($submitClick)
{
    define( 'UPLOAD_PATH', 'files/' );

    $title = $_POST['title'];
    $details = $_POST['details'];
    $branch = $_POST['branch_id'];
    

    if ($branch == "all") {
        $selectStudentIds = "select student_id from students;";
    } else {
        $selectStudentIds = "select student_id from students where branch_id = ".$branch.";";
    }
    
    if (isset($_FILES["img1"]["name"])) {
        $filename1 = $_FILES["img1"]["name"];
        $tempname1 = $_FILES["img1"]["tmp_name"];

        $newName1 = uniqid("1_".$title,true);
        $newName1 =  str_replace(".","",$newName1);

        $tempext = explode(".", $filename1);
        $ext1 = end($tempext);

        $newName1 =  $newName1 . "." . $ext1;
        $file1 = move_uploaded_file( $tempname1 , UPLOAD_PATH . $newName1 );
        $url = "https://".$_SERVER['SERVER_NAME']."/files/";
        
        $details .= "<br> ".$url.rawurlencode($newName1); 
    
    }
    
    $resStudentIds = mysqli_query($con, $selectStudentIds);
    $time = date('Y-m-d H:i:s');
    if (mysqli_num_rows($resStudentIds) > 0)
    {
        $bool = false;
        // output data of each row
        while($row = mysqli_fetch_assoc($resStudentIds)) 
        {
            $q = "insert into notification (title,details,student_id,viewed,type,sent,timestamp) VALUES ('".$title."','".$details."','".$row['student_id']."','0','custom','0','".$time."')";
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
                  Notification Added Successfully.
                </div></div>";                    
        }
    } else {
        $alertMsg = "
        <div class='col-md-12'>
            <div class='alert alert-danger alert-dismissible' style='height:80px;'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                No students found in branch.
            </div>
        </div>";
    }
        
}
$sql = "SELECT * FROM notification GROUP BY timestamp";
$result = $con->query($sql);

$queryForBranch = "select * from branch";
$resultForBranch = $con->query($queryForBranch);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <?php include_once("head_link.php"); ?>
    <style>
        .file1, .file2, .file3 {
            visibility: hidden;
            position: absolute;
        }

    </style>

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
                        ?>
                        <!-- left column -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Notification Details</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Details</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr>  <td>" . $row["title"] . "</td>
 <td>" . $row["details"]. "</td>
 <td>
    <div class='text-center'>
        <button id='btnModal' value='".$row['timestamp']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
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
                                                <input type="text" name="userid" id="userid" hidden />

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
                                    <h3 class="card-title">Add Notification</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start   -->
                                <form id="quickForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Title</label>
                                            <input type="text" class="form-control" name="title" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Details</label>
                                            <input type="text" class="form-control" name="details" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Attachment</label>
                                            <input id="img11" type="file" name="img1" class="file1">
                                            <div class="input-group my-3">
                                                <input type="text" class="form-control" disabled placeholder="Upload File" id="file1">
                                                <div class="input-group-append">
                                                    <button type="button" class="browse1 btn btn-primary">Browse...</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                                    <label>Branch</label>
                                                    <select id="branch_id" class="form-control select2 " style="width: 100%;" name="branch_id" required>
                                                        <option disabled selected value>Select Branch</option>
                                                        <option value="all">All</option>
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
        
        $(document).on("click", ".browse1", function() {
            var file = $(this).parents().find(".file1");
            file.trigger("click"); 
        });
        $('#img11').change(function(e) {
            var fileName = e.target.files[0].name;
            $("#file1").val(fileName);
        });
    </script>
    <script type="text/javascript">
        function passId(val) {
            $("#userid").val(val); //set the id to the input on the modal
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
        $.validator.addMethod("passwordcheck", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9!@#$%^&*]{8,16}$/i.test(value);
        }, "Password only contains alphabets, numbers and special characters");
        $(function() {
            $('#quickForm').validate({
                rules: {
                    firstname: {
                        required: true,
                        lettersonly: true,
                        rangelength: [2, 25]
                    },
                    lastname: {
                        required: true,
                        lettersonly: true,
                        rangelength: [2, 25]
                    },
                    mobileNumber: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 16,
                        passwordcheck: true
                    },
                    role: {
                        required: true
                    },
                },
                messages: {
                    email: {
                        required: "Please enter a email address",
                        email: "Please enter a vaild email address"
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 8 characters long"
                    },
                    firstname: {
                        required: "Please Enter Firstname",
                        rangelength: "Please Enter Characters between 2 to 25"
                    },
                    lastname: {
                        required: "Please Enter Lastname",
                        rangelength: "Please Enter Characters between 2 to 25"
                    },
                    mobileNumber: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    role: "Please Select Role"
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
        $.validator.addMethod("passwordcheck", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9!@#$%^&*]{8,16}$/i.test(value);
        }, "Password only contains alphabets, numbers and special characters");
        $(function() {
            $('#quickForm').validate({
                rules: {
                    invoice_no: {
                        required: true,
                        numbersonly: true,
                        rangelength: [1, 11]
                    },
                    amount: {
                        required: true,
                        numbersonly: true,
                        rangelength: [2, 25]
                    },
                    cheque_no: {
                        required: true,
                        alphanumeric: true,
                        rangelength: [6, 6]
                    },
                    description: {
                        required: true,
                        alphanumeric: true,
                    },
                },
                messages: {
                    invoice_no: {
                        required: "Please enter a Invoice No.",
                        rangelength: "Please enter a Valid Invoice No."
                    },
                    amount: {
                        required: "Please provide a amount",
                        rangelength: "Please enter a Valid amount"
                    },
                    cheque_no: {
                        required: "Please Enter cheque_no",
                        rangelength: "Please Enter a Valid cheque_no"
                    },
                    description: {
                        required: "Please Enter description",
                        rangelength: "Please Enter Valid description"
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
