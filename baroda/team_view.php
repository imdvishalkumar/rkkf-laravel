<?php
$page="team";
include_once("connection.php");
include_once("page_title.php");
$sql = "SELECT * FROM team";
$result = $con->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com    @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>RKKF Team</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
    	body{
    background:#eee;
    margin-top:20px;
}
.img-thumbnail {
    padding: .25rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: .25rem;
    max-width: 100%;
    height: 100px;
}

.social-link {
    width: 30px;
    height: 30px;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    border-radius: 50%;
    transition: all 0.3s;
    font-size: 0.9rem;
}
    </style>
</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<div class="container py-5">
    <div class="row mb-4">
      <div class="col-lg-12">
        <h2 class="display-4 font-weight-light text-center">Our team</h2>
      </div>
    </div>

    <div class="row text-center">
      <!-- Team item-->
        <?php 
        if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  ?>
                <div class="col-xl-3 col-sm-6 mb-5">
        <div class="bg-white rounded shadow-sm py-5 px-4"><img src="<?php echo "images/team/".$row['image']; ?>" alt="" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm" width="100" height="100">
          <h5 class="mb-0"><?php echo $row["name"]; ?></h5><span class="small text-uppercase text-muted"><?php echo $row["post"]; ?></span>
        </div>
      </div>
                
                <?php
              }
        }
        ?>
      <!-- End-->

     

    </div>
  </div>
</body>
</html>