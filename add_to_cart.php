<?php
// At the start of the file, after the POST data collection
$debug = array(
    'post_data' => $_POST,
    'errors' => array(),
    'queries' => array(),
    'results' => array()
);

// Add debug info throughout the code, for example after the loyalty check:
$debug['loyalty_check'] = [
    'user_id' => $user_id,
    'loyalty' => $loyalty,
    'price' => $price,
    'original_price' => $original_price
];

// Add SQL queries to debug
$debug['queries'][] = $sql;

// Before final echo, modify the response to include debug info:
$response = [
    'success' => true,
    'order_id' => $order_id,
    'cartCount' => $cart_count + 1,
    'debug' => $debug
];

echo json_encode($response);
exit();
?>
