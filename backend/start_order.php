<?php

// Check if user is logged in
if (isset($active_log) && $active_log == 1) {
    // Check for existing unpaid order
    $stmt = $con->prepare("SELECT * FROM pr_orders WHERE user = ? AND status = 'unpaid' ORDER BY date DESC LIMIT 1");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Existing unpaid order found
        $order = $result->fetch_assoc();
        setcookie('order', $order['order_id'], time() + (86400 * 30), "/"); // 30 days cookie
        $order_id = $order['order_id'];
    } else {
        // Create new order
        $order_id = uniqid('ORD');
        $current_timestamp = date('Y-m-d H:i:s');
        
        $stmt = $con->prepare("INSERT INTO pr_orders (order_id, user, status, total_amount, date) VALUES (?, ?, 'unpaid', '0', ?)");
        $stmt->bind_param("sss", $order_id, $user_id, $current_timestamp);
        
        if ($stmt->execute()) {
            setcookie('order', $order_id, time() + (86400 * 30), "/");
        }
    }
} else { $order_id = ''; } 
?>