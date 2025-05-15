<?php

// place order
include "header.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Get the order ID and user ID from the form
    $order_id = $_POST['order_id'];
    $user_id = $_POST['user_id'];

    /* Mark the order as paid
    $sql_update_order = "UPDATE ".$siteprefix."orders SET status = 'paid' WHERE order_id = '$order_id'";
    mysqli_query($con, $sql_update_order);*/

    // Redirect to the success page
    header("Location: https://projectreporthub.ng/pay_success.php?ref=$order_id");
    exit;
}

?>