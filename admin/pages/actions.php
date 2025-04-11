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


//upload-report
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addcourse'])) {
    $reportId = $_POST['id'];
    $title = $_POST['title'];
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $preview = mysqli_real_escape_string($con, $_POST['preview']);
    $tableContent = mysqli_real_escape_string($con, $_POST['tableContent']);
    $category = $_POST['category'];
    $subcategory = isset($_POST['subcategory']) ? $_POST['subcategory'] : null;
    $pricing = $_POST['pricing'];
    $price = !empty($_POST['price']) ? $_POST['price'] : '0';
    $tags = $_POST['tags'];
    $loyalty = isset($_POST['loyalty']) ? 1 : 0;
    $documentTypes = isset($_POST['documentSelect']) ? $_POST['documentSelect'] : [];
    $status = $_POST['status'];
  
    // Upload images
    $uploadDir = '../../uploads/';
    $fileuploadDir = '../../documents/';
    $fileKey='images';
    global $fileName;
    $message="";

    $reportImages = handleMultipleFileUpload($fileKey, $uploadDir);
    if (empty($_FILES[$fileKey]['name'][0])) {
        // Array of default images
        $defaultImages = ['default1.jpg', 'default2.jpg', 'default3.jpg', 'default4.jpg', 'default5.jpg'];
        // Pick a random default image
        $randomImage = $defaultImages[array_rand($defaultImages)];
        $reportImages = [$randomImage];
    }
    
    $uploadedFiles = [];
    foreach ($reportImages as $image) {
        $stmt = $con->prepare("INSERT INTO  ".$siteprefix."reports_images (report_id, picture, updated_at) VALUES (?, ?, current_timestamp())");
        $stmt->bind_param("ss", $reportId, $image);
        if ($stmt->execute()) {
            $uploadedFiles[] = $image;
        } else {
            $message.="Error: " . $stmt->error;
        }
        $stmt->close();
    }
    // Handle file uploads
    $fileFields = [
        'file_word' => 'word',
        'file_excel' => 'excel',
        'file_pdf' => 'pdf',
        'file_powerpoint' => 'powerpoint',
        'file_text' => 'text'
    ];

    foreach ($fileFields as $fileField => $docType) {
        if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] == UPLOAD_ERR_OK) {
            $filePath = handleFileUpload($fileField, $fileuploadDir);
            $pagesField = 'pages_' . $docType;
            $pages = isset($_POST[$pagesField]) ? $_POST[$pagesField] : 0;

            $stmt = $con->prepare("INSERT INTO  ".$siteprefix."reports_files (report_id, title, pages, updated_at) VALUES (?, ?, ?, current_timestamp())");
            $stmt->bind_param("ssi", $reportId, $filePath, $pages);

            if ($stmt->execute()) {
                $message.="File uploaded and record added successfully!";
            } else {
                $message.="Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }


  
    // Insert data into the database
    $sql = "INSERT INTO ".$siteprefix."reports (s, id, title, description, preview, table_content, category, subcategory, pricing, price, tags, loyalty, user, created_date, updated_date, status) VALUES (NULL, '$reportId', '$title', '$description','$preview','$tableContent','$category', '$subcategory', '$pricing', '$price', '$tags', '$loyalty', '$user_id', current_timestamp(), current_timestamp(), '$status')";
    if (mysqli_query($con, $sql)) {
        $message .= "Report added successfully!";
    } else {
        $message .= "Error: " . mysqli_error($con);
    }

    showSuccessModal('Processed',$message);
    header("refresh:2; url=reports.php");
  }

//delete-record
  if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $table = $_GET['table'];
    $item = $_GET['item'];
    $page = $_GET['page'];
    
    if (deleteRecord($table, $item)) {
        $message="Record deleted successfully.";
    } else {
         $message="Failed to delete the record.";
    }

    showToast($message);
    header("refresh:1; url=$page");
}
// add plan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addPlan'])) {
    $planName = $_POST['planName'];
    $planPrice = $_POST['planPrice'];
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $discount = $_POST['discount'];
    $downloads = $_POST['downloads'];
    $durationCount= $_POST['durationCount'];
    $planDuration = $_POST['planDuration'];
    $planStatus = $_POST['planStatus'];
    $benefits = isset($_POST['benefits']) ? implode(", ", $_POST['benefits']) : '';

    // Upload Image
    $uploadDir = '../../uploads/';
    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];
    $fileKey = 'planImage';
    global $fileName;
    $message = "";

    if (!empty($_FILES[$fileKey]['name'])) {
        $fileType = mime_content_type($_FILES[$fileKey]['tmp_name']);
        if (in_array($fileType, $allowedImageTypes)) {
            $fileName = uniqid() . '_' . $_FILES[$fileKey]['name'];
            $filePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $filePath)) {
                $uploadedImage = $fileName;
            } else {
                $message .= "Error uploading image.<br>";
            }
        } else {
            $message .= "Invalid file type (Only JPG, PNG, GIF, WEBP allowed).<br>";
        }
    }

    // Assign a default image if none is uploaded
    if (empty($uploadedImage)) {
        $defaultImages = ['default1.jpg', 'default2.jpg', 'default3.jpg'];
        $uploadedImage = array_rand(array_flip($defaultImages));
    }

    // Insert subscription plan into the database
    $sql = "INSERT INTO " . $siteprefix . "subscription_plans (name, price, description, discount, downloads, duration,no_of_duration, status, benefits, image, created_at) 
            VALUES ('$planName', '$planPrice', '$description', '$discount', '$downloads', '$planDuration', '$durationCount','$planStatus', '$benefits', '$uploadedImage', current_timestamp())";

    if (mysqli_query($con, $sql)) {
        $message .= "Subscription plan added successfully!";
    } else {
        $message .= "Error: " . mysqli_error($con);
    }

    // Show success message and redirect
    showSuccessModal('Processed', $message);
    header("refresh:2; url=add-plan.php");
}
// manual payment rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reject_payment'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $rejection_reason = mysqli_real_escape_string($con, $_POST['rejection_reason']);
    $date = date('Y-m-d H:i:s');

    // Update the payment status to "payment resend" and store the rejection reason
    $update_query = "UPDATE " . $siteprefix . "manual_payments SET status = 'payment resend', rejection_reason = '$rejection_reason'  WHERE order_id = '$order_id'";
    if (mysqli_query($con, $update_query)) {
        // Fetch user details
        $user_query = "SELECT display_name, email FROM " . $siteprefix . "users WHERE s = '$user_id'";
        $user_result = mysqli_query($con, $user_query);

        if ($user_result && mysqli_num_rows($user_result) > 0) {
            $user = mysqli_fetch_assoc($user_result);
            $user_name = $user['display_name'];
            $user_email = $user['email'];

            // Send email to the user
            $emailSubject = "Payment Rejected for Order #$order_id";
            $emailMessage = "
                <p>Dear $user_name,</p>
                <p>Your payment for Order ID <strong>$order_id</strong> has been rejected for the following reason:</p>
                <p><em>$rejection_reason</em></p>
                <p>Please resubmit your payment proof to proceed with your order.</p>
                <p>Thank you.</p>
            ";

            sendEmail($user_email, $user_name, $siteName, $siteMail, $emailMessage, $emailSubject);
        }

        // Display success message
        $message = "Payment for Order ID $order_id has been rejected successfully.";
        showToast($message); // Use showToast to display the message
        header("refresh:2;");
       
    } else {
        // Display error message
        $message = "An error occurred while rejecting the payment. Please try again.";
        showErrorModal('Oops', $message); // Use showErrorModal to display the error
        header("refresh:2;");
       
    }
}
//update plans
if (isset($_POST['updatePlan'])) {
    $plan_id = $_POST['id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $discount = $_POST['discount'];
    $downloads = $_POST['downloads'];
    $duration = $_POST['planDuration'];
    $durationCount = $_POST['durationCount'];
    $status = $_POST['status'];
    
    // Handle benefits checkboxes
    $benefits = isset($_POST['benefits']) ? implode(", ", $_POST['benefits']) : "";

    // Image Upload Settings
    $uploadDir = '../../uploads/';
    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image.webp'];
    $fileKey = 'planImage';
    $message = "";
    $uploadedImage = "";

    // Check if an image is uploaded
    if (!empty($_FILES[$fileKey]['name'])) {
        $fileType = mime_content_type($_FILES[$fileKey]['tmp_name']);
        if (in_array($fileType, $allowedImageTypes)) {
            $fileName = uniqid() . '_' . $_FILES[$fileKey]['name'];
            $filePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $filePath)) {
                $uploadedImage = $fileName;
            } else {
                $message .= "Error uploading image.<br>";
            }
        } else {
            $message .= "Invalid file type (Only JPG, PNG, GIF, WEBP allowed).<br>";
        }
    }

    // Prepare the update query
    $query = "UPDATE " . $siteprefix . "subscription_plans 
              SET name='$name', price='$price', description='$description', discount='$discount', 
                  downloads='$downloads', duration='$duration',no_of_duration='$durationCount' ,status='$status', benefits='$benefits'";

    // Only update the image if a new one was uploaded
    if (!empty($uploadedImage)) {
        $query .= ", image='$uploadedImage'";
    }

    $query .= " WHERE s='$plan_id'";

    // Execute the query
    if (mysqli_query($con, $query)) {
        $message = "Plan updated successfully!";
        showSuccessModal('Processed', $message);
        header("refresh:1; url=edit-plan.php");
      
    } else {
        $message = "Update failed: " . mysqli_error($con);
        showToast($message);
        header("refresh:2; url=edit-plan.php");
        exit;
    }
}

