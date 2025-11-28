<?php
include_once("connection.php");
$id=$_REQUEST['id'];
$query = "SELECT * from users where user_id=".$id.""; 

$result = mysqli_query($con, $query) or die ( mysqli_error());
      if (mysqli_num_rows($result) > 0) {
		  $row = mysqli_fetch_assoc($result);
		  echo $row['mobile'];
	  } else {
		  echo "else";
	  }
?>