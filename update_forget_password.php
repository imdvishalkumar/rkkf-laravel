<?php
if ( isset( $_POST['password'] ) && $_POST['reset_link_token'] && $_POST['email'] ) {
    include "connection.php";
    $emailId = $_POST['email'];
    $token = $_POST['reset_link_token'];
    $password = $_POST['password'];
    $hash = password_hash($password,PASSWORD_DEFAULT);
    $query = mysqli_query( $con, "UPDATE students set  password='" . $password . "', reset_link_token='" . NULL . "' ,exp_date='" . NULL . "' WHERE email='" . $emailId . "'" );
    if ( $query ) {
        echo '<p>Congratulations! Your password has been updated successfully.</p>';
    } else {
        echo "<p>Something goes wrong. Please try again</p>";
    }
}
?>