<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="index";

$page = "news_feed";

include_once("connection.php");
include_once("page_title.php");

$submitClick = isset( $_POST['submit'] );
$delClick = isset( $_POST['delete'] );
if ( $delClick ) {
    $id = $_POST['postid'];
    $query = "UPDATE post SET is_deleted = '1' WHERE id = ".$id;
    $result = mysqli_query( $con, $query ) or die ( mysqli_error() );
    if ( $result ) {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Post Deleted Successfully.
                </div></div>";
    } else {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error deleting Post.
                </div></div>";
    }
}
if ( $submitClick ) {
    define( 'UPLOAD_PATH', 'images/feed/' );
    
    $title = $_POST['product_name'];
    $description = $_POST['description'];
    
    $filesArray = array();

    if(isset($_FILES['files']) ){  
        $fileCount = count($_FILES["files"]["name"]);
        for ($x = 0; $x < $fileCount; $x++) {
            if(!file_exists($_FILES['files']['tmp_name'][$x]) || !is_uploaded_file($_FILES['files']['tmp_name'][$x])) {
                //echo 'No upload';
            } else {
                $filename = $_FILES["files"]["name"][$x];
                $tempname = $_FILES["files"]["tmp_name"][$x];
                $type = $_FILES["files"]["type"][$x];
                $type = substr($type,0,5);
                $newName = uniqid($x."_".$title."_",true);
                $newName =  str_replace(".","",$newName);
                $tempext = explode(".", $filename);
                $ext1 = end($tempext);
                $newName =  $newName . "." . $ext1;
                $filesArray[$x]['name'] = $newName;
                $filesArray[$x]['tempname'] = $tempname;
                $filesArray[$x]['type'] = $type;
            }
        }
    }

    $sql = "INSERT INTO `post` (`id`, `title`, `description`, `created`, `is_deleted`) VALUES (NULL, '".$title."', '".$description."', current_timestamp(), '0');";
    // Execute query
    mysqli_query( $con, $sql );
    $last_id = mysqli_insert_id($con);

    $success_var = false;
    foreach($filesArray as $index => $file) {
        $query = "INSERT INTO `media` (`id`, `post_id`, `path`, `type`) VALUES (NULL, '".$last_id."', '".$file['name']."', '".$file['type']."');"; 
        $query_stmt = $con->prepare( $query );
        if ( $query_stmt->execute() ) {
            $fileBool = move_uploaded_file( $file['tempname'] , UPLOAD_PATH . $file['name'] );
            $success_var = true;
        } else {
            $success_var = false;
        }
        
    }
    if ($success_var) {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Post Added Successfully.
                </div></div>";
    } else {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error Adding Post.
                </div></div>";
    }
}

$sql = "SELECT id, title, description, created FROM post WHERE is_deleted = 0 ORDER BY id DESC";
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
                                            <h3 class="card-title">Post Details</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Srn</th>
                                                        <th>Title</th>
                                                        <th>Description</th>
                                                        <th>Media</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody> <?php
					if ($result->num_rows > 0) {
                        $i = 1;
  while($row = $result->fetch_assoc()) {
    echo "<tr> 
		  <td>" . $i. "</td>
		  <td>" . $row["title"]. "</td>
		  <td>" . $row["description"]. "</td>
          <td>"; 
        $query_media = "SELECT path, type FROM media WHERE post_id = ".$row['id'].";";
        $media_result = $con->query( $query_media );
        if ($media_result->num_rows > 0) {
              while($media_row = $media_result->fetch_assoc()) {
                  if ($media_row['type'] == 'image') {
                      echo "<div class='form-group'> <img src='images/feed/".$media_row['path']."' style='height:100px;width:100px;'  class='img-thumbnail'> </div>";
                  } else {
                      echo "<div class='form-group'> <video style='height:100px;width:100px;' controls class='img-thumbnail'> <source src='images/feed/".$media_row['path']."' type='video/mp4'> Your browser does not support the video tag. </video> </div>";
                  }
              }
        } else {
            echo "-";
        }
      echo "</td>
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
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Post.</h5>
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
                                    <h3 class="card-title">Add Post</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" id="quickForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Title</label>
                                                    <input type="text" class="form-control" name="product_name" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea style="resize:none" class="form-control" rows="3" name="description"></textarea>
                                        </div>
                                        <div class="files-row">
                                        </div>
                                        <div class="form-group">
                                            <input id="media" type="file" name="files[]" class="file" accept="image/*, video/*" multiple>
                                            <div class="input-group my-3">
                                                <input type="text" class="form-control" disabled placeholder="Select Files" id="file">
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
                        var markup = "";
                        if (file.type.slice(0, 5) == 'image') {
                            markup = "<div class='form-group'> <img src=" + reader.result + " style='height:200px;width:200px;' id='preview" + index + "' class='img-thumbnail'> </div>";
                        } else {
                            markup = "<div class='form-group'> <video style='height:200px;width:200px;' controls id='preview" + index + "' class='img-thumbnail'> <source src=" + reader.result + " type='video/mp4'> Your browser does not support the video tag. </video> </div>";
                        }
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
                    product_name: {
                        required: true,
                        alphanumericspace: true,
                        rangelength: [1, 40]
                    },
                    description: {
                        required: true,
                        rangelength: [1, 12288]
                    },
                },
                messages: {
                    product_name: {
                        required: "Please enter a Name",
                    },
                    description: {
                        required: "Please Enter Description",
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