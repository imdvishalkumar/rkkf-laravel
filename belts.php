<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page = "belts";
include_once( "connection.php" );
include_once("page_title.php");

$submitClick = isset( $_POST['submit'] );
if ( $submitClick ) {
    
    $ids=$_POST['belt_id'];
    $examFees=$_POST['exam_fees'];
    
    
    foreach($examFees as $index => $value){
        $query = "update belt set exam_fees = ".$value." WHERE belt_id=".$ids[$index].";";
        $query_stmt = $con->prepare( $query );
        if ( $query_stmt->execute() ) {
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
                  Belt Updated Successfully.
                </div></div>";
    } else {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error Updating Belt.
                </div></div>";
    }
}
$sql = "SELECT * FROM belt";
$result = $con->query( $sql );

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
                        ?>
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Belt Details</h3>


                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <form id="quickForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Name</th>
                                                        <th>Code</th>
                                                        <th>Exam Fee</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr>  <td>" . $row["belt_id"] . "</td>
    <input type='text' class='form-control' name='belt_id[]' required value=" . $row['belt_id'] . " hidden>
		  <td>" . $row["name"]. "</td>
		  <td>" . $row["code"]. "</td>
		  <td> <div class='form-group'> <input type='number' class='form-control' name='exam_fees[]' required value=" . $row['exam_fees'] . "> </div> </td>
		  </tr>";
  }
} else {
 ?><tr>

                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr><?php
}
				  ?>
                                                </tbody>
                                                
                                            </table>
                                                <div class="card">
                                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
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
    <script type="text/javascript">
        function passId(val) {
            $("#userid").val(val); //set the id to the input on the modal
        }

    </script>

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
                    'variation[]': {
                        required: true,
                        alphanumericspace: true,
                        rangelength: [1, 40]
                    },
                    'exam_fees[]': {
                        required: true,
                        numbersonly: true,
                        rangelength: [1, 10]
                    },
                    'qty[]': {
                        required: true,
                        numbersonly: true,
                        rangelength: [1, 10]
                    },

                    description: {
                        required: true,
                        alphanumericspace: true,
                        rangelength: [1, 256]
                    },
                    img1: {
                        required: true,
                        extension: "jpg,jpeg,png",
                        filesize: 2097152,
                    },
                    img2: {
                        required: true,
                        extension: "jpg,jpeg,png",
                        filesize: 2097152,
                    },
                    img3: {
                        required: true,
                        extension: "jpg,jpeg,png",
                        filesize: 2097152,
                    },
                },
                messages: {
                    product_name: {
                        required: "Please enter a Name",
                    },
                    'variation[]': {
                        required: "Please enter Variation Name",
                    },
                    'price[]': {
                        required: "Please Enter Amount",
                    },
                    'qty[]': {
                        required: "Please Enter Quantity",
                    },
                    description: {
                        required: "Please Enter Description",
                    },
                    img1: {
                        required: "Please Select Image 1",
                        extension: "Please Select Image with jpg, jpeg & png extension.",
                        filesize: "Image file size must be less than 2MB",
                    },
                    img2: {
                        required: "Please Select Image 2",
                        extension: "Please Select Image with jpg, jpeg & png extension.",
                        filesize: "Image file size must be less than 2MB",
                    },
                    img3: {
                        required: "Please Select Image 3",
                        extension: "Please Select Image with jpg, jpeg & png extension.",
                        filesize: "Image file size must be less than 2MB",
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
