<?php
// add_to_cart.php


require_once 'backend/connect.php';

// Get POST data
$report_id = $_POST['reportId'];
$user_id = $_POST['userId'];
$order_id = $_POST['orderId'];
$file_id = $_POST['file_id'];

try {
    // Start transaction
    $con->begin_transaction();

      // Get cart item count
      $sql = "SELECT COUNT(*) as count FROM pr_order_items WHERE order_id = ?";
      $stmt = $con->prepare($sql);
      $stmt->bind_param("s", $order_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $cart_count = $result->fetch_assoc()['count'];
    
    // Get price from reports table with basic query
    $sql = "SELECT price FROM pr_reports WHERE id = '$report_id'";
    $result = $con->query($sql);
    if (!$result || $result->num_rows == 0) {
        throw new Exception("Report not found");
    }
    $row = $result->fetch_assoc();
    $price = floatval($row['price']);
    if ($price <= 0) {
        throw new Exception("Invalid price value: " . $price);
    }

    // Check if item already exists in order
    $sql = "SELECT COUNT(*) as count FROM pr_order_items WHERE item_id = '$file_id' AND order_id = '$order_id'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        // Update existing item price
        $sql = "UPDATE pr_order_items SET price = $price WHERE item_id = '$file_id' AND order_id = '$order_id'";
        $con->query($sql);
        
        echo json_encode(['success' => true, 'message' => 'Price updated', 'cartCount' => $cart_count]);
        exit();
    }


    // Insert order item
    // Insert order item
    $sql = "INSERT INTO pr_order_items (report_id, item_id, price, order_id, date) 
            VALUES ('$report_id', '$file_id', $price, '$order_id', CURRENT_TIMESTAMP)";
    $con->query($sql);

    // Update order total
    $sql = "UPDATE pr_orders SET total_amount = total_amount + $price WHERE order_id = '$order_id'";
    $con->query($sql);

    // Commit transaction
    $con->commit();

    echo json_encode(['success' => true, 'order_id' => $order_id, 'cartCount' => $cart_count + 1]);

} catch (Exception $e) {
    // Rollback on error
    $con->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$con->close();
?>