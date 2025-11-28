<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="index";

$page = "guide";

include_once("connection.php");
include_once("page_title.php");

$submitClick = isset( $_POST['submit'] );
$delClick = isset( $_POST['delete'] );
if ( $delClick ) {
    $id = $_POST['postid'];
    $query = "UPDATE guide SET is_deleted = '1' WHERE id = ".$id;
    $result = mysqli_query( $con, $query ) or die ( mysqli_error() );
    if ( $result ) {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Guide Deleted Successfully.
                </div></div>";
    } else {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error deleting Guide.
                </div></div>";
    }
}
if ( $submitClick ) {
    define( 'UPLOAD_PATH', 'guide/' );
    
    $title = $_POST['guide_name'];
//    $description = $_POST['description'];
    
    $filesArray = array();

    if(isset($_FILES['files']) ){
//        $fileCount = count($_FILES["files"]["name"]);
//        for ($x = 0; $x < $fileCount; $x++) {
//        if(!file_exists($_FILES['files']['tmp_name'][$x]) || !is_uploaded_file($_FILES['files']['tmp_name'][$x])) {
            if(!file_exists($_FILES['files']['tmp_name']) || !is_uploaded_file($_FILES['files']['tmp_name'])) {
                //echo 'No upload';

            } else {
                $filename = $_FILES["files"]["name"];
                $tempname = $_FILES["files"]["tmp_name"];
                $type = $_FILES["files"]["type"];
                $type = substr($type,0,5);
                $newName = uniqid("1_".$title."_",true);
                $newName =  str_replace(".","",$newName);
                $tempext = explode(".", $filename);
                $ext1 = end($tempext);
                $newName =  $newName . "." . $ext1;
                $filesArray['name'] = $newName;
                $filesArray['tempname'] = $tempname;
                $filesArray['type'] = $type;
            }
//        }
    }
    
    $fileBool = move_uploaded_file( $filesArray['tempname'] , UPLOAD_PATH . $filesArray['name'] );

    $sql = "INSERT INTO `guide` (`id`, `name`, `link`, `created_at`, `updated_at`, `is_deleted`, `created_by`) VALUES (NULL, '".$title."', 'https://rkkf.org/guide/".$filesArray['name']."', current_timestamp(), current_timestamp(), '0', '".$_SESSION["user_id"]."');";
    // Execute query
    $query_stmt = $con->prepare( $sql );
    $success_var = $query_stmt->execute();
    
    if ($success_var) {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Guide Added Successfully.
                </div></div>";
    } else {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error Adding Guide.
                </div></div>";
    }
}

$sql = "SELECT id, name, link FROM guide WHERE is_deleted = 0 ORDER BY id DESC;";
$result = $con->query( $sql );

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <?php include_once("head_link.php"); ?>


    <style>
        .file,
        .file1,
        .file2,
        .file3 {
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
                        <?php if ($submitClick || $delClick) { if (isset($alertMsg)) { echo $alertMsg; } } ?>
                        <!-- left column -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Guide Details</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Srn</th>
                                                        <th>Title</th>
                                                        <th>File</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody> <?php
					if ($result->num_rows > 0) {
                        $i = 1;
  while($row = $result->fetch_assoc()) {
    echo "<tr> 
		  <td>" . $i. "</td>
		  <td>" . $row["name"]. "</td>
		  <td> <embed name='plugin' src=" . $row["link"]. " style='height:200px;width:200px;' type='application/pdf'></td>
		  <td>
            <div class='text-center'>
                    <button id='btnModal' value='".$row['id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
              </div>
          </td>
		  </tr>";
      $i++;
  }
}  ?>
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
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Guide.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="postid" id="postid" hidden />

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
                                    <h3 class="card-title">Add Guide</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" id="quickForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Title</label>
                                                    <input type="text" class="form-control" name="guide_name" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="files-row">
                                        </div>
                                        <div class="form-group">
                                            <input id="media" type="file" name="files" class="file" accept="application/pdf" >
                                            <div class="input-group my-3">
                                                <input type="text" class="form-control" disabled placeholder="Select File" id="file" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="browse btn btn-primary">Browse...</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" name="submit" id="submit" class="btn btn-primary submitBtn">Submit</button>
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
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

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
        function passId(val) {
            $("#postid").val(val); //set the id to the input on the modal
        }

    </script>
    <script>
        $(document).ready(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
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
    <script>
        $(document).on("click", ".browse", function() {
            var file = $(this).parents().find(".file");
            file.trigger("click");
        });
        $('#media').change(function(e) {
            $(".files-row").empty();
            var files = e.target.files;
            if (files.length > 5) {
                $("#media").val(null);
                alert("Maximum 5 files are allowed.");
            } else {
                $.each(files, function(index, val) {
                    var file = $("input[type=file]").get(0).files[index];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var markup = "<embed name='plugin' src=" + reader.result + " style='height:200px;width:200px;' type='application/pdf'>";
                        $(".files-row").append(markup);
                    };
                    reader.readAsDataURL(file);
                });

            }
        });
    </script>
    <!-- Script to add table row -->
    <script>
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^\w+$/i.test(value);
        }, "Letters, numbers, and underscores only please");
        $.validator.addMethod("alphanumericspace", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s]*$/i.test(value);
        }, "Letters, numbers, and spaces only please");
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z ]+$/i.test(value);
        }, "Letters only please");
        $.validator.addMethod("numbersonly", function(value, element) {
            return this.optional(element) || /^[0-9]+$/i.test(value);
        }, "Numbers only please");
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than {0}');
        $(function() {
            $('#quickForm').validate({
                rules: {
                    guide_name: {
                        required: true,
                        alphanumericspace: true,
                        rangelength: [1, 40]
                    },
                    files: {
                        required: true,
                    },
                },
                messages: {
                    guide_name: {
                        required: "Please enter a Name",
                    },
                    files: {
                        required: "Please select file",
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
