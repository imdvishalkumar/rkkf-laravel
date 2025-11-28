<?php
session_start();
if ( !isset( $_SESSION["tmc"] ) ) {
    header( "Location: login.php" );
    exit();
}
?>
