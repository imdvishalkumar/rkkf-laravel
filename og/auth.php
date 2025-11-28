<?php
session_start();
if ( !isset( $_SESSION["super_admin"] ) ) {
    header( "Location: login.php" );
    exit();
}
?>
