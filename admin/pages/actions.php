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
    $sql = "INSERT INTO ".$siteprefix."reports (s, id, title, description, category, subcategory, pricing, price, tags, loyalty, user, created_date, updated_date, status) VALUES (NULL, '$reportId', '$title', '$description', '$category', '$subcategory', '$pricing', '$price', '$tags', '$loyalty', '$user_id', current_timestamp(), current_timestamp(), '$status')";
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










