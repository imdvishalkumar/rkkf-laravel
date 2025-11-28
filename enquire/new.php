<?php

$page = "enquire";
include_once( "../connection.php" );
include_once( "../page_title.php" );

//$queryForBranch = "SELECT * FROM branch WHERE branch_id NOT IN (40,44,45,46,47,48,49,50,52,53,54)";
$queryForBranch = "SELECT * FROM branch WHERE branch_id IN (67,68,69,70,71,72,73,74,75,76,77,78,80,81,82,83)";
$resultForBranch = $con->query( $queryForBranch );

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <?php include_once("../head_link.php"); ?>

</head>

<body class="layout-fixed" style="height: auto;">

    <!-- Content Wrapper. Contains page content -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Enquire</h1>
                </div><!-- /.col -->
                <!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Student Information</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!--                                <form id="quickForm" method="post" action="<?php //echo $_SERVER['PHP_SELF'];
?>">-->
                        <form id="quickForm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">First Name</label>
                                            <input type="text" class="form-control" name="firstname" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Last Name</label>
                                            <input type="text" class="form-control" name="lastname" placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select class="form-control select2" style="width: 100%;" name="gender" required>
                                                <option disabled selected value>Select Gender</option>
                                                <option data-select2-id="30" value="1">Male</option>
                                                <option data-select2-id="31" value="2">Female</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email</label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date of Birth:</label>
                                            <div class="input-group date" id="dobdate" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#dobdate" name="dob" required />
                                                <div class="input-group-append" data-target="#dobdate" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Dad Mobile No</label>
                                            <input type="text" maxlength="10" class="form-control" name="dmno" placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Dad WhatsApp No</label>
                                            <input type="text" maxlength="10" class="form-control" name="dwno" placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Mom Mobile No</label>
                                            <input type="text" maxlength="10" class="form-control" name="mmno" placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Mom WhatsApp No</label>
                                            <input type="text" maxlength="10" class="form-control" name="mwno" placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Self Mobile No</label>
                                            <input type="text" maxlength="10" class="form-control" name="smno" placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Self WhatsApp No</label>
                                            <input type="text" maxlength="10" class="form-control" name="swno" placeholder="" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date -->
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea style="resize:none" class="form-control" rows="3" name="address"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Pincode</label>
                                                    <input type="number" maxlength="6" class="form-control" name="pincode" placeholder="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Branch</label>
                                                    <select class="form-control select2  " style="width: 100%;" name="branch_id" id="branch_id" onchange="getBranchFees()" required>
                                                        <option disabled selected value>Select Branch</option>
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
                                                <div class="form-group" id="infoDiv">
                                                    <div class="d-flex justify-content-center">
                                                        <div id="wait" class="spinner-border" role="status" style="display: none;">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group text-center">
                                            <label>Tap over QR Code to Pay your fees</label>
                                            <a href="upi://pay?pa=7203820040@barodampay&pn=UjjawalJaiswal&am=1300&cu=INR" class="upi-pay1" target="_blank"><img src="../dist/img/rkkf_qr.jpeg" alt="QR Image" height="500px" width="500px"></a>
                                            <!--<img src="../dist/img/rkkf_qr.jpeg" alt="QR Image" height="500px" width="500px">-->
                                        </div>
                                    </div>
                                </div>

                                </div>
                               
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="checkboxTerms" name="checkboxTerms" value="option1">
                                        <label for="checkboxTerms" class="custom-control-label">I agree to <a href="terms_of_service" target="_blank">Terms of Service</a> and <a href="privacy_statement" target="_blank">Privacy Policy.</a></label>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button class="btn btn-primary" type="button" onclick="get_order_id();">Submit</button>
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
    <footer class="footer">
        <strong>Copyright &copy; 2020 <a href="http://www.rkkf.co.in/">RKKF</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 10.0.1-pre
        </div>
    </footer>


    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)

    </script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="../plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
    <script src="../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="../plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../dist/js/pages/dashboard.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/jszip/jszip.min.js"></script>
    <script src="../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- jquery-validation -->
    <script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../plugins/jquery-validation/additional-methods.min.js"></script>
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
        $(function() {
            //Date range picker
            $('#dobdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        })

    </script>
    <script type="text/javascript">
        function passId(val) {
            $("#userid").val(val); //set the id to the input on the modal
        }

    </script>

    <script>
        $.validator.addMethod("alphanumericspecial", function(value, element) {
            return this.optional(element) || /^[ A-Za-z0-9_@.,/#&+-\s]*$/i.test(value);
        }, "Letters, numbers, and special characters only please");
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
                    gender: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            type: "POST",
                            url: "check_email.php",
                            data: {
                              username: function() {
                                return $("#username").val();
                              }
                            },
                        }
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
                    dob: {
                        required: true,
                    },
                    address: {
                        required: true,
                        alphanumericspecial: true,
                        rangelength: [1, 448],
                    },
                    branch_id: {
                        required: true,
                    },
                    pincode: {
                        required: true,
                        numbersonly: true,
                        rangelength: [6, 6],
                    },
                    fees: {
                        required: true,
                        numbersonly: true,
                    },
                    checkboxTerms: {
                        required: true,
                    },
                },
                messages: {
                    firstname: {
                        required: "Please Enter Firstname",
                        rangelength: "Please Enter Characters between 2 to 25"
                    },
                    lastname: {
                        required: "Please Enter Lastname",
                        rangelength: "Please Enter Characters between 2 to 25"
                    },
                    gender: {
                        required: "Please Select Gender",
                    },
                    email: {
                        required: "Please Enter Email address",
                        email: "Please Enter a vaild Email address",
                        remote: "Email address already registered with us."
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
                    address: {
                        required: "Please Enter Your Address",
                    },
                    branch_id: {
                        required: "Please Select Branch",
                    },
                    pincode: {
                        required: "Please Enter Your Pincode",
                        rangelength: "Please Enter Valid Pincode"
                    },
                    fees: {
                        required: "Please Enter Fees Amount",
                    },
                    checkboxTerms: {
                        required: "Please Accept our terms and conditions.",
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
    
    <script>
//        function IsEmail(email) {
//            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
//            if (!regex.test(email)) {
//                return false;
//            } else {
//                return true;
//            }
//        }
//        $("#email").change(function() {
//            
//        });
    </script>

    <script>
        
        function get_order_id() { // Call to ajax function
            if ($("#quickForm").valid()) {
                console.log('Called.');
                var firstname = $("input[name=firstname]").val();
                var lastname = $("input[name=lastname]").val();
                var gender = $("select[name=gender]").val();
                var email = $("input[name=email]").val();
                var dob = $("input[name=dob]").val();
                var dmno = $("input[name=dmno]").val();
                var dwno = $("input[name=dwno]").val();
                var mmno = $("input[name=mmno]").val();
                var mwno = $("input[name=mwno]").val();
                var selfno = $("input[name=smno]").val();
                var selfwp = $("input[name=swno]").val();
                var address = $("textarea[name=address]").val();
                var branch_id = $("select[name=branch_id]").val();
                var pincode = $("input[name=pincode]").val();
                $.ajax({
                    type: "POST",
                    url: "direct_entry.php", // Name of the php files
                    data: {
                        enquire: true,
                        firstname: firstname,
                        lastname: lastname,
                        gender: gender,
                        email: email,
                        dob: dob,
                        dadno: dmno,
                        dadwp: dwno,
                        momno: mmno,
                        momwp: mwno,
                        selfno: selfno,
                        selfwp: selfwp,
                        address: address,
                        branch_id: branch_id,
                        pincode: pincode
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        var order_created = response.order_created;
                        if (order_created) {
                            $(':input','#quickForm')
                              .not(':button, :submit, :reset, :hidden')
                              .val('')
                              .prop('checked', false)
                              .prop('selected', false);
                            alert('Success.');
                        } else {
                            alert('Failed.');
                        }
                    }
                });

            }
        }

    </script>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function rzpopen(response) {
            var order_id = response.order_id;
            var amount = response.amount;
            var name = response.name;
            var number = response.number;
            var email = response.email;
            var key = response.key;
            var options = {
                "key": key, // Enter the Key ID generated from the Dashboard
                //"amount": amount * 100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                "amount": 1 * 100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                "currency": "INR",
                "name": "RKKF",
                "description": "Admission fees",
                "image": "https://rkkf.org/images/logo.jpg",
                "order_id": order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                "callback_url": "https://www.rkkf.org/enquire/verify_payment.php",
                "prefill": {
                    "name": name,
                    "email": email,
                    "contact": number
                },
                "theme": {
                    "color": "#3399cc"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();

        }

    </script>
    <script>
        function getBranchFees() { // Call to ajax function
            var branchId = $('#branch_id').val();
            var dataString = "branch_id=" + branchId;
            $.ajax({
                type: "POST",
                url: "get_fees_from_branch.php", // Name of the php files
                data: dataString,
                beforeSend: function() {
                    $('#wait').show();
                },
                complete: function() {
                    $('#wait').fadeOut();
                },
                success: function(html) {
                    $("#infoDiv").html(html);
                }
            });
        }

    </script>

</body>

</html>