//delete-plans
if (isset($_GET['action']) && $_GET['action'] == 'deleteplans') {
    $table = $_GET['table'];
    $item = $_GET['item'];
    $page = $_GET['page'];
    
    if (deleteRecord($table, $item)) {
        $message="Record deleted successfully.";
    } else {
         $message="Failed to delete the record.";
    }

    showToast($message);
    header("refresh:1; url=$page");
}


//approve payment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_payment'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
    $date = date('Y-m-d H:i:s');

    // Update the payment status to "approved"
    $update_query = "UPDATE " . $siteprefix . "manual_payments 
                     SET status = 'approved', rejection_reason = '' WHERE order_id = '$order_id'";
    if (mysqli_query($con, $update_query)) {
        // Insert into orders table
        $insert_query = "INSERT INTO " . $siteprefix . "orders (order_id, user, status, total_amount, date) 
                         VALUES ('$order_id', '$user_id', 'paid', '$amount', '$date')";
        if (mysqli_query($con, $insert_query)) {
            // Display success message
            $message = "Payment for Order ID $order_id has been approved successfully.";
            showSuccessModal('Processed', $message);
            header("refresh:2;");
           
        } else {
            // Display error message for order insertion failure
            $message = "An error occurred while inserting the order. Please try again.";
            showErrorModal('Oops', $message);
            header("refresh:2;");
         
        }
    } else {
        // Display error message for payment status update failure
        $message = "An error occurred while updating the payment status. Please try again.";
        showErrorModal('Oops', $message);
        header("refresh:2;");
       
    }
}

