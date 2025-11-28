<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page = "add_product";

include_once("connection.php");
include_once("page_title.php");

$submitClick = isset( $_POST['submit'] );
if ( $submitClick ) {

    define( 'UPLOAD_PATH', 'images/products/' );
    $name = $_POST['product_name'];
    $variations = $_POST['variation'];
    $prices = $_POST['price'];
    $qtys = $_POST['qty'];
    $belts = $_POST['belt'];
    $beltStr = implode(', ', $belts); 
    
    $description = $_POST['description'];
    
    $filename1 = $_FILES["img1"]["name"];
    $tempname1 = $_FILES["img1"]["tmp_name"];
    $filename2 = $_FILES["img2"]["name"];
    $tempname2 = $_FILES["img2"]["tmp_name"];
    $filename3 = $_FILES["img3"]["name"];
    $tempname3 = $_FILES["img3"]["tmp_name"];

    $newName1 = uniqid("1_".$name."_",true);
    $newName2 = uniqid("2_".$name."_",true);
    $newName3 = uniqid("3_".$name."_",true);
    
    $newName1 =  str_replace(".","",$newName1);
    $newName2 =  str_replace(".","",$newName2);
    $newName3 =  str_replace(".","",$newName3);

    $tempext = explode(".", $filename1);
    $ext1 = end($tempext);
    $tempext = explode(".", $filename2);
    $ext2 = end($tempext);
    $tempext = explode(".", $filename3);
    $ext3 = end($tempext);
    
    $newName1 =  $newName1 . "." . $ext1;
    $newName2 =  $newName2 . "." . $ext2;
    $newName3 =  $newName3 . "." . $ext3;
    
    // Get all the submitted data from the form
    $sql = "INSERT INTO products (name,details,image1,image2,image3,belt_ids) VALUES ('".$name."','".$description."','".$newName1."','".$newName2."','".$newName3."','".$beltStr."')";
    // Execute query
    mysqli_query( $con, $sql );
    $last_id = mysqli_insert_id($con);

    $success_var = false;
    foreach($variations as $index => $value){
        $query = "INSERT INTO variation (product_id,variation,price,qty) VALUES ('".$last_id."','".$variations[$index]."','".$prices[$index]."','".$qtys[$index]."')"; 
        $query_stmt = $con->prepare( $query );
        if ( $query_stmt->execute() ) {
            $success_var = true;
        } else {
            $success_var = false;
        }
    }

    $file1 = move_uploaded_file( $tempname1 , UPLOAD_PATH . $newName1 );
    $file2 = move_uploaded_file( $tempname2 , UPLOAD_PATH . $newName2 );
    $file3 = move_uploaded_file( $tempname3 , UPLOAD_PATH . $newName3 );
    // Now let's move the uploaded image into the folder: image 
        if ($file1 && $file2 && $file3) { 
            $msg = "Image uploaded successfully"; 
        } else { 
            $msg = "Failed to upload image"; 
        }
    if ($success_var) {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Product Added Successfully.
                </div></div>";
    } else {
        $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error Adding Product.
                </div></div>";
    }
}

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


    <style>
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
                        <?php if ($submitClick)
                            echo $alertMsg;		
                        ?>
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Add Product</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" id="quickForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Product Name</label>
                                                    <input type="text" class="form-control" name="product_name" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="variation-row">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Variation</label>
                                                        <input type="text" maxlength="10" class="form-control" name="variation[]" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Price</label>
                                                        <input type="text" maxlength="10" class="form-control" name="price[]" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Quantity</label>
                                                        <input type="text" maxlength="10" class="form-control" name="qty[]" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 d-flex justify-content-center" style="  align-items: center;">
                                                    <div class="form-group">
                                                        <button type="button" id="add-row" class="btn btn-info float-right"><i class="fas fa-plus"></i></button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea style="resize:none" class="form-control" rows="3" name="description"></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Select Belt</label>
                                                    <select class="select2bs4" multiple="multiple" data-placeholder="Select a Belt" style="width: 100%;" name="belt[]" required>
                                                        <?php 
												if ($resultForBelt->num_rows > 0) {
													while($rows = $resultForBelt->fetch_assoc()) {
														?>
                                                        <option value="<?php echo $rows["belt_id"]; ?>"><?php echo $rows["name"]; ?></option>
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
                                                <!-- /.form-group -->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Image 1</label>
                                                    <input id="img11" type="file" name="img1" class="file1" accept="image/*" required>
                                                    <div class="input-group my-3">
                                                        <input type="text" class="form-control" disabled placeholder="Upload File" id="file1">
                                                        <div class="input-group-append">
                                                            <button type="button" class="browse1 btn btn-primary">Browse...</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Image 2</label>
                                                    <input id="img22" type="file" name="img2" class="file2" accept="image/*">
                                                    <div class="input-group my-3">
                                                        <input type="text" class="form-control" disabled placeholder="Upload File" id="file2">
                                                        <div class="input-group-append">
                                                            <button type="button" class="browse2 btn btn-primary">Browse...</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Image 3</label>
                                                    <input id="img33" type="file" name="img3" class="file3" accept="image/*">
                                                    <div class="input-group my-3">
                                                        <input type="text" class="form-control" disabled placeholder="Upload File" id="file3">
                                                        <div class="input-group-append">
                                                            <button type="button" class="browse3 btn btn-primary">Browse...</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <img src="images/placeholder.png" style="height:200px;width:200px;" id="preview1" class="img-thumbnail">

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <img src="images/placeholder.png" style="height:200px;width:200px;" id="preview2" class="img-thumbnail">

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <img src="images/placeholder.png" style="height:200px;width:200px;" id="preview3" class="img-thumbnail">

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
        $("#btnModal").click(function() {
            var passedID = $(this).data('id'); //get the id of the selected button
            $("#userid").val(passedID); //set the id to the input on the modal
        });

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
        $(document).on("click", ".browse1", function() {
            var file = $(this).parents().find(".file1");
            file.trigger("click");

        });
        $('#img11').change(function(e) {
            var fileName = e.target.files[0].name;
            $("#file1").val(fileName);

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("preview1").src = e.target.result;
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        });

        $(document).on("click", ".browse2", function() {
            var file = $(this).parents().find(".file2");
            file.trigger("click");
        });
        $('#img22').change(function(e) {
            var fileName = e.target.files[0].name;
            $("#file2").val(fileName);

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("preview2").src = e.target.result;
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        });

        $(document).on("click", ".browse3", function() {
            var file = $(this).parents().find(".file3");
            file.trigger("click");
        });
        $('#img33').change(function(e) {
            var fileName = e.target.files[0].name;
            $("#file3").val(fileName);

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("preview3").src = e.target.result;
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        });

    </script>
    <script type="text/javascript">
        function get_batches() { // Call to ajax function
            var bid = $('#branch_id').val();
            var dataString = "branch_id=" + bid;
            $.ajax({
                type: "POST",
                url: "getbatches.php", // Name of the php files
                data: dataString,
                success: function(html) {
                    $("#batchdiv").html(html);
                }
            });
        }

    </script>

    <!-- Script to add table row -->
    <script>
        $(document).ready(function() {
            $("#add-row").click(function() {
                markup = "<div class ='row'><div class='col-sm-4'> <div class='form-group'> <label for='exampleInputEmail1'>Variation</label> <input type='text' maxlength='10' class='form-control' name='variation[]' placeholder='' required> </div> </div> <div class='col-sm-3'> <div class='form-group'> <label for='exampleInputEmail1'>Price</label> <input type='text' maxlength='10' class='form-control' name='price[]' placeholder='' required> </div> </div> <div class='col-sm-3'> <div class='form-group'> <label for='exampleInputEmail1'>Quantity</label> <input type='text' maxlength='10' class='form-control' name='qty[]' placeholder='' required> </div> </div> <div class='col-sm-2 d-flex justify-content-center' style='align-items: center;'> <div class='form-group'> <button type='button' id='remove-row' class='btn btn-info float-right'><i class='fas fa-times'></i></button></div> </div> </div>";
                tableBody = $(".variation-row");
                tableBody.append(markup);

            });
        });
        $(document).on('click', '#remove-row', function() {
            $(this).parent().parent().parent().remove();
        });

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
                    'price[]': {
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
