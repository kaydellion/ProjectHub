<?php


$sql = "SELECT * FROM  ".$siteprefix."alerts WHERE status='0' ORDER BY s DESC LIMIT 5";
$sql2 = mysqli_query($con,$sql);
$notification_count = mysqli_num_rows($sql2);
 
if (isset($_GET['action']) && $_GET['action'] == 'read-message') {
    $sql = "UPDATE dv_alerts SET status='1' WHERE status='0'";
    $sql2 = mysqli_query($con,$sql);
    $message="All notifications marked as read.";
    showToast($message);
    header("refresh:2; url=notifications.php");
}


// add to affiliate list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_affiliate_list'])) {
    $user_id = $_POST['user_id']; 
    $affliate_id = $_POST['affliate_id'];
    // Assuming user ID is stored in the session
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']); // Sanitize product ID

    // Check if the product is already in the affiliate's list
    $check_query = "SELECT * FROM " . $siteprefix . "affiliate_products WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Product is already in the list
        $message = "This product is already in your affiliate list.";
        showToast($message);
        header("refresh:2; url=reports.php");
        exit();
    }


  
    // Generate affiliate link
    $base_url = $siteurl;
    $affiliate_link = urlencode($base_url . "?id=" . urlencode($product_id) . "&ref=" . urlencode($affliate_id));
     // Add product to affiliate's list
    $insert_query = "INSERT INTO " . $siteprefix . "affiliate_products (user_id, product_id, affiliate_link,affiliate_id) 
                     VALUES ('$user_id', '$product_id', '$affiliate_link','$affliate_id')";
    if (mysqli_query($con, $insert_query)) {
        $message = "Product added to your affiliate list successfully!";
        showSuccessModal('Processed', $message);
        header("refresh:1; url=reports.php");
        exit();
    } else {
        $message = "Failed to add product to your affiliate list: " . mysqli_error($con);
        showErrorModal('Oops', $message);
        exit();
    }
}



//withdrawwallet
if (isset($_POST['withdraw'])){
    $date=$currentdatetime;
    $bank=$_POST['bank'];
    $bankname=$_POST['bankname'];
    $bankno=$_POST['bankno'];
    $amount=$_POST['amount'];
    $status="pending";
    
    
    insertWithdraw($con, $user_id, $amount,$bank, $bankname, $bankno, $date, $status);
    $emailSubject="Withdrawal Request - Recieved";
    $emailMessage="<p>We have successfully received your withdrawal request of ₦$amount. Your request is now being processed and will be completed within the next 24 hours.";
    $emailMessage_admin="<p>A new withdrawal request has been recieved for ₦$amount. Please login into your dashboard to process it</p>";
    $adminmessage = "New Withdrawal Request - &#8358;$amount";
    $link="withdrawals.php";
    $msgtype='New Withdrawal';
    $message_status=1;
    insertadminAlert($con, $adminmessage, $link, $date, $msgtype, $message_status); 
    //sendEmail($email, $name, $siteName, $siteMail, $emailMessage, $emailSubject);
    //sendEmail($siteMail, $adminName, $siteName, $siteMail, $emailMessage_admin, $emailSubject);
        
       
    $statusAction="Successful";
    $statusMessage="Withdrawal Request Sent Sucessfully!";
    showSuccessModal($statusAction,$statusMessage);
    header("Refresh: 4; url=wallet.php");
    }
    
?>










