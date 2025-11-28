<?php
include("auth.php"); 
$page="fix_payment";
include_once("page_title.php");


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
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Fix Payment</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Payment Type</label>
                                                    <select id="order_type" class="form-control select2 " style="width: 100%;" name="order_type" required>
                                                        <option disabled selected value>Select Payment Type </option>
                                                        <option data-select2-id="30" value="fee">Monthly Fee</option>
                                                        <option data-select2-id="30" value="enq">Admission</option>
                                                        <option data-select2-id="30" value="order">Order</option>
                                                        <option data-select2-id="30" value="exam">Exam Fee</option>
                                                        <option data-select2-id="30" value="event">Event Fee</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Razorpay Order ID</label>
                                                    <input type="text" class="form-control" name="order_id" id="order_id" placeholder="" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-1 align-items-center">
                                                <div class="form-group">
                                                    <button type="submit" name="submit" id="submit" onclick="checkOrder()" class="btn btn-primary">Check Order ID</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="infoDiv" class="form-group">
                                    </div>
                                    <!-- /.card-body -->
                            </div>
                            <!-- Button trigger modal -->


                            <!-- Modal -->

                        </div>
                        <!--/.col (left) -->
                        <!-- right column -->
                        <div class="col-md-12">

                            <!-- general form elements -->

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
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
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

    <script>
        function checkOrder() { // Call to ajax function
            var orderId = $('#order_id').val();
            var orderType = $('#order_type').val();
            console.log("ORDER ID : " + orderId);
            console.log("ORDER TYPE : " + orderType);

            $.ajax({
                type: "POST",
                url: "fix_payment_entry_ajax.php", // Name of the php files
                data: {
                   order_id: orderId,
                   order_type: orderType
                },
                dataType: 'JSON',
                success: function(response) {
                    console.log(response);
                     var found = response.order_found;
                   var msg = response.message;
                     if (found) {
                        alert(msg);
//                         $('#'+feeId).remove();
                     } else {
                         alert(msg);
                     }
                }
            });
        }
    </script>

</body>

</html>
