<?php
// add_to_cart.php
require_once 'backend/connect.php';

// Get POST data
$report_id = $_POST['reportId'];
$user_id = $_POST['userId'];
$order_id = $_POST['orderId'];
$file_id = $_POST['file_id'];
$affliate = $_POST['affliateId'];

      // Get cart item count
      $sql = "SELECT COUNT(*) as count FROM pr_order_items WHERE order_id = ?";
      $stmt = $con->prepare($sql);
      $stmt->bind_param("s", $order_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $cart_count = $result->fetch_assoc()['count'];

    //check if user is a loyalty member
    $sql = "SELECT * FROM ".$siteprefix."users  WHERE s  = '$user_id'";
    $sql2 = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($sql2)) {
    $loyalty = $row['loyalty']; }

    
    // Get price from reports table with basic query
    $sql = "SELECT price FROM pr_reports WHERE id = '$report_id'";
    $result = $con->query($sql);
    if (!$result || $result->num_rows == 0) {
       return "Report not found";
    }
    $row = $result->fetch_assoc();

    // Check if price is valid
    $price = floatval($row['price']);
    $original_price = $price;
    if ($price <= 0) {
       return "Invalid price value: " . $price;
    }
  
   


    
    // Check if item already exists in order
    $sql = "SELECT COUNT(*) as count FROM pr_order_items WHERE item_id = '$file_id' AND report_id = '$report_id' AND order_id = '$order_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['count'] > 0) {
        $sql = "UPDATE pr_order_items SET price = $price, original_price = $original_price, loyalty_id= '$loyalty' WHERE item_id = '$file_id' AND report_id = '$report_id' AND order_id = '$order_id'";
        mysqli_query($con, $sql); 
        echo json_encode(['success' => true, 'message' => 'Price updated', 'cartCount' => $price]);
        exit();
    }


    // Insert order item
    $sql = "INSERT INTO pr_order_items (report_id, item_id, price,original_price,loyalty_id,affiliate_id,order_id, date) 
            VALUES ('$report_id', '$file_id', $price, $original_price, $loyalty, '$affliate', '$order_id', CURRENT_TIMESTAMP)";
    $con->query($sql);

    // Update order total
    $sql = "UPDATE pr_orders SET total_amount = total_amount + $price WHERE order_id = '$order_id'";
    $con->query($sql);

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

    // Commit transaction
    $con->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id, 'cartCount' => $cart_count + 1]);
    exit();
?>