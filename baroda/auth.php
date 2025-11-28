<?php
session_start();
if ( !isset( $_SESSION["baroda_admin"] ) ) {
    header( "Location: login.php" );
    exit();
}
?>
