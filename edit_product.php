<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page = "edit_product";

include_once("connection.php");
include_once("page_title.php");

$submitClick = isset( $_POST['submit'] );

if (isset($_REQUEST['id'])){
	$id=$_REQUEST['id'];
$_SESSION["savedId"] = $id;
$query = "SELECT * from variation v, products p WHERE p.product_id = '".$id."' AND p.product_id = v.product_id "; 

$result = mysqli_query($con, $query) or die ( mysqli_error());
    
	$queryForVariation = "SELECT * from variation v, products p WHERE p.product_id = '".$id."' AND p.product_id = v.product_id ";
	$resultForVariation = $con->query($queryForVariation);
$row = mysqli_fetch_assoc($result);	
      if (mysqli_num_rows($result) > 0) {
	  } else {
		  echo "else";
	  }
} else if ( $submitClick ) {
    
    $id = $_SESSION["savedId"];
    
    define( 'UPLOAD_PATH', 'images/products/' );
    
    $filename1 = $_FILES["img1"]["name"];
    $tempname1 = $_FILES["img1"]["tmp_name"];
    $filename2 = $_FILES["img2"]["name"];
    $tempname2 = $_FILES["img2"]["tmp_name"];
    $filename3 = $_FILES["img3"]["name"];
    $tempname3 = $_FILES["img3"]["tmp_name"];
    
    if ($filename1 != "") {
        $newName1 = uniqid("1_".$name."_",true);
        $newName1 =  str_replace(".","",$newName1);
        $tempext = explode(".", $filename1);
        $ext1 = end($tempext);
        $newName1 =  $newName1 . "." . $ext1;
        $update = "UPDATE products SET image1 = '".$newName1."' WHERE product_id = ".$id;
        mysqli_query( $con, $update);
        $file1 = move_uploaded_file( $tempname1 , UPLOAD_PATH . $newName1 );
    }
    if ($filename2 != "") {
        $newName2 = uniqid("2_".$name."_",true);
        $newName2 =  str_replace(".","",$newName2);
        $tempext = explode(".", $filename2);
        $ext2 = end($tempext);
        $newName2 =  $newName2 . "." . $ext2;
        $update = "UPDATE products SET image2 = '".$newName2."' WHERE product_id = ".$id;
        mysqli_query( $con, $update);
        $file2 = move_uploaded_file( $tempname2 , UPLOAD_PATH . $newName2 );
    }
    if ($filename3 != "") {
        $newName3 = uniqid("3_".$name."_",true);
        $newName3 =  str_replace(".","",$newName3);
        $tempext = explode(".", $filename3);
        $ext3 = end($tempext);
        $newName3 =  $newName3 . "." . $ext3;
        $update = "UPDATE products SET image3 = '".$newName3."' WHERE product_id = ".$id;
        mysqli_query( $con, $update);
        $file3 = move_uploaded_file( $tempname3 , UPLOAD_PATH . $newName3 );
    }
    
    $name = $_POST['product_name'];
    $variationsId = $_POST['variationId'];
    $variations = $_POST['variation'];
    $prices = $_POST['price'];
    $qtys = $_POST['qty'];
    $belts = $_POST['belt'];
    $beltStr = implode(', ', $belts); 
    $description = $_POST['description'];
    
    $update = "UPDATE products SET name = '".$name."', details = '".$description."', belt_ids = '".$beltStr."' WHERE product_id = ".$id;

    if ( mysqli_query( $con, $update) )
    {
        $success_var1 = false;
        foreach($variationsId as $index => $value){
            $query = "DELETE FROM `variation` WHERE `variation`.`id` = '".$value."'"; 
            echo $query;
            $query_stmt = $con->prepare( $query );
            if ( $query_stmt->execute() ) {
                $success_var1 = true;
            } else {
                $success_var1 = false;
            }
        }
        $success_var2 = false;
        foreach($variations as $index => $value){
            $query = "INSERT INTO variation (product_id,variation,price,qty) VALUES ('".$id."','".$variations[$index]."','".$prices[$index]."','".$qtys[$index]."')"; 
            $query_stmt = $con->prepare( $query );
            if ( $query_stmt->execute() ) {
                $success_var2 = true;
            } else {
                $success_var2 = false;
            }
        }
        
        $alertMsg = "
			<div class='col-md-12'>
			<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
              <h5><i class='icon fas fa-check'></i> Alert!</h5>
              Product Updated Successfully.
            </div></div>";
            header( "Location: view_products.php" );
    }
    
} else {
    header( "Location: view_products.php" );
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
                                    <h3 class="card-title">Edit Products</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" id="quickForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Product Name</label>
                                                    <input type="text" class="form-control" value="<?php echo $row['name'];?>" name="product_name" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="variation-row">
                                            <?php 
                                                $temp = 0;
												if ($resultForVariation->num_rows > 0) {
													while($rows = $resultForVariation->fetch_assoc()) {
                                                        
														?>
                                            <input type="text" maxlength="10" class="form-control" value="<?php echo $rows['id'];?>" name="variationId[]" placeholder="" hidden>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Variation</label>
                                                        <input type="text" maxlength="10" class="form-control" value="<?php echo $rows['variation'];?>" name="variation[]" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Price</label>
                                                        <input type="text" maxlength="10" class="form-control" value="<?php echo $rows['price'];?>" name="price[]" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Quantity</label>
                                                        <input type="text" maxlength="10" class="form-control" value="<?php echo $rows['qty'];?>" name="qty[]" placeholder="">
                                                    </div>
                                                </div>

                                                <?php
                                                if ($temp == 0){
                                                    echo "<div class='col-sm-2 d-flex justify-content-center' style='  align-items: center;'> <div class='form-group'> <button type='button' id='add-row' class='btn btn-info float-right'><i class='fas fa-plus'></i></button> </div> </div>";
                                                    $temp++;
                                                }
                                                else{
                                                    echo "<div class='col-sm-2 d-flex justify-content-center' style='align-items: center;'> <div class='form-group'> <button type='button' id='remove-row' class='btn btn-info float-right'><i class='fas fa-times'></i></button></div> </div>";
                                                }
                                                
                                                ?>

                                            </div>
                                            <?php
													}
												} else {
													?>
                                            echo "No varations found.";
                                            <?php
												}
                                        ?>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea style="resize:none" class="form-control" rows="3" name="description"><?php echo $row['details'];?></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Select Belt</label>
                                                    <select class="select2bs4" multiple="multiple" data-placeholder="Select a Belt" style="width: 100%;" name="belt[]" required>
                                                        <?php 
                                                        
                                                    $string = $row['belt_ids'];
                                                    $str_arr = explode (",", $string); 

												if ($resultForBelt->num_rows > 0) {
													while($rows = $resultForBelt->fetch_assoc()) {
													    
													    if (in_array($rows["belt_id"], $str_arr)) {
    														?>
                                                            <option value="<?php echo $rows["belt_id"]; ?>" selected ><?php echo $rows["name"]; ?></option>
                                                            <?php
													    } else {
    														?>
                                                            <option value="<?php echo $rows["belt_id"]; ?>"><?php echo $rows["name"]; ?></option>
                                                            <?php
													    }
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
                                                    <input id="img11" type="file" name="img1" class="file1" accept="image/*">
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
                                                    <?php 
                                                    if ($row['image1'] != "") {
                                                        ?>
                                                        <img src="<?php echo "images/products/".$row['image1'];?>" style="height:200px;width:200px;" id="preview1" class="img-thumbnail">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <img src="images/placeholder.png" style="height:200px;width:200px;" id="preview1" class="img-thumbnail">
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <?php 
                                                    if ($row['image2'] != "") {
                                                        ?>
                                                        <img src="<?php echo "images/products/".$row['image2'];?>" style="height:200px;width:200px;" id="preview2" class="img-thumbnail">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <img src="images/placeholder.png" style="height:200px;width:200px;" id="preview2" class="img-thumbnail">
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <?php 
                                                    if ($row['image3'] != "") {
                                                        ?>
                                                        <img src="<?php echo "images/products/".$row['image3'];?>" style="height:200px;width:200px;" id="preview3" class="img-thumbnail">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <img src="images/placeholder.png" style="height:200px;width:200px;" id="preview3" class="img-thumbnail">
                                                        <?php
                                                    }
                                                    ?>
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
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- Page specific script -->

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
    <!-- Script to add table row -->
    <script>
        $(document).ready(function() {
            $("#add-row").click(function() {
                markup = "<div class ='row'><div class='col-sm-4'> <div class='form-group'> <label for='exampleInputEmail1'>Variation</label> <input type='text' maxlength='10' class='form-control' name='variation[]' placeholder='' > </div> </div> <div class='col-sm-3'> <div class='form-group'> <label for='exampleInputEmail1'>Price</label> <input type='text' maxlength='10' class='form-control' name='price[]' placeholder='' > </div> </div> <div class='col-sm-3'> <div class='form-group'> <label for='exampleInputEmail1'>Quantity</label> <input type='text' maxlength='10' class='form-control' name='qty[]' placeholder='' > </div> </div> <div class='col-sm-2 d-flex justify-content-center' style='align-items: center;'> <div class='form-group'> <button type='button' id='remove-row' class='btn btn-info float-right'><i class='fas fa-times'></i></button></div> </div> </div>";
                tableBody = $(".variation-row");
                tableBody.append(markup);
                console.log("add clicked");
            });
        });
        $(document).on('click', '#remove-row', function() {
            $(this).parent().parent().parent().remove();
            console.log("remove clicked");
        });

    </script>

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
        $(document).ready(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
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
                    dmno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    dwno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    mmno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    mwno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    smno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    swno: {
                        required: true,
                        numbersonly: true,
                        rangelength: [10, 10]
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    dob: {
                        required: true,
                    },
                    doj: {
                        required: true,
                    },
                    address: {
                        required: true,
                        alphanumeric: true,
                    },
                    pincode: {
                        required: true,
                        numbersonly: true,
                        rangelength: [6, 6],
                    },
                },
                messages: {
                    email: {
                        required: "Please enter a email address",
                        email: "Please enter a vaild email address"
                    },
                    firstname: {
                        required: "Please Enter Firstname",
                        rangelength: "Please Enter Characters between 2 to 25"
                    },
                    lastname: {
                        required: "Please Enter Lastname",
                        rangelength: "Please Enter Characters between 2 to 25"
                    },
                    dmno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    dwno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    mmno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    mwno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    smno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    swno: {
                        required: "Please Enter Mobile Number",
                        rangelength: "Please Enter Valid Mobile Number"
                    },
                    dob: {
                        required: "Please Enter Your Birthdate"
                    },
                    doj: {
                        required: "Please Enter Your Joining Date"
                    },
                    address: {
                        required: "Please Enter Your Address"
                    },
                    pincode: {
                        required: "Please Enter Your Pincode",
                        rangelength: "Please Enter Valid Pincode"
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
