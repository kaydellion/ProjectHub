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
  
    // Apply loyalty discount if applicable
    if ($loyalty > 0){

        $price = $price - ($price * $discount / 100);
       
        // Check if the user has reached the maximum number of downloads for their loyalty plan
        $query = "SELECT COUNT(r.*) as count FROM pr_order_items r
        LEFT JOIN pr_orders o ON o.order_id = r.order_id
        WHERE o.user = '$user_id' AND status != 'cancelled'
        AND original_price != $price";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];

        //check number of downloads allowed for loyalty plan user is on
        $query = "SELECT downloads FROM {$siteprefix}subscription_plans WHERE s = '$loyalty'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $downloads = $row['downloads'];
        if ($count >= $downloads) {
            // Notify user and set loyalty to 0
            $query = "UPDATE pr_users SET loyalty = 0 WHERE s = '$user_id'";
            mysqli_query($con, $query);
    
          $query = "SELECT lp.*, u.email, u.name AS display_name
          FROM pr_loyalty_purchases lp
          JOIN pr_users u ON lp.user_id = u.s
          WHERE u.s = '$user_id'";
          $result = mysqli_query($con, $query);
         if (mysqli_num_rows($result) > 0) {
         while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $email = $row['email'];
        $display_name = $row['display_name'];
        $plan_id = $row['loyalty_id'];
        $end_date = $row['end_date'];

        // Email details
        $emailSubject = "Your Subscription Has Expired";
        $emailMessage = "<p>Dear $display_name,</p>
                         <p>Your subscription for plan ID $plan_id has expired on $end_date. Please log in to your account to renew your subscription.</p>
                         <p>Thank you for using our service!</p>";
        // Send email to the user
        sendEmail($email, $display_name, $siteName, $siteMail, $emailMessage, $emailSubject);
        
        $price= $original_price ;
    }}}

        
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

    // Commit transaction
    $con->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id, 'cartCount' => $cart_count + 1]);
    exit();
?>