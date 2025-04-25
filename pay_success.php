<?php include "header.php"; 

// Get reference number (user ID) from Paystack
global $ref;
$ref = $_GET['ref'];
$date = date('Y-m-d H:i:s');
$attachments = array();

// Get order details and order items
$sql_order = "SELECT * FROM ".$siteprefix."orders WHERE order_id = '$ref' AND status = 'unpaid'";
$sql_order_result = mysqli_query($con, $sql_order);
if (mysqli_affected_rows($con) > 0) {
    while ($row_order = mysqli_fetch_array($sql_order_result)) {
        $order_id = $row_order['order_id']; 
        $user_id = $row_order['user']; 
        $status = $row_order['status']; 
        $total_amount = $row_order['total_amount']; 
        $date = $row_order['date']; 
    }
}

// Get order items
$sql_items = "SELECT * FROM ".$siteprefix."order_items WHERE order_id = '$ref'";   
$sql_items_result = mysqli_query($con, $sql_items);
if (mysqli_affected_rows($con) > 0) {
    while ($row_item = mysqli_fetch_array($sql_items_result)) {
        $s = $row_item['s']; 
        $report_id = $row_item['report_id']; 
        $item_id = $row_item['item_id']; 
        $price = $row_item['price']; 
        $original_price = $row_item['original_price']; 
        $loyalty_id = $row_item['loyalty_id']; 
        $affiliate_id = $row_item['affiliate_id']; 
        $order_id = $row_item['order_id']; 
        $date = $row_item['date'];   

        // Check if the item has an affiliate
        if ($affiliate_id != 0) {
            // Get affiliate details
            $sql_affiliate = "SELECT * FROM ".$siteprefix."users WHERE affiliate = '$affiliate_id'";
            $sql_affiliate_result = mysqli_query($con, $sql_affiliate);
            if (mysqli_affected_rows($con) > 0) {
                while ($row_affiliate = mysqli_fetch_array($sql_affiliate_result)) {
                    $affiliate_user_id = $row_affiliate['user_id']; 
                    $affiliate_amount = $price * ($affiliate_percentage / 100);
                    
                    // Update affiliate wallet
                    $sql_update_affiliate_wallet = "UPDATE ".$siteprefix."users SET wallet = wallet + $affiliate_amount WHERE affiliate = '$affiliate_id'";
                    mysqli_query($con, $sql_update_affiliate_wallet);
                    
                    // Insert into affiliate transactions
                    $note = "Affiliate Earnings from Order ID: ".$order_id;
                    $type = "credit";
                    insertWallet($con, $affiliate_user_id, $affiliate_amount, $type, $note, $date);
                    
                    // Notify affiliate
                    $message = "You have earned $sitecurrency$affiliate_amount from Order ID: $order_id";
                    $link = "wallet.php";
                    $msgtype = "wallet";
                    $status = 0;
                    insertadminAlert($con, $message, $link, $date, $msgtype, $status);
                }
            }
        }


        // Get seller ID
        $sql_seller = "SELECT u.s AS user, u.* FROM ".$siteprefix."users u LEFT JOIN ".$siteprefix."reports r ON r.user=u.s WHERE r.id = '$report_id'";
        $sql_seller_result = mysqli_query($con, $sql_seller);
        if (mysqli_affected_rows($con) > 0) {
            while ($row_seller = mysqli_fetch_array($sql_seller_result)) {
                $seller_id = $row_seller['user']; 
                $vendorEmail = $row_seller['email'];
                $vendorName = $row_seller['display_name'];
                $sellertype = $row_seller['type'];
                $admin_commission=0;

        
        if($sellertype=="user"){
        // Admin commission deduction
        $admin_commission = $price * ($escrowfee / 100);
        $sql_insert_commission = "INSERT INTO ".$siteprefix."profits (amount, report_id, order_id, date) VALUES ('$admin_commission', '$report_id', '$order_id', '$date')";
        mysqli_query($con, $sql_insert_commission);
        
        // Notify admin
        $message = "Admin Commission of $sitecurrency$admin_commission from Order ID: $order_id";
        $link = "profits.php";
        $msgtype = "profits";
        insertadminAlert($con, $message, $link, $date, $msgtype, 0);
        }
                
                // Credit seller
                $seller_amount = $price - $admin_commission;
                $sql_update_seller_wallet = "UPDATE ".$siteprefix."users SET wallet = wallet + $seller_amount WHERE s = '$seller_id'";
                mysqli_query($con, $sql_update_seller_wallet);
                
                // Insert seller transactions
                $note = "Payment from Order ID: ".$order_id;
                $type = "credit";
                insertWallet($con, $seller_id, $seller_amount, $type, $note, $date);
                
                // Notify seller
                insertAlert($con, $seller_id, "You have received $sitecurrency$seller_amount from Order ID: $order_id", $date, 0);
                
               // Enhanced email content
$emailSubject = "New Sale on Project Report Hub – Let’s Keep the Momentum Going!";
$emailMessage = "
<p>Hello $vendorName,</p>
<p>Great news! A new sale has just been made on ProjectReportHub.ng.</p>
<p><strong>Title of Resource:</strong> $resourceTitle</p>
<p><strong>Price:</strong> $sitecurrency$price</p>
<p><strong>Earning:</strong> $sitecurrency$seller_amount</p>
<p>This is a win for our community and a reminder that students and researchers are actively exploring and purchasing resources from our platform every day.</p>
<p>If you haven’t updated your listings recently, now is a great time to:</p>
<ol>
    <li>Refresh your content and pricing</li>
    <li>Promote your reports on social media</li>
    <li>Add new documents that reflect trending industries</li>
</ol>
<p>The more visible and updated your resources are, the more sales opportunities you create.</p>
<p>Let’s keep the momentum going and continue providing high-value insights to Nigeria and the world!</p>
<p>Warm regards,</p>
<p>The Project Report Hub Team<br>
<a href='mailto:hello@projectreporthub.ng'>hello@projectreporthub.ng</a> | <a href='https://www.projectreporthub.ng'>www.projectreporthub.ng</a></p>
";

// Send email to seller
sendEmail($vendorEmail, $vendorName, $siteName, $siteMail, $emailMessage, $emailSubject);
            }
        }
    }
}

// Update order status to paid
$sql_update_order = "UPDATE ".$siteprefix."orders SET status = 'paid' WHERE order_id = '$ref'";
mysqli_query($con, $sql_update_order);

// Send order confirmation email
$subject = "Order Confirmation";
$emailMessage="<p>Thank you for your order. We appreciate your business!<br>
The resources have been sent to your email address and it is also available on your profile.<br>
Feel free to visit our website for more information, updates, or to explore additional services.</p>";

sendEmail($email, $username, $siteName, $siteMail,$emailMessage, $subject, $attachments);
?>

<div class="container mt-5 mb-5">
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Payment Successful!</h4>
        <p>Your payment was successful. An email has been sent to you with your invoice.</p>
        <hr>
        <p class="mb-0">Thank you for your order.</p>
    </div>
    <div class="card text-center">
        <div class="card-header bg-success text-white">🎉 Thank You for Your Purchase!</div>
        <div class="card-body">
            <h5 class="card-title">Order processed successfully.</h5>
            <a href="my_orders.php" class="btn btn-primary mt-4"> 🔙 Back to My Orders</a>
        </div>
        <div class="card-footer text-muted">We appreciate your business! 💖</div>
    </div>
</div>

<?php include "footer.php"; ?>
