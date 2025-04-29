<?php session_start();

$_SESSION = array();

if (isset($_COOKIE['userID'])) {
    setcookie("userID", "", time()-3600);
}

unset($_SESSION['id']);


$message = "Logged Out Successfully";


session_destroy();

if($_COOKIE['userID']){
header("Location: index.php");
} else {
print "<script>alert('Could not log you out, sorry the system encountered an error.');</script>";
header("refresh:0; url=index.php");
exit();
} 

?> 

