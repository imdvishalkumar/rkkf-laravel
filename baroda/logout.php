<?php 
session_start();
$_SESSION["baroda_admin"] = "";
session_destroy();
header("Location: login.php");
