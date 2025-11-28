<?php
$myfile = fopen( "testfile.txt", "w" );
fwrite( $myfile, "test" );
fclose( $myfile );
?>