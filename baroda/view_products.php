<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="view_products";
include_once("connection.php");
include_once("page_title.php");

$submitClick = isset($_POST['submit']);
$delClick = isset($_POST['delete']);
$searchClick = isset($_POST['search']);
if($delClick) {
	$id=$_POST['product_id'];
$query = "delete from products WHERE product_id=$id";

$result = mysqli_query($con,$query);
if($result){
			$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Product Deleted Successfully.
                </div></div>";
} else {
				$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error deleting Product.
                </div></div>";
}
}
	$sql = "SELECT p.*,GROUP_CONCAT(v.variation) variation, GROUP_CONCAT(v.price) price, GROUP_CONCAT(v.qty) qty from variation v, products p WHERE p.product_id = v.product_id GROUP BY p.product_id";
	$result = $con->query($sql);

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
		if ($delClick){
			echo $alertMsg;
		}
		if (isset($_SESSION["branchUpdated"])){
			if ($_SESSION["branchUpdated"] == "yes"){
			unset($_SESSION['branchUpdated']);
			$alertMsg = "<div class='row'>
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Branch Updated Successfully.
                </div></div>";
							echo $alertMsg;

		}
		}
		
		?>
                        <!-- left column -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Products Details</h3>


                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Name</th>
                                                        <th>Variation</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Description</th>
                                                        <th>Image1</th>
                                                        <th>Image2</th>
                                                        <th>Image3</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr id='".$row['product_id']."'>  
          <td>
          <div class='text-center'>
				<a href='edit_product.php?id=".$row['product_id']."' class='edit-user action-icon' title='Edit'>
				<span class='fas fa-edit'></span>
				</a>&nbsp;&nbsp;";

                        if ($_SESSION["email"] == 'kenseiujwl@gmail.com' || $_SESSION["email"] == 'kaushalrola@gmail.com'){


				echo "<button id='btnModal' value='".$row['product_id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>"; }
				echo "
		  </div>
          </td>
          <td>" . $row["name"] .  "</td>
          <td>" . $row["variation"] .  "</td>
		  <td>" . $row["price"]. "</td>
          <td>" .$row["qty"]. "</td>
          <td>" .$row["details"]. "</td>
          <td>
          <div class='form-group'>";
    if(empty($row['image1'])) {
          echo "<img src='images/placeholder.png' style='height:100px;width:100px;' class='img-thumbnail'>";
      } else {
          echo "<img src='images/products/".$row['image1']."' style='height:100px;width:100px;' class='img-thumbnail'>";
      }
            echo "
            </div>
          </td><td>
          <div class='form-group'>";
    if(empty($row['image2'])) {
          echo "<img src='images/placeholder.png' style='height:100px;width:100px;' class='img-thumbnail'>";
      } else {
          echo "<img src='images/products/".$row['image2']."' style='height:100px;width:100px;' class='img-thumbnail'>";
      }
            echo "
            </div>
          </td><td>
          <div class='form-group'>";
    if(empty($row['image3'])) {
          echo "<img src='images/placeholder.png' style='height:100px;width:100px;' class='img-thumbnail'>";
      } else {
          echo "<img src='images/products/".$row['image3']."' style='height:100px;width:100px;' class='img-thumbnail'>";
      }
            echo "
            </div>
          </td>
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
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="product_id" id="product_id" hidden/>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="delete" class="btn btn-danger" data-dismiss="modal" onclick="deleteProduct()">Delete</button>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
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
    <script type="text/javascript">
        $(document).ready(function($) {
            $('#addNewUser').click(function() {
                $('#userInserUpdateForm').trigger("reset");
                $('#userModel').html("Add New User");
                $('#user-model').modal('show');
            });
            $('body').on('click', '.edit', function() {
                var id = $(this).data('id');
                var id = $(this).data('name');
                var id = $(this).data('id');
                // ajax
                $.ajax({
                    type: "POST",
                    url: "edit.php",
                    data: {
                        id: id,
                        id: id,
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#userModel').html("Edit User");
                        $('#user-model').modal('show');
                        $('#id').val(res.id);
                        $('#name').val(res.name);
                        $('#age').val(res.age);
                        $('#email').val(res.email);
                    }
                });
            });
            $('body').on('click', '.delete', function() {
                if (confirm("Delete Record?") == true) {
                    var id = $(this).data('id');
                    // ajax
                    $.ajax({
                        type: "POST",
                        url: "delete.php",
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('#name').html(res.name);
                            $('#age').html(res.age);
                            $('#email').html(res.email);
                            window.location.reload();
                        }
                    });
                }
            });
        });

    </script>
    <script type="text/javascript">
        function passId(val) {
            $("#product_id").val(val); //set the id to the input on the modal
        }
        
        function deleteProduct() { // Call to ajax function
            var productId = $('#product_id').val();
            $.ajax({
                type: "POST",
                url: "delete_product_ajax.php", // Name of the php files
                data: {
                    product_id: productId
                },
                dataType: 'JSON',
                success: function(response) {
                    console.log("function called2");
                    var found = response.deleted;
                    if (found) {
                        $('#'+productId).remove();
                    } else {
                        alert('Unable to delete product!');
                    }

                }
            });
        }
    </script>

</body>

</html>