//update dispute status
if (isset($_POST['update-dispute'])){
    $dispute_id = $_POST['ticket_id'];
    $status = $_POST['status'];
    updateDisputeStatus($con, $siteprefix, $dispute_id, $status);

    //get dispute details
    $sql = "SELECT * FROM ".$siteprefix."disputes WHERE ticket_number='$dispute_id'";
    $sql2 = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($sql2);
    $ticket_number = $row['ticket_number'];
    $recipient_id = $row['recipient_id'];
    $sender_id = $row['user_id'];

    $emailSubject="Dispute Updated($ticket_number)";
    $emailMessage="<p>This dispute status has been updated to $status</p>";
    $message = "Dispute status updated to $status: " . $ticket_number;
    $status=0;
    $date = date("Y-m-d H:i:s");

    //notify sender and if recipient exists
    $sDetails = getUserDetails($con, $siteprefix, $sender_id);
    $s_email = $sDetails['email'];
    $s_name = $sDetails['display_name'];
    //sendEmail($s_email, $s_name, $siteName, $siteMail, $emailMessage, $emailSubject);
    insertAlert($con, $sender_id, $message, $date, $status);

    if($recipient_id){
        $rDetails = getUserDetails($con, $siteprefix, $recipient_id);
        $r_email = $rDetails['email'];
        $r_name = $rDetails['display_name'];
       //sendEmail($r_email, $r_name, $siteName, $siteMail, $emailMessage, $emailSubject);
       insertAlert($con, $recipient_id, $message, $date, $status);
    }
    $message="Dispute status updated successfully.";
    showToast($message);
}

