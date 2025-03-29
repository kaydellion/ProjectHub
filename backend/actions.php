<?php

//get total order amount
if($active_log==1){
    $sql = "SELECT SUM(price) as total FROM pr_order_items WHERE order_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $order_total = empty($row['total']) ? 0 : $row['total'];

    //update total orders in orders table
    $sql = "UPDATE pr_orders SET total_amount = ? WHERE order_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ds", $order_total, $order_id);
    $stmt->execute();
    $stmt->close();
} else ($order_total = 0);



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
  
    // Upload images
    $uploadDir = 'uploads/';
    $fileuploadDir = 'documents/';
    $fileKey='images';
    global $fileName;
    $message="";
    $status="pending";

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
    header("refresh:2; url=models.php");
  }


//new-registertion
if(isset($_POST['register-user'])){

    $email = $_POST['email'];
    $password = $_POST['password'];
    $retypePassword = $_POST['retypePassword'];
    $seller = !empty($_POST['register_as_seller']) ? 1 : 0;
    

       //status
       $status='inactive';
       $date=date('Y-m-d H:i:s');

       //profile picture
       $uploadDir = 'uploads/';
       $fileKey='profile_picture';
       global $fileName;
   
       // Update profile picture if a new one is uploaded
       if (!empty($profilePicture)) {
           $profilePicture = handleFileUpload($fileKey, $uploadDir, $fileName);
       } else {
           $profilePicture = 'user.png'; // Use the current profile picture if no new one is uploaded
       }
  
  //error for same email,password errors
  $checkEmail = mysqli_query($con, "SELECT * FROM ".$siteprefix."users WHERE email='$email'");
  if(mysqli_num_rows($checkEmail) >= 1 ) {
  $statusAction="Ooops!";
  $statusMessage="This email has already been registered. Please try registering another email.";
  showErrorModal($statusAction, $statusMessage); } 	

  //check if password is less than 6
  else if (strlen($password) < 6){
      $statusAction="Try Again";
      $statusMessage="Password must have 8 or more characters";
      showErrorModal($statusAction, $statusMessage);
  }	
  //check if password match									
  else if ($password !== $retypePassword ){
      $statusAction="Ooops!";
      $statusMessage="Password do not match!";
      showErrorModal($statusAction, $statusMessage);
  }

       else {
       $password=hashPassword($password);
      
        // Prepare and bind
        $display_name = mysqli_real_escape_string($con, $_POST['display_name']);
        $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
        $middle_name = mysqli_real_escape_string($con, $_POST['middle_name']);
        $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
        $profile_picture = mysqli_real_escape_string($con, $profilePicture);
        $mobile_number = mysqli_real_escape_string($con, $_POST['mobile_number']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $gender = mysqli_real_escape_string($con, $_POST['gender']);
        $address = mysqli_real_escape_string($con, $_POST['address']);
        $type = 'user';
        $status = $status;
        $last_login = $date;
        $created_date = date("Y-m-d H:i:s");
        $preference = '';
        $bank_name = mysqli_real_escape_string($con, $_POST['bank_name']);
        $bank_accname = mysqli_real_escape_string($con, $_POST['bank_accname']);
        $bank_number = mysqli_real_escape_string($con, $_POST['bank_number']);
        $loyalty = '0';
        $wallet = '0';
        $affliate = '0';
        $facebook = mysqli_real_escape_string($con, $_POST['facebook']);
        $twitter = mysqli_real_escape_string($con, $_POST['twitter']);
        $instagram = mysqli_real_escape_string($con, $_POST['instagram']);
        $linkedln = mysqli_real_escape_string($con, $_POST['linkedln']);
        $kin_name = mysqli_real_escape_string($con, $_POST['kin_name']);
        $kin_number = mysqli_real_escape_string($con, $_POST['kin_number']);
        $kin_email = mysqli_real_escape_string($con, $_POST['kin_email']);
        $biography = mysqli_real_escape_string($con, $_POST['biography']);
        $kin_relationship = mysqli_real_escape_string($con, $_POST['kin_relationship']);

        $query = "INSERT INTO ".$siteprefix."users (display_name, first_name, middle_name, last_name, profile_picture, mobile_number, email, password, gender, address, type, status, last_login, created_date, preference, bank_name, bank_accname, bank_number, loyalty, wallet, affliate, seller, facebook, twitter, instagram, linkedln, kin_name, kin_number, kin_email, biography, kin_relationship) VALUES ('$display_name', '$first_name', '$middle_name', '$last_name', '$profile_picture', '$mobile_number', '$email', '$password', '$gender', '$address', '$type', '$status', '$last_login', '$created_date', '$preference', '$bank_name', '$bank_accname', '$bank_number', '$loyalty', '$wallet', '$affliate', '0', '$facebook', '$twitter', '$instagram', '$linkedln', '$kin_name', '$kin_number', '$kin_email', '$biography', '$kin_relationship')";

        if (mysqli_query($con, $query)) {
            $user_id = mysqli_insert_id($con);
        } else {
            $statusAction = "Error!";
            $statusMessage = "There was an error registering the user: " . mysqli_error($con);
            showErrorModal($statusAction, $statusMessage);
            exit();
        }

        $emailSubject="Verify Your Email";
        $emailMessage="<p>Thank you for registering on our website. To complete your registration, 
        please click on the following link to verify your email address:<br>
        <a href='$siteurl/verifymail.php?verify_status=$user_id'>Verify My Email</a></p>";
        $adminmessage = "A new user has been registered($display_name - $account)";
        $link="users.php";
        $msgtype='New User';
        $message_status=1;
        $emailMessage_admin="<p>Hello Dear Admin,a new user has been successfully registered!</p>";
        $emailSubject_admin="New User Registeration";
        insertadminAlert($con, $adminmessage, $link, $date, $msgtype, $message_status); 
        //sendEmail($email, $name, $siteName, $siteMail, $emailMessage, $emailSubject);
        //sendEmail($siteMail, $adminName, $siteName, $siteMail, $emailMessage_admin, $emailSubject_admin);
        if($seller==1){
        //$statusMessage="Your account has been created successfully. You can now proceed to sign your the contract.";
        echo header("location:contract.php?user_login=$user_id&name=$first_name $middle_name $last_name&address=$address&display_name=$display_name&email=$email&phone=$mobile_number");
        }else{
        echo header("location:signin.php?user_login=$user_id");
        }	



}}



// Affiliate Registration
if (isset($_POST['register-affiliate'])) {
    // Sanitize and validate input fields
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($con, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $website = mysqli_real_escape_string($con, $_POST['website']);
    $referral_source = mysqli_real_escape_string($con, $_POST['referral_source']);
    $agree_terms = isset($_POST['agree_terms']) ? 1 : 0;
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $retypePassword = mysqli_real_escape_string($con, $_POST['retypePassword']);
    $date = date('Y-m-d H:i:s');
    $status = 'active';
    $type = 'affiliate';
    $affiliate = 'AFF-' . strtoupper(substr(bin2hex(random_bytes(6)), 0, 12));
 // Generate unique affiliate ID

    // Validate email uniqueness
    $checkEmail = mysqli_query($con, "SELECT * FROM " . $siteprefix . "users WHERE email='$email' AND type='$type'");
    if (mysqli_num_rows($checkEmail) >= 1) {
        $statusAction = "Ooops!";
        $statusMessage = "This email has already been registered. Please try registering with another email.";
        showErrorModal($statusAction, $statusMessage);
        exit();
    }

    // Validate password length
    if (strlen($password) < 6) {
        $statusAction = "Try Again";
        $statusMessage = "Password must have 6 or more characters.";
        showErrorModal($statusAction, $statusMessage);
        exit();
    }

    // Validate password match
    if ($password !== $retypePassword) {
        $statusAction = "Ooops!";
        $statusMessage = "Passwords do not match!";
        showErrorModal($statusAction, $statusMessage);
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Handle file upload for ID
    $id_upload = '';
    if (!empty($_FILES['id_upload']['name'])) {
        $uploadDir = 'uploads/';
        $fileName = basename($_FILES['id_upload']['name']);
        $id_upload = $uploadDir . $fileName;

        // Validate file type and size
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($_FILES['id_upload']['type'], $allowed_types)) {
            $statusAction = "Invalid File!";
            $statusMessage = "Only JPG, PNG, and PDF files are allowed.";
            showErrorModal($statusAction, $statusMessage);
           
        }
        if ($_FILES['id_upload']['size'] > 2000000) { // Limit to 2MB
            $statusAction = "File Too Large!";
            $statusMessage = "File size exceeds the limit of 2MB.";
            showErrorModal($statusAction, $statusMessage);
         
        }

        // Move uploaded file to the uploads directory
        if (!move_uploaded_file($_FILES['id_upload']['tmp_name'], $id_upload)) {
            $statusAction = "Upload Failed!";
            $statusMessage = "Failed to upload the file. Please try again.";
            showErrorModal($statusAction, $statusMessage);
           
        }
    }

    // Insert affiliate details into the database
    $query = "INSERT INTO " . $siteprefix . "users 
              (display_name, first_name, middle_name, last_name, profile_picture, mobile_number, email, password, gender, address, type, status, last_login, created_date, preference, bank_name, bank_accname, bank_number, loyalty, wallet, affliate, seller, facebook, twitter, instagram, linkedln, kin_name, kin_number, kin_email, biography, kin_relationship) 
              VALUES 
              ('$first_name', '$first_name', '$middle_name', '$last_name', '$id_upload', '$phone', '$email', '$hashedPassword', '', '$address', '$type', '$status', '$date', '$date', '', '', '', '', '0', '0', '$affiliate', '0', '', '', '', '', '', '', '', '', '')";

    if (mysqli_query($con, $query)) {
        $user_id = mysqli_insert_id($con);
/*
        // Send confirmation email to the affiliate
        $emailSubject = "Affiliate Registration Successful";
        $emailMessage = "<p>Dear $first_name $last_name,</p>
                         <p>Thank you for registering as an affiliate. Your application has been received and is under review.</p>
                         <p>We will contact you shortly with further details.</p>";
        sendEmail($email, "$first_name $last_name", $siteName, $siteMail, $emailMessage, $emailSubject);

        // Notify admin about the new affiliate registration
        $adminMessage = "A new affiliate has registered: $first_name $last_name ($email)";
        $adminSubject = "New Affiliate Registration";
        sendEmail($siteMail, "Admin", $siteName, $siteMail, $adminMessage, $adminSubject);
*/
        // Show success modal and redirect
     // Show success modal and redirect
$statusAction = "Success!";
$message = "Affiliate registration successful! A confirmation email has been sent to $email.";
showSuccessModal($statusAction, $message); // Correctly pass the variable
header("refresh:1; url=affiliate/");

    } else {
        $statusAction = "Error!";
        $statusMessage = "There was an error registering the affiliate: " . mysqli_error($con);
        showErrorModal($statusAction, $statusMessage);
        exit();
    }
}

if(isset($_POST['update-profile'])){
    $fullName = htmlspecialchars($_POST['fullName']);
    $email = htmlspecialchars($_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $retypePassword = !empty($_POST['retypePassword']) ? $_POST['retypePassword'] : null;
    $oldPassword = htmlspecialchars($_POST['oldpassword']);
    $options = htmlspecialchars($_POST['options']);
    $profilePicture = $_FILES['profilePicture']['name'];

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

    $uploadDir = 'uploads/';
    $fileKey='profilePicture';
    global $fileName;

    // Update profile picture if a new one is uploaded
    if (!empty($profilePicture)) {
        $profilePicture = handleFileUpload($fileKey, $uploadDir, $fileName);
    } else {
        $profilePicture = $profile_picture; // Use the current profile picture if no new one is uploaded
    }

    // Update user information in the database
    $query = "UPDATE ".$siteprefix."users SET name = ?, email = ?, preference = ?, profile_picture = ?";
    $params = [$fullName, $email, $options, $profilePicture];

    if ($password) {
        $query .= ", password = ?";
        $params[] = $password;
    }

    $query .= " WHERE s = ?";
    $params[] = $user_id;

    $stmt = $con->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    if ($stmt->execute()) {
        $message= "Profile updated successfully.";
    } else {
        $message= "Error updating profile.";
    }
    showToast($message); 
    echo "<meta http-equiv='refresh' content='2'>";
}





//login user
if (isset( $_POST['signin'])){
    $code= $_POST['name'];
    $password = $_POST['password'];
          
    $sql = "SELECT * from ".$siteprefix."users where email='$code' OR display_name='$code'";
    $sql2 = mysqli_query($con,$sql);
    if (mysqli_affected_rows($con) == 0){
    $statusAction="Try Again!";
    $statusMessage='Invalid Email address or Display Name!';
    showErrorModal($statusAction, $statusMessage);  
    }
                
    else {  
    while($row = mysqli_fetch_array($sql2)){
    $id = $row["s"]; 
    $hashedPassword = $row['password'];
    $status = $row['status'];
    $type = $row['type'];
    }
     
    if($type!='user'){
        $statusAction="Ooops!";
        $statusMessage='Invalid Credentials!';
        showErrorModal($statusAction, $statusMessage);  
    }

     else if (!checkPassword($password, $hashedPassword)) {
     $statusAction="Ooops!";
     $statusMessage='Incorrect Password for this account! <a href="forgetpassword.php" style="color:red;">Forgot password? Recover here</a>';
     showErrorModal($statusAction, $statusMessage);  
    }
     
    
    else if($status == "inactive"){
        $statusAction="Ooops!";
        $statusMessage=' Email Address have not been verified. we have sent you a mail which contains verification link. kindly check your email and verify your email address.';
        showErrorModal($statusAction, $statusMessage);  
    }
    
    else if($status == "active"){
    $date=date('Y-m-d H:i:s');
    $insert = mysqli_query($con,"UPDATE ".$siteprefix."users SET last_login='$date' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
                  
    session_start(); 
    $_SESSION['id']=$id;
    setcookie("userID", $id, time() + (10 * 365 * 24 * 60 * 60));
    $message = "Logged In Successfully";
                 
                 
    showToast($message);          
    //redirection
    if (isset($_SESSION['previous_page'])) {
      $previousPage = $_SESSION['previous_page'];
      header("location: $previousPage");
    } else {
      header("location: dashboard.php");
    }} 
    }}



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
    header("refresh:2; url=$page");
}


//add dispute
if (isset($_POST['create_dispute'])){
    $category = $_POST['category'];
    $recipient_id = $_POST['seller'];
    $contract_reference = $_POST['order_id'];
    $issue = mysqli_real_escape_string($con, $_POST['issue']);
    $ticket_number = "TKT" . time(); // Unique Ticket ID
    $page="ticket.php?ticket_number=$ticket_number";
    $date = date('Y-m-d H:i:s');

    //

    // Insert dispute into DB
    $sql = "INSERT INTO ".$siteprefix."disputes (user_id, recipient_id, ticket_number, category, order_reference, issue) 
            VALUES ('$user_id', '$recipient_id','$ticket_number', '$category', '$contract_reference', '$issue')";
    
    $fileKey = 'evidence';
    $uploadDir = 'uploads/';
    $reportImages = handleMultipleFileUpload($fileKey, $uploadDir);
    $uploadedFiles = [];

     
    $sql2 = "INSERT INTO ".$siteprefix."dispute_messages (dispute_id, sender_id, message, file) 
    VALUES ('$ticket_number', '$user_id', '$issue', '')";
    mysqli_query($con, $sql2);
    
    
    if (mysqli_query($con, $sql)) {
        $dispute_id = mysqli_insert_id($con); // Get the ID of the just inserted dispute
        foreach ($reportImages as $image) {
            $sql = "INSERT INTO ".$siteprefix."evidence (dispute_id, file_path, uploaded_at) VALUES ('$dispute_id', '$image', NOW())";
            if (mysqli_query($con, $sql)) {
                $uploadedFiles[] = $image;
            } else {
                $message .= "Error: " . mysqli_error($con);
            }
        }

        $emailSubject="New Dispute ($ticket_number)";
        $emailMessage="<p>Thank you for submitting a dispute. Your ticket number is: $ticket_number</p>";
        $adminmessage = "A new dispute has been submitted ($ticket_number)";
        $link="ticket.php?ticket_number=$ticket_number";
        $msgtype='New Dispute';
        $message_status=1;
        $emailMessage_admin="<p>Hello Dear Admin,a new dispute has been submitted!</p>";
        $emailSubject_admin="New Dispute";
        insertadminAlert($con, $adminmessage, $link, $date, $msgtype, $message_status);
        //sendEmail($email, $display_name, $siteName, $siteMail, $emailMessage, $emailSubject);
        //sendEmail($siteMail, $adminName, $siteName, $siteMail, $emailMessage_admin, $emailSubject_admin);
    
            if($recipient_id){
            $rDetails = getUserDetails($con, $siteprefix, $recipient_id);
            $r_email = $rDetails['email'];
            $r_name = $rDetails['display_name'];
            $r_emailSubject="New Dispute ($ticket_number)";
            $r_emailMessage="<p>A new dispute has been submitted with you as the recipient. Login to your dashboard to check</p>";
           //sendEmail($r_email, $r_name, $siteName, $siteMail, $r_emailMessage, $r_emailSubject);
           $message = "A new dispute has been submitted with you as the recipient: " . $ticket_number;
           $status=0;
           insertAlert($con, $recipient_id, $message, $date, $status);
        }

       $message= "Dispute submitted successfully. Ticket ID: " . $ticket_number;
       showSuccessModal('Success', $message);
       header("refresh:2; url=$page");
    } else {
       $message="Error: " . mysqli_error($con);
       showErrorModal('Oops', $message);
    }}

  //add dispute message
  if (isset($_POST['send_dispute_message'])) {
        $dispute_id = $_POST['dispute_id'];
        $sender_id = $user_id; // Assume logged-in user
        $message = mysqli_real_escape_string($con, $_POST['message']);
        $page = "ticket.php?ticket_number=$dispute_id";
        $new_status = "awaiting-response";

        $fileKey = 'attachment';
        $uploadDir = 'uploads/';
        $reportImages = handleMultipleFileUpload($fileKey, $uploadDir);
        $uploadedFiles =  implode(', ', $reportImages);
        if (empty($_FILES[$fileKey]['name'][0])) {
            $uploadedFiles = '';
        }

        
        $sql = "INSERT INTO ".$siteprefix."dispute_messages (dispute_id, sender_id, message, file) 
            VALUES ('$dispute_id', '$sender_id', '$message', '$uploadedFiles')";
            
       
        if (mysqli_query($con, $sql)) {

        // Then call the function where needed:
        notifyDisputeRecipient($con, $siteprefix, $dispute_id);
        $date = date('Y-m-d H:i:s');
        $status = 0;
        $message = "A new message has been sent on dispute $dispute_id";
        $link = "ticket.php?ticket_number=$dispute_id";
        $msgtype = "Dispute Update";
        insertadminAlert($con, $message, $link, $date, $msgtype, $status);
        updateDisputeStatus($con, $siteprefix, $dispute_id, $new_status);
        showToast("Message sent successfully!");

        } else {
        $message = "Error: " . mysqli_error($con);
        showErrorModal('Oops', $message);
        }
    }

?>