<?php
$con  = mysqli_connect("localhost","root","","bulakbuy");
 if (!$con) {
     # code...
    echo "Problem in database connection! Contact administrator!" . mysqli_error();
 }else{
         $sql ="SELECT * FROM sales";
         $result = mysqli_query($con,$sql);
         $chart_data="";
         while ($row = mysqli_fetch_array($result)) { 
 
            $productname[]  = $row['product_id']  ;
            $sales[] = $row['amount'];
        }
 
 
 }
 
 
?>