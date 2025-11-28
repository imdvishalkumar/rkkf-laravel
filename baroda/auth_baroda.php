<?php
session_start();
if ( !isset( $_SESSION["baroda"] ) ) {
    header( "Location: login.php" );
    exit();
}
?>
