<?php 
session_start();
$_SESSION["super_admin"] = "";
session_destroy();
header("Location: login.php");
