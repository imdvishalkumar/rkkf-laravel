<?php
if ($_POST)
{
    include_once ("connection.php");
    if (isset($_POST['branch_id']))
    {
        $branchId = $_POST['branch_id'];
        $startdate = $_POST['start_date'];
        $startdatearr = explode('-', $startdate);
        $startYear = $startdatearr[0];
        $startMonth = $startdatearr[1];
        $enddate = $_POST['end_date'];
        $enddatearr = explode('-', $enddate);
        $endYear = $enddatearr[0];
        $endMonth = $enddatearr[1];

        if (1)
        {
            $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name ,s.doj,(select name FROM belt WHERE belt_id = s.belt_id) as belt_name,s.active, c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND f.coupon_id = c.coupon_id;";
            if ($branchId != '')
            {
                $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name ,s.doj,(select name FROM belt WHERE belt_id = s.belt_id) as belt_name,s.active, c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND f.coupon_id = c.coupon_id AND s.branch_id = '" . $branchId . "';";
                if ((!empty($startdate)) && (!empty($enddate)))
                {
                    $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name ,s.doj,(select name FROM belt WHERE belt_id = s.belt_id) as belt_name,s.active, c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND f.coupon_id = c.coupon_id AND s.branch_id = '" . $branchId . "' AND Date(CONCAT('" . $startdate . "', '-01')) <= Date(CONCAT(f.year , '-' , f.months , '-01')) AND Date(CONCAT('" . $enddate . "', '-01')) >= Date(CONCAT(f.year , '-' , f.months , '-01'))";
                }
                if ($branchId == '0')
                {
                    $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name ,s.doj,(select name FROM belt WHERE belt_id = s.belt_id) as belt_name,s.active, c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND f.coupon_id = c.coupon_id;";
                    if ((!empty($startdate)) && (!empty($enddate)))
                    {
                        $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name ,s.doj,(select name FROM belt WHERE belt_id = s.belt_id) as belt_name,s.active, c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND f.coupon_id = c.coupon_id AND Date(CONCAT('" . $startdate . "', '-01')) <= Date(CONCAT(f.year , '-' , f.months , '-01')) AND Date(CONCAT('" . $enddate . "', '-01')) >= Date(CONCAT(f.year , '-' , f.months , '-01'))";
                    }
                }
            }
            else if ((!empty($startdate)) && (!empty($enddate)))
            {
                $sql = "select f.* ,(SELECT name from branch WHERE branch_id = s.branch_id )as branch_name,concat(s.firstname,' ',s.lastname) as name ,s.doj,(select name FROM belt WHERE belt_id = s.belt_id) as belt_name,s.active, c.amount as discount from fees f, students s, coupon c WHERE f.student_id = s.student_id AND f.coupon_id = c.coupon_id AND Date(CONCAT('" . $startdate . "', '-01')) <= Date(CONCAT(f.year , '-' , f.months , '-01')) AND Date(CONCAT('" . $enddate . "', '-01')) >= Date(CONCAT(f.year , '-' , f.months , '-01'))";
            }

            $result = $con->query($sql);
            
            
$newData = array();
$uniqueIds = array();
$index = -1;
if ( $result->num_rows > 0 ) {
    // output data of each row
    while( $row = $result->fetch_assoc() ) {
        if ($row['mode'] != "cash") {
            $row['mode'] = "razorpay";
        }
        $feeDetails = array();
        
        if ( in_array( $row['student_id'], $uniqueIds ) ) {
            
            $feeDetails['months'] = $row['months'];
            $feeDetails['year'] = $row['year'];
            $feeDetails['mode'] = $row['mode'];
            $feeDetails['amount'] = $row['amount'];
            $feeDetails['fee_id'] = $row['fee_id'];
            
            array_push( $newData[$index]['fee_details'], $feeDetails );
            
        } else {
            array_push( $uniqueIds, $row['student_id'] );
            
            $feeDetailsArray = array();
            
            $feeDetails['months'] = $row['months'];
            $feeDetails['year'] = $row['year'];
            $feeDetails['mode'] = $row['mode'];
            $feeDetails['amount'] = $row['amount'];
            $feeDetails['fee_id'] = $row['fee_id'];
            array_push( $feeDetailsArray, $feeDetails );
            $index++;
            $newData[$index]['student_id'] = $row['student_id'];
            $newData[$index]['name'] = $row['name'];
            $newData[$index]['branch_name'] = $row['branch_name'];
            $newData[$index]['doj'] = $row['doj'];
            $newData[$index]['active'] = $row['active'];
            $newData[$index]['belt_name'] = $row['belt_name'];
            $newData[$index]['fee_details'] = $feeDetailsArray;           
        }
    }
}
$totalRow = count( $newData );
            

?>
<table id="example3" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>GR No</th>
            <th>Branch</th>
            <th>Name</th>
            <th>Belt</th>
            <th>Date of Join</th>
            <th>Active</th>
            <?php
            
            for ($i = $startMonth, $ii = $startYear; (($i <= $endMonth && $ii <= $endYear) || ($i >= $endMonth && $ii != $endYear)); $i++) {
                $monthName = date('F', mktime(0, 0, 0, $i, 10)); // March
                echo "<th>".$monthName."-".$ii."</th>";
                if ($i == 12) {
                    $i = 0;
                    $ii++;
                }
            }
            
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($totalRow > 0) {
                for ($x = 0; $x < $totalRow; $x++) {
    
                
                    echo "<tr>

          <td>" . $newData[$x]["student_id"] . "</td>
          <td>" . $newData[$x]["branch_name"] . "</td>
          <td>" . $newData[$x]["name"] . "</td>
          <td>" . $newData[$x]["belt_name"] . "</td>
		  <td>" . $newData[$x]["doj"] . "</td>
          <td>";
          if ($newData[$x]["active"] == 1)
          {
              echo "Yes";
          } else 
          {
              echo "No"; 
          }
          echo "</td>";

            for ($i = $startMonth, $ii = $startYear; (($i <= $endMonth && $ii <= $endYear) || ($i >= $endMonth && $ii != $endYear)); $i++) {
                $int = (int)$i;
                $iint = (int)$ii;
                
                $feeDetails = $newData[$x]['fee_details'];
                $monthsCount = count ($feeDetails);
                $index = 0;
                $found = 0;

                for ($j = 0; $j < $monthsCount; $j++) {
                    $int2 = (int)$feeDetails[$j]['months'];
                    $iint2 = (int)$feeDetails[$j]['year'];
                     if ($int == $int2 && $iint == $iint2) {
                         $found = 1;
                         $index = $j;
                         break;
                     }  
                    
                }
                if($found == 1)
                {
                            echo "<td>".
                            $feeDetails[$index]['fee_id']
                            ."<br>".
                            $feeDetails[$index]['mode']
                            ."<br>".
                            $feeDetails[$index]['amount']
                            ."</td>";
 
                }
                else 
                {
                       echo "<td>-</td>";

                }
                
                if ($i == 12) {
                    $i = 0;
                    $ii++;
                }
            }
                   
		  echo "</tr>";
                }
            }
            else
            {
?><tr>

            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr><?php
            }
?>
    </tbody>
</table>


<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Page specific script -->
<script>
    $(function() {
        $("#example3").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
        $('#example4').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });

</script>




<?php
        }
    }
}
?>
