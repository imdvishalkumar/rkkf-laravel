<?php
session_start();
$page = "login";

include_once ("../connection.php");
include_once ("../page_title.php");
$submitClick = isset($_POST['submit']);
$alertMsg = "";
if ($submitClick)
{
    $email = $_POST['email'];
    $pass = $_POST['password'];
    //to prevent from mysqli injection
    $email = stripcslashes($email);
    $pass = stripcslashes($pass);
    $email = mysqli_real_escape_string($con, $email);
    $pass = mysqli_real_escape_string($con, $pass);

    $sql = "select student_id,password from students where email = '$email' and active = 1";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);

    $verify = password_verify($pass, $row['password']);

    if (is_array($row))
    {
        if ($verify)
        {
            $_SESSION["user"] = $row['student_id'];
        } else {
          $alertMsg = "<div class='row'>
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Invalid Email or Password!</h5>
                </div></div></div>";
        }
    }
    else
    {
        $alertMsg = "<div class='row'>
				<div class='col-md-12'>
				<div class='alert alert-danger alert-dismissible' style='height:80px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-ban'></i> Invalid Email or Password!</h5>
                </div></div></div>";
    }
}
if (isset($_SESSION["user"]))
{
    header("Location: index");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo $pageTitle; ?></title>
<?php 
include_once("../head_link.php");
?>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="index.html" class="h1"><b>RKKF</b></a>
    </div>
    <div class="card-body">
	<?php echo $alertMsg ?>
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
