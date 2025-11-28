<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reset Password In PHP MySQL</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                Reset Password In PHP MySQL
            </div>
            <div class="card-body">
                <?php
if(isset($_GET['key']) && isset($_GET['token']))
{
    require __DIR__.'/api/classes/Database.php';
    
    $db_connection = new Database();
    $conn = $db_connection->dbConnection();
    
    $email = $_GET['key'];
    $token = $_GET['token'];
    $query = "SELECT * FROM `students` WHERE `reset_link_token`='".$token."' and `email`='".$email."';";
    $query_stmt = $conn->prepare($query);
    $query_stmt->execute();
    $curDate = date("Y-m-d H:i:s");
    if($query_stmt->rowCount()){
        $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
        if($row['exp_date'] >= $curDate)
        { ?>
                    <form action="update-forget-password.php" method="post">
                        <input type="hidden" name="email" value="<?php echo $email;?>">
                        <input type="hidden" name="reset_link_token" value="<?php echo $token;?>">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Password</label>
                            <input type="password" name='password' class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Confirm Password</label>
                            <input type="password" name='cpassword' class="form-control">
                        </div>
                        <input type="submit" name="new-password" class="btn btn-primary">
                    </form>
                    <?php 
        } 
    }
    else {
        echo "<p>This forget password link has been expired</p>";
    }
} else {
    //header("Location: login.php");
}
?>
            </div>
        </div>
    </div>
</body>

</html>
