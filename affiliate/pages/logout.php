<?php
session_start();

$_SESSION = array();

if (isset($_COOKIE['userID'])) {
    setcookie("userID", "", time() - 3600, "/");
}

unset($_SESSION['id']);
unset($_SESSION['email']);
unset($_SESSION['password']);
unset($_SESSION['name']);

session_destroy();

// Always redirect to login or home after logout
header("Location: ../?message=Logged+Out+Successfully");
exit();
?>
