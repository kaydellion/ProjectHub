<?php
// add_to_cart.php
require_once 'backend/connect.php';

// Initialize debug array
$debug = array(
    'post_data' => $_POST,
    'errors' => array(),
    'queries' => array(),
    'results' => array()
);

// Get POST data
$report_id = $_POST['reportId'];
$user_id = $_POST['userId'];
$order_id = $_POST['orderId'];
$file_id = $_POST['file_id'];
$affliate = $_POST['affliateId'];

// Debugging: Log POST data
$debug['post_data'] = $_POST;

// Get cart item count
$sql = "SELECT COUNT(*) as count FROM pr_order_items WHERE order_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_count = $result->fetch_assoc()['count'];

// Debugging: Log SQL and result for cart count
$debug['queries'][] = $sql;
$debug['results']['cart_count'] = $cart_count;

// Check if user is a loyalty member
$sql = "SELECT * FROM ".$siteprefix."users WHERE s  = '$user_id'";
$sql2 = mysqli_query($con, $sql);
$loyalty = 0; // Default loyalty value
while ($row = mysqli_fetch_array($sql2)) {
    $loyalty = $row['loyalty'];
}

// Debugging: Log loyalty check data
$debug['loyalty_check'] = [
    'user_id' => $user_id,
    'loyalty' => $loyalty
];

// Get price from reports table
$sql = "SELECT price,loyalty FROM pr_reports WHERE id = '$report_id'";
$result = $con->query($sql);
if (!$result || $result->num_rows == 0) {
    $debug['errors'][] = "Report not found.";
    echo json_encode(['success' => false, 'message' => 'Report not found', 'debug' => $debug]);
    exit();
}
$row = $result->fetch_assoc();

// Debugging: Log price query and result
$debug['queries'][] = $sql;
$debug['results']['price'] = $row['price'];
$report_loyalty=$row['loyalty'];

// Check if price is valid
$price = floatval($row['price']);
$original_price = $price;
if ($price == "") {
    $debug['errors'][] = "Invalid price value: " . $price;
    echo json_encode(['success' => false, 'message' => 'Invalid price value', 'debug' => $debug]);
    exit();
}

$sql_count = "SELECT COUNT(*) as count FROM pr_order_items WHERE item_id = '$file_id' AND report_id = '$report_id' AND order_id = '$order_id'";
$result_count = mysqli_query($con, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);

// Apply loyalty discount if applicable
if ($loyalty > 0 && $report_loyalty > 0) {
//select loyalty discount
$sql = "SELECT discount FROM pr_subscription_plans WHERE s = '$loyalty'";
$result = $con->query($sql);
if (!$result || $result->num_rows == 0) {
    $debug['errors'][] = "Loyalty plan not found.";
    echo json_encode(['success' => false, 'message' => 'Loyalty plan not found', 'debug' => $debug]);
    exit();
}
$row = $result->fetch_assoc();
// Debugging: Log loyalty discount query and result
$debug['queries'][] = $sql;
$discount = floatval($row['discount']);
if ($discount == "") {
    $debug['errors'][] = "Invalid discount value: " . $discount;
    echo json_encode(['success' => false, 'message' => 'Invalid discount value', 'debug' => $debug]);
    exit();
}
    $price = $price - ($price * $discount / 100);
    
    // Debugging: Log loyalty discount application
    $debug['loyalty_discount'] = [
        'discount' => $discount,
        'new_price' => $price
    ];

    // Check if the user has reached the maximum number of downloads for their loyalty plan
    $query = "SELECT downloads AS count FROM pr_users WHERE s = '$user_id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];

    // Debugging: Log download count query
    $debug['queries'][] = $query;
    $debug['results']['download_count'] = $count;

    // Check number of downloads allowed for loyalty plan user
    $query = "SELECT downloads FROM {$siteprefix}subscription_plans WHERE s = '$loyalty'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $downloads = $row['downloads'];

    // Debugging: Log downloads limit
    $debug['results']['downloads_limit'] = $downloads;

    if ($count < 1) {
        // Notify user and set loyalty to 0
        $query = "UPDATE pr_users SET loyalty = 0 WHERE s = '$user_id'";
        mysqli_query($con, $query);

        // Debugging: Log loyalty update query
        $debug['queries'][] = $query;

        $query = "SELECT lp.*, u.email, u.name AS display_name
                  FROM pr_loyalty_purchases lp
                  JOIN pr_users u ON lp.user_id = u.s
                  WHERE u.s = '$user_id'";
        $result = mysqli_query($con, $query);

        // Debugging: Log loyalty purchase query
        $debug['queries'][] = $query;

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
                $price = $original_price;
            }
        }
    } else {
    //deduct from downloads if item has not been added
    if ($row_count['count'] < 1) { decreaseDownloads($con, $user_id);}
    }
} else {
$loyalty=0;
}

// Debugging: Log price after loyalty check
$debug['results']['final_price'] = $price;

// Check if item already exists in order
if ($row_count['count'] > 0) {
    $sql = "UPDATE pr_order_items SET price = $price, original_price = $original_price, loyalty_id = '$loyalty' WHERE item_id = '$file_id' AND report_id = '$report_id' AND order_id = '$order_id'";
    mysqli_query($con, $sql);
    
    // Debugging: Log item update query
    $debug['queries'][] = $sql;

    echo json_encode(['success' => true, 'message' => 'Price updated', 'cartCount' => $cart_count, 'debug' => $debug]);
    exit();
}

// Insert order item
$sql = "INSERT INTO pr_order_items (report_id, item_id, price, original_price, loyalty_id, affiliate_id, order_id, date) 
        VALUES ('$report_id', '$file_id', $price, $original_price, $loyalty, '$affliate', '$order_id', CURRENT_TIMESTAMP)";
$con->query($sql);

// Debugging: Log insert order item query
$debug['queries'][] = $sql;

// Update order total
$sql = "UPDATE pr_orders SET total_amount = total_amount + $price WHERE order_id = '$order_id'";
$con->query($sql);

// Debugging: Log update order total query
$debug['queries'][] = $sql;

// Commit transaction
$con->commit();
echo json_encode(['success' => true, 'order_id' => $order_id, 'cartCount' => $cart_count + 1, 'debug' => $debug]);
exit();
?>
