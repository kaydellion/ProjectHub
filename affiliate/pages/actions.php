<?php

/*
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

*/


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
        
    } else {
        $message = "Failed to add product to your affiliate list: " . mysqli_error($con);
        showErrorModal('Oops', $message);
       
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
  
    // Sanitize and validate input
    $user_id = $_POST['user_id'];
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($con, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sex = mysqli_real_escape_string($con, $_POST['gender']);
    $mobile_number = mysqli_real_escape_string($con, $_POST['mobile_number']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $bank_name = mysqli_real_escape_string($con, $_POST['bank_name']);
    $bank_accname = mysqli_real_escape_string($con, $_POST['bank_accname']);
    $bank_number = mysqli_real_escape_string($con, $_POST['bank_number']);
    $facebook = mysqli_real_escape_string($con, $_POST['facebook']);
    $twitter = mysqli_real_escape_string($con, $_POST['twitter']);
    $instagram = mysqli_real_escape_string($con, $_POST['instagram']);
    $linkedln = mysqli_real_escape_string($con, $_POST['linkedln']);
    $kin_name = mysqli_real_escape_string($con, $_POST['kin_name']);
    $kin_number = mysqli_real_escape_string($con, $_POST['kin_number']);
    $kin_email = mysqli_real_escape_string($con, $_POST['kin_email']);
    $kin_relationship = mysqli_real_escape_string($con, $_POST['kin_relationship']);
    $biography = mysqli_real_escape_string($con, $_POST['biography']);


    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $retypePassword = !empty($_POST['retypePassword']) ? $_POST['retypePassword'] : null;
    $oldPassword = htmlspecialchars($_POST['oldpassword']);

    // Validate passwords match
    if ($password && $password !== $retypePassword) {
        $message= "Passwords do not match.";
    }

    // Validate old password
    $stmt = $con->prepare("SELECT password FROM ".$siteprefix."users WHERE s = ?");
    if ($stmt === false) {
        $message = "Error preparing statement: " . $con->error;
    } else {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user === null || !checkPassword($oldPassword, $user['password'])) {
            $message = "Old password is incorrect.";
        }
    }

    $uploadDir = '../uploads/';
    $fileKey='profile_picture';
    global $fileName;
    $profilePicture = $_FILES['profile_picture']['name'];

    // Update profile picture if a new one is uploaded
    if (!empty($profilePicture)) {
        $profilePicture = handleFileUpload($fileKey, $uploadDir, $fileName);
    } else {
        $profilePicture = $profile_picture; // Use the current profile picture if no new one is uploaded
    }

    // Update query
    $update_query = "
        UPDATE ".$siteprefix."users 
        SET 
            first_name = '$first_name',
            middle_name = '$middle_name',
            last_name = '$last_name',
            email = '$email',
            password = '$password',
            gender = '$sex',
            mobile_number = '$mobile_number',
            address = '$address',
            bank_name = '$bank_name',
            bank_accname = '$bank_accname',
            bank_number = '$bank_number',
            facebook = '$facebook',
            twitter = '$twitter',
            instagram = '$instagram',
            linkedln = '$linkedln',
            kin_name = '$kin_name',
            kin_number = '$kin_number',
            kin_email = '$kin_email',
            kin_relationship = '$kin_relationship',
            biography = '$biography',
            profile_picture = '$profilePicture'
        WHERE s = '$user_id'
    ";

    // Execute the query
    if (mysqli_query($con, $update_query)) {
        // Success modal
        $statusAction = "Success!";
        $statusMessage = "Profile updated successfully! $message";
        showSuccessModal($statusAction, $statusMessage);
        header("refresh:1; url=settings.php");
        
    } else {
        // Error modal
        $statusAction = "Error!";
        $statusMessage = "Failed to update profile: " . mysqli_error($con);
        showErrorModal($statusAction, $statusMessage);
       
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










