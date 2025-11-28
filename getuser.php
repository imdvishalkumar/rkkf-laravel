<?php

include_once("connection.php");
echo "search.php";

$searchStr = intval($_GET['q']);


$sql="SELECT * FROM users WHERE CONCAT( firstname,  ' ', lastname ) LIKE  '%".$searchStr."%'";
$result = mysqli_query($con,$sql);

while($row = mysqli_fetch_array($result)) {
 echo "<tr>  <td>" . $row["firstname"] . " " . $row["lastname"] . "</td>
		  <td>" . $row["mobile"]. "</td>
		  <td>" . $row["email"]. "</td>
		  <td>" . $row["role"]. "</td>
		  <td> - </td>
		  </tr>";
}

mysqli_close($con);
?>