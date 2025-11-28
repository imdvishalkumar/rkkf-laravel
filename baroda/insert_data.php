<?php
include_once("connection.php");

$fname=$_POST['firstname'];
$lname=$_POST['lastname'];
$num=$_POST['mobileNumber'];
$email=$_POST['email'];
$pass=$_POST['password'];
$role=$_POST['role'];

$q="insert into users (firstname,lastname,mobile,email,password,role) values('$fname','$lname','$num','$email','$pass','$role')";

if(mysqli_query($con,$q))
{
	//	header("location:../contact.php");
	echo "<script>alert('Message sent Successfully.');
			
		</script>";
	//echo "Registration successful";
	//window.location='../contact.php';
}
else
{
	echo "<script> alert ('sorry something went wrong.'); 
		
		</script>";
		//window.location='../contact.php';
}
?>