//send-message
 if (isset($_POST['send_dispute_message'])) {
    $dispute_id = $_POST['dispute_id'];
    $sender_id = $user_id; // Assume logged-in user
    $message = mysqli_real_escape_string($con, $_POST['message']);
    $page = "ticket.php?ticket_number=$dispute_id";
    $new_status = "awaiting-response";

    $fileKey = 'attachment';
    $uploadDir = '../../uploads/';
    $reportImages = handleMultipleFileUpload($fileKey, $uploadDir);
    $uploadedFiles =  implode(', ', $reportImages);
    if (empty($_FILES[$fileKey]['name'][0])) {
        $uploadedFiles = '';
    }

      //get dispute details
      $sql = "SELECT * FROM ".$siteprefix."disputes WHERE ticket_number='$dispute_id'";
      $sql2 = mysqli_query($con,$sql);
      $row = mysqli_fetch_array($sql2);
      $ticket_number = $row['ticket_number'];
      $recipient_id = $row['recipient_id'];
      $sender_id = $row['user_id'];

    
    $sql = "INSERT INTO ".$siteprefix."dispute_messages (dispute_id, sender_id, message, file) 
        VALUES ('$dispute_id', '$user_id', '$message', '$uploadedFiles')";
    if (mysqli_query($con, $sql)) {
    // Then call the function where needed:
    $emailSubject="Dispute Updated($ticket_number)";
    $emailMessage="<p>This dispute status has been updated to $status</p>";
    $message = "Dispute status updated to $status: " . $ticket_number;
    $status=0;
    $date = date("Y-m-d H:i:s");

    //notify sender and if recipient exists
    $sDetails = getUserDetails($con, $siteprefix, $sender_id);
    $s_email = $sDetails['email'];
    $s_name = $sDetails['display_name'];
    //sendEmail($s_email, $s_name, $siteName, $siteMail, $emailMessage, $emailSubject);
    insertAlert($con, $sender_id, $message, $date, $status);

    if($recipient_id){
        $rDetails = getUserDetails($con, $siteprefix, $recipient_id);
        $r_email = $rDetails['email'];
        $r_name = $rDetails['display_name'];
       //sendEmail($r_email, $r_name, $siteName, $siteMail, $emailMessage, $emailSubject);
       insertAlert($con, $recipient_id, $message, $date, $status);
    }
    updateDisputeStatus($con, $siteprefix, $dispute_id, $new_status);
    showToast("Message sent successfully!");

    } else {
    $message = "Error: " . mysqli_error($con);
    showErrorModal('Oops', $message);
    }
}

//manage wallet
if (isset($_POST['update-wallet-dispute'])) {
$user= $_POST['user'];
$amount= $_POST['amount'];
$dispute_id= $_POST['dispute_id'];
$walletaction= $_POST['wallet-action'];


    $rDetails = getUserDetails($con, $siteprefix, $user);
    $r_email = $rDetails['email'];
    $r_name = $rDetails['display_name'];
  


if($walletaction=='add'){
    $type="credit";
    $emailMessage="Your wallet has been credited with $sitecurrency$amount";
    $sql = "UPDATE ".$siteprefix."users SET wallet=wallet+$amount WHERE s='$user'";
    $sql2 = mysqli_query($con,$sql);
    $message="Wallet credited successfully.";
}
if($walletaction=='deduct'){
    $type="debit";
    $emailMessage="Your wallet has been debited with $sitecurrency$amount";
    $sql = "UPDATE ".$siteprefix."users SET wallet=wallet-$amount WHERE s='$user'";
    $sql2 = mysqli_query($con,$sql);
    $message="Wallet debited successfully.";
}

$note="Dispute Resolution: $dispute_id";
$date = date("Y-m-d H:i:s");
$emailSubject="Wallet Update";
$alertmessage = "You wallet amount has been modified. kindly check your wallet for details.";
$status=0;

//sendEmail($r_email, $r_name, $siteName, $siteMail, $emailMessage, $emailSubject);
insertAlert($con, $user, $alertmessage, $date, $status);
insertWallet($con, $user, $amount, $type, $note, $date);
showSuccessModal('Processed',$message);
}
?>










