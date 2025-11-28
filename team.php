<?php
include_once("auth.php"); //include auth.php file on all secure pages
$page="team";
include_once("connection.php");
include_once("page_title.php");

$submitClick = isset($_POST['submit']);
$delClick = isset($_POST['delete']);
$searchClick = isset($_POST['search']);
if($delClick) {
	$id=$_POST['team_id'];
$query = "delete from team WHERE id=$id";
$result = mysqli_query($con,$query);
if($result){
			$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Team Member Deleted Successfully.
                </div></div>";
} else {
				$alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error deleting Team Member.
                </div></div>";
}
}

if ( $submitClick ) {

    define( 'UPLOAD_PATH', 'images/team/' );
    $name = $_POST['name'];
    $pos = $_POST['position'];
    
    $filename1 = $_FILES["img1"]["name"];
    $tempname1 = $_FILES["img1"]["tmp_name"];

    $newName1 = uniqid("1_".$name."_",true);
    $newName1 =  str_replace(".","",$newName1);

    $tempext = explode(".", $filename1);
    $ext1 = end($tempext);
    
    $newName1 =  $newName1 . "." . $ext1;
    
    // Get all the submitted data from the form
    $sql = "INSERT INTO team (name,post,image) VALUES ('".$name."','".$pos."','".$newName1."')";

    // Execute query
    $success_var = mysqli_query( $con, $sql );
  

    $file1 = move_uploaded_file( $tempname1 , UPLOAD_PATH . $newName1 );
    // Now let's move the uploaded image into the folder: image 
        if ($file1 && $success_var) { 
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Team Member Added Successfully.
                </div></div>";
        } else { 
            $alertMsg = "
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Alert!</h5>
                  Error adding Team Member.
                </div></div>";
        }
}
	$sql = "SELECT * FROM team";
	$result = $con->query($sql);

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
		if (isset($_SESSION["teamUpdated"])){
			if ($_SESSION["teamUpdated"] == "yes"){
			unset($_SESSION['teamUpdated']);
			$alertMsg = "<div class='row'>
				<div class='col-md-12'>
				<div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Alert!</h5>
                  Team Member Updated Successfully.
                </div></div>";
							echo $alertMsg;

		}
		}
		
		?>
                        <!-- left column -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Team Details</h3>


                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Name</th>
                                                        <th>Description</th>
                                                        <th>Image1</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
					if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo "<tr>  
          <td>
          <div class='text-center'>
				
				<button id='btnModal' value='".$row['id']."' onclick='passId(this.value)' data-toggle='modal'  data-target='#deleteModal' class='delete-user action-icon' style='padding: 0;border: none;background: none;'><span class='fas fa-trash'></span></button>
				
		  </div>
          </td>
          <td>" . $row["name"] .  "</td>
          <td>" .$row["post"]. "</td>
          <td>
          <div class='form-group'>";
    if(empty($row['image'])) {
          echo "<img src='images/placeholder.png' style='height:100px;width:100px;' class='img-thumbnail'>";
      } else {
          echo "<img src='images/team/".$row['image']."' style='height:100px;width:100px;' class='img-thumbnail'>";
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
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Team Member.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <div class="modal-body">
                                                Are you sure?
                                                <input type="text" name="team_id" id="team_id" hidden/>

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
                                    <h3 class="card-title">Add Team Member</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start   -->
                                <form id="quickForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Name</label>
                                            <input type="text" class="form-control" name="name" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Position</label>
                                            <input type="text" class="form-control" name="position" placeholder="">
                                        </div>
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
                                        <div class="form-group">
                                            <img src="images/placeholder.png" style="height:200px;width:200px;" id="preview1" class="img-thumbnail">

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
            $("#team_id").val(val); //set the id to the input on the modal
        }
    </script>

</body>

</html>
