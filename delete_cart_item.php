<?php

require_once 'backend/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
  $item_id = (int)$_POST['item_id']; // cast to int for basic sanitization

  // Get the order_id before deleting
  $result = mysqli_query($con, "SELECT order_id, loyalty_id FROM pr_order_items WHERE s = '$item_id'");
  $cart_item = mysqli_fetch_assoc($result);
  $order_id = $cart_item['order_id'];
  $loyalty_id = $cart_item['loyalty_id'];

  $result = mysqli_query($con, "SELECT * FROM pr_orders WHERE order_id = '$order_id'");
  $row = mysqli_fetch_assoc($result);
  $user_id = $row['user'];

  // Delete the cart item
  if (mysqli_query($con, "DELETE FROM pr_order_items WHERE s = '$item_id'")) {
    if ($loyalty_id > 0) {
      increaseDownloads($con, $user_id);
    }

    // Get updated cart count
    $result = mysqli_query($con, "SELECT COUNT(*) as count FROM pr_order_items WHERE order_id ='$order_id'");
    $row = mysqli_fetch_assoc($result);
    $cartCount = $row['count'];

    // Get updated total
    $result = mysqli_query($con, "SELECT SUM(price) as total FROM pr_order_items WHERE order_id = '$order_id'");
    $row = mysqli_fetch_assoc($result);
    $total = $row['total'] ?? 0;

    // Update total in orders table
    mysqli_query($con, "UPDATE pr_orders SET total_amount = $total WHERE order_id = '$order_id'");

    echo json_encode([
      'success' => true,
      'cartCount' => $cartCount,
      'total' => formatNumber($total, 2)
    ]);
  } else {
    echo json_encode([
      'success' => false,
      'error' => 'Failed to delete item'
    ]);
  }

  mysqli_close($con);
}
?>
