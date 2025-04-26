
<?php

require_once 'backend/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
  $item_id = $_POST['item_id'];
  
  // Get the order_id before deleting
  $stmt = $con->prepare("SELECT order_id,loyalty_id FROM pr_order_items WHERE s = ?");
  $stmt->bind_param("i", $item_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $cart_item = $result->fetch_assoc();
  $order_id = $cart_item['order_id'];
  $loyalty_id = $cart_item['loyalty_id'];


  $query = "SELECT * FROM pr_orders WHERE order_id = '$order_id'";
  $result = mysqli_query($con, $query);
  $row = mysqli_fetch_assoc($result);
  $user_id = $row['user'];
  
  // Delete the cart item
  $stmt = $con->prepare("DELETE FROM pr_order_items WHERE s = ?");
  $stmt->bind_param("i", $item_id);
  
  if ($stmt->execute()) {
    if($loyalty_id > 0){ increaseDownloads($con, $user_id);}
    // Get updated cart count
    $stmt = $con->prepare("SELECT COUNT(*) as count FROM pr_order_items WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $cartCount = $row['count'];
    
    // Get updated total
    $stmt = $con->prepare("SELECT SUM(price) as total FROM pr_order_items WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total = $row['total'] ?? 0;

    //update total orders in orders table
    $stmt = $con->prepare("UPDATE pr_orders SET total_amount = ? WHERE order_id = ?");
    $stmt->bind_param("di", $total, $order_id);
    $stmt->execute();
    
    echo json_encode([
      'success' => true,
      'cartCount' => $cartCount,
      'total' => number_format($total, 2)
    ]);
  } else {
    echo json_encode([
      'success' => false,
      'error' => 'Failed to delete item'
    ]);
  }
  
  $stmt->close();
  $con->close();
}
?>