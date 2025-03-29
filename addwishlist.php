<?php
include "backend/connect.php";
$siteprefix="pr_";

$user = $_POST['user'];
if($user!==''){ 
$user = $_POST['user'];
$productid = $_POST['productId'];
    
    

$checkEmail = mysqli_query($con, "SELECT * FROM ".$siteprefix."wishlist WHERE user='$user' AND product='$productid'");
if(mysqli_num_rows($checkEmail) >= 1 ) {
$del = mysqli_query($con,"DELETE from ".$siteprefix."wishlist WHERE user='$user' AND product='$productid'") or die ('Could not connect: ' .mysqli_error($con)); 
echo 'removed';
}  
    
else{
//current date
$date=date("Y-m-d H:i:s");
  
//submit to stock_orders table
$submit = mysqli_query($con,"insert into ".$siteprefix."wishlist (user, product, date) values ('$user','$productid','$date')") or die ('Could not connect: ' .mysqli_error($con));
echo "success";
}}

else{
echo'redirect';
}

?>