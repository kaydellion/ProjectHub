<?php include "header.php"; ?>





<?php 
//get ref number which is user id from paystack
  global $ref;
  $ref = $_GET['ref'];
  $date=date('Y-m-d H:i:s');
  $attachments = array();	

//get order details and order items
$sql = "SELECT * FROM ".$siteprefix."orders WHERE order_id = '$ref' AND status = 'unpaid'";
$sql2 = mysqli_query($con,$sql);
if (mysqli_affected_rows($con) == 0){
//echo "Order not found. Contact Support"; 
} 
else {
while($row = mysqli_fetch_array($sql2)){
    $order_id = $row['order_id']; 
    $user_id = $row['user']; 
    $status = $row['status']; 
    $total_amount = $row['total_amount']; 
    $date = $row['date']; 
}
}
//get order items
$sql = "SELECT * FROM ".$siteprefix."order_items WHERE order_id = '$ref'";   
$sql2 = mysqli_query($con,$sql);
if (mysqli_affected_rows($con) == 0){
//echo "Order items not found. Contact Support"; 
} 
else {
while($row = mysqli_fetch_array($sql2)){
    $s = $row['s']; 
    $report_id = $row['report_id']; 
    $item_id = $row['item_id']; 
    $price = $row['price']; 
    $original_price = $row['original_price']; 
    $loyalty_id = $row['loyalty_id']; 
    $affliate_id = $row['affliate_id']; 
    $order_id = $row['order_id']; 
    $date = $row['date'];   

//check if any of them have an affliate
if($affliate_id != 0){
    //get affliate details
    $sql = "SELECT * FROM ".$siteprefix."users WHERE affliate = '$affliate_id'";
    $sql2 = mysqli_query($con,$sql);
    if (mysqli_affected_rows($con) == 0){ //echo "Affliate not found. Contact Support"; 
    } 
    else {
    while($row = mysqli_fetch_array($sql2)){
        $affliate_id = $row['affliate_id']; 
        $user_id = $row['user_id']; 
        $affliate_amount = $row['affliate_amount']; 
        $date = $row['date']; 
    
    //calculate amount with a percentage 6% from $price
    $affliate_amount = $price * ($affliate_percentage *100);

    //update affliate wallet amount
    $sql = "UPDATE ".$siteprefix."users SET wallet = wallet + $affliate_amount WHERE affliate = '$affliate_id'";
    mysqli_query($con, $sql);

    //insert into affliate transactions
    $note = "Affliate Earnings from Order ID: ".$order_id;
    $amount = $affliate_amount;
    $type = "credit";
    insertWallet($con, $user_id, $amount, $type, $note, $date);
    
    //notify affliate
    $message = "You have earned $sitecurrency $affliate_amount from Order ID: $order_id";
    $link = "wallet.php";
    $msgtype = "wallet";
    $status = 0;
    insertadminAlert($con, $message, $link, $date, $msgtype, $status);
    }} //end affliate loop

    //check each of their files with item_id from report_files and append them to be able to send to mail
    $sql = "SELECT * FROM ".$siteprefix."report_files WHERE item_id = '$item_id'";
    $sql2 = mysqli_query($con,$sql);
    if (mysqli_affected_rows($con) == 0){
    //echo "Report files not found. Contact Support";
   } 
    else {
    while($row = mysqli_fetch_array($sql2)){
        $id = $row['id']; 
        $report_id = $row['report_id']; 
        $title = $row['title']; 
        $pages = $row['pages']; 
        $updated_at = $row['updated_at']; 

    //append document itself to an array to mail
    $attachments[] = $siteurl."documents/".$title;
    }} //end report files loop     
}} //end order items loop

//update order status to paid
$sql = "UPDATE ".$siteprefix."orders SET status = 'paid' WHERE order_id = '$ref'";
mysqli_query($con, $sql);

$vendorEmail = $email;
$vendorName = $username;
$emailMessage = "Thank you for your order. Your invoice is attached.";
$emailSubject = "Order Confirmation";

//send email
sendEmail($vendorEmail, $vendorName, $siteName, $siteMail, $emailMessage, $emailSubject, $attachments);


}//end order loop

$sql = "SELECT ri.*, r.title as report_name, r.description as report_description 
    FROM ".$siteprefix."order_items ri 
    INNER JOIN ".$siteprefix."reports r ON ri.report_id = r.id 
    WHERE ri.order_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $ref);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $purchased_items[] = $row;
}

if (empty($purchased_items)) {
    $message = "No items found for this order.";
} else {
    $message = "Thank you for your purchase! Here’s what you bought:";
}

?>

<div class="container mt-5 mb-5">
<div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Payment Successful!</h4>
                <p>Your payment was successful. An email has been sent to you with your invoice.</p>
                <hr>
                <p class="mb-0">Thank you for your order.</p>
            </div>
    <div class="card text-center">
        <div class="card-header bg-success text-white">
            🎉 Thank You for Your Purchase!
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo $message; ?></h5>
            <?php if (!empty($purchased_items)): ?>
                <ul class="list-group mt-3">
                    <?php foreach ($purchased_items as $item): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?php echo htmlspecialchars($item['report_name']); ?></span>
                            <strong><?php echo $sitecurrency; echo number_format($item['price'], 2); ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <a href="my_orders.php" class="btn btn-primary mt-4"> 🔙 Back to My Orders</a>
        </div>
        <div class="card-footer text-muted">
            We appreciate your business! 💖
        </div>
    </div>
</div>

<?php include "footer.php"; ?>