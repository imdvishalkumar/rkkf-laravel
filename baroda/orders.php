<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page = "orders";
include_once( "connection.php" );
include_once("page_title.php");

$sql = "SELECT @a=@a+1 serial_number, o.order_id,o.name_var,o.qty,o.p_price,o.date,o.status,o.rp_order_id,CONCAT(s.firstname,' ',s.lastname) as student_name, s.student_id,s.email,(SELECT name FROM branch WHERE branch_id = s.branch_id) as branch_name,o.counter, o.flag_delivered, o.viewed FROM orders o, students s WHERE o.student_id = s.student_id AND o.status = 1 ORDER BY o.order_id DESC";
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
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Orders Details</h3>
                                            <div class="card-tools">
                                                <div class="row">
                                                    <div class="col-sm-3 my-auto">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="customSwitch1" onchange="get_studentInfo()" checked>
                                                            <label class="custom-control-label" for="customSwitch1">Paid/Pending</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body" id="infoDiv">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Sr</th>
                                                        <th>Order No.</th>
                                                        <th>Gr No.</th>
                                                        <th>Student Name</th>
                                                        <th>Student Branch</th>
                                                        <th>Product Name</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Send Mail</th>
                                                        <th>Viewed</th>
                                                        <th>RazorPay Order Id</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {
                        $i = 0;
  while($row = $result->fetch_assoc()) {
    echo "<tr>  <td>" . ++$i . "</td>
		  <td>" . $row["counter"]. "</td>
		  <td>" . $row["student_id"]. "</td>
		  <td>" . $row["student_name"]. "</td>
		  <td>" . $row["branch_name"]. "</td>
		  <td>" . $row["name_var"]. "</td>
		  <td>" . $row["qty"]. "</td>		  
		  <td>" . $row["p_price"]. "</td>		  
		  <td>" . $row["date"]. "</td>		  
		  <td>";
      if ($row["status"] == 1) {
          echo "<span class='float-right badge bg-success'>Success</span>";
      } else {
          echo "<span class='float-right badge bg-danger'>Failed</span>";
      }
          echo "</td>";
        
        
        
        
      if ($row["flag_delivered"] == 1 )
      {
        echo "<td><span class='float-right badge bg-success'>Delivered</span></td>";
      }
      else if($row["status"] == 1)
      {  
          
          echo "
		  <td><a href='order_delivered_mail.php?name=".$row["student_name"]."&order_date=".$row["date"]."&email=".$row["email"]."&order_no=".$row["counter"]."' class='edit-user action-icon' title='Edit'>
				<span class='fas fa-paper-plane'></span>
				</a></td>";
		
      }
      else 
      {
         echo "<td><span>-</span></td>";
      }

      if ($row["viewed"] == 1 )
      {
        echo "<td><span class='float-right badge bg-success'>Viewed</span></td>";
      }
      else
      {  
          
          echo "
		  <td><a href='order_viewed.php?order_no=".$row["counter"]."' class='edit-user action-icon' title='Edit'>
				<span class='far fa-eye'></span>
				</a></td>";
		
      }
		
		
				
				echo"
				<td>". $row["rp_order_id"]. "</td>	
		 
		  </tr>";
  }
} else {
 ?><tr>

                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
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
                        minlength: 4,
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
                        required: "Please provide a password"
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
        function get_studentInfo() { // Call to ajax function
            var param = $("#customSwitch1").is(':checked');
            $.ajax({
                type: "POST",
                url: "orders_ajax.php", // Name of the php files
                data: {
                    param: param
                },
                success: function(html) {
                    $("#infoDiv").html(html);
                }
            });
        }
        $('#customSwitch1').on('switchChange.bootstrapSwitch', function (event, state) {
            get_studentInfo();
        }); 


    </script>


</body>

</html>
