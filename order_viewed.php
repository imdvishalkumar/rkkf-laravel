<?php
include("auth.php"); //include auth.php file on all secure pages

include_once("connection.php");

$no = $_GET['order_no'];

$sql = "UPDATE orders SET viewed = 1 where counter = '".$no."';";
$result = $con->query( $sql );
echo"<script type='text/javascript'> window.location = 'orders.php'; </script>";