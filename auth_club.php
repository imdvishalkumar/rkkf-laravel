<?php
session_start();
if ( !isset( $_SESSION["club"] ) ) {
    header( "Location: login.php" );
    exit();
}
?>
