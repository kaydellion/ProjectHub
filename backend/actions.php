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
    $title =  mysqli_real_escape_string($con,$_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $preview = mysqli_real_escape_string($con, $_POST['preview']);
    $tableContent = mysqli_real_escape_string($con, $_POST['table_content']);
    $category = $_POST['category'];
    $subcategory = isset($_POST['subcategory']) ? $_POST['subcategory'] : null;
    $pricing = $_POST['pricing'];
    $price = !empty($_POST['price']) ? $_POST['price'] : '0';
    $tags = mysqli_real_escape_string($con,$_POST['tags']);
    $loyalty = isset($_POST['loyalty']) ? 1 : 0;
    $documentTypes = isset($_POST['documentSelect']) ? $_POST['documentSelect'] : [];
    $methodology =  mysqli_real_escape_string($con, $_POST['methodology']);
    $year_of_study = implode(',',$_POST['year_of_study']); 
    $resource_type = implode(',',$_POST['resource_type']);
    $education_level = $_POST['education_level'];
    $chapter = $_POST['chapter'];
    $answer = $_POST['answer'];
  
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
    $sql = "INSERT INTO ".$siteprefix."reports (s, id, title, description, preview, table_content,methodology,chapter,year_of_study,resource_type,education_level,answer, category, subcategory, pricing, price, tags, loyalty, user, created_date, updated_date, status) VALUES (NULL, '$reportId', '$title', '$description','$preview','$tableContent','$methodology','$chapter','$year_of_study','$resource_type','$education_level','$answer','$category', '$subcategory', '$pricing', '$price', '$tags', '$loyalty', '$user_id', current_timestamp(), current_timestamp(), '$status')";
    if (mysqli_query($con, $sql)) {
        $message .= "Report added successfully!";
    } else {
        $message .= "Error: " . mysqli_error($con);
    }

    showSuccessModal('Processed',$message);
    header("refresh:2; url=models.php");
  }



  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update-report'])) {
    $reportId = $_POST['id'];
    $title =  mysqli_real_escape_string($con,$_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $preview = mysqli_real_escape_string($con, $_POST['preview']);
    $tableContent = mysqli_real_escape_string($con, $_POST['table_content']);
    $category = $_POST['category'];
    $subcategory = isset($_POST['subcategory']) ? $_POST['subcategory'] : null;
    $pricing = $_POST['pricing'];
    $price = !empty($_POST['price']) ? $_POST['price'] : '0';
    $tags = mysqli_real_escape_string($con,$_POST['tags']);
    $loyalty = isset($_POST['loyalty']) ? 1 : 0;
    $documentTypes = isset($_POST['documentSelect']) ? $_POST['documentSelect'] : [];
    $status = "pending";
    $methodology =  mysqli_real_escape_string($con, $_POST['methodology']);
    $year_of_study = implode(',',$_POST['year_of_study']); 
    $resource_type = implode(',',$_POST['resource_type']);
    $education_level = $_POST['education_level'];
    $chapter = $_POST['chapter'];
    $answer = $_POST['answer'];


    // Upload images
    $uploadDir = '../../uploads/';
    $fileuploadDir = '../../documents/';
    $fileKey='images';
    global $fileName;
    $message="";

    $reportImages = handleMultipleFileUpload($fileKey, $uploadDir);
    if (empty($_FILES[$fileKey]['name'][0])) {
       // Array of default images
        //$defaultImages = ['default1.jpg', 'default2.jpg', 'default3.jpg', 'default4.jpg', 'default5.jpg'];
        // Pick a random default image
        //$randomImage = $defaultImages[array_rand($defaultImages)];
        //$reportImages = [$randomImage];
    }else{
    
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
    }}

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


  
    // Insert data into the database for update
    $sql = "UPDATE ".$siteprefix."reports SET title='$title', answer='$answer', description='$description', preview='$preview', table_content='$tableContent', methodology='$methodology', chapter='$chapter', year_of_study='$year_of_study', resource_type='$resource_type', education_level='$education_level', category='$category', subcategory='$subcategory', pricing='$pricing', price='$price', tags='$tags', loyalty='$loyalty', user='$user_id', updated_date=current_timestamp(), status='$status' WHERE id = '$reportId'";
    if (mysqli_query($con, $sql)) {
        $message .= "Report updated successfully!";
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
    $profilePicture = $_FILES['profile_picture']['name'];
    

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
        $profile_picture = $profilePicture;
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

        $query = "INSERT INTO ".$siteprefix."users (display_name, first_name, middle_name, last_name, profile_picture, mobile_number, email, password, gender, address, type, status, last_login, created_date, preference, bank_name, bank_accname, bank_number, loyalty, wallet, affliate, seller, facebook, twitter, instagram, linkedln, kin_name, kin_number, kin_email, biography, kin_relationship)
         VALUES ('$display_name', '$first_name', '$middle_name', '$last_name', '$profile_picture', '$mobile_number', '$email', '$password', '$gender', '$address', '$type', '$status', '$last_login', '$created_date', '$preference', '$bank_name', '$bank_accname', '$bank_number', '$loyalty', '$wallet', '$affliate', '0', '$facebook', '$twitter', '$instagram', '$linkedln', '$kin_name', '$kin_number', '$kin_email', '$biography', '$kin_relationship')";

        if (mysqli_query($con, $query)) {
            $user_id = mysqli_insert_id($con);
        } else {
            $statusAction = "Error!";
            $statusMessage = "There was an error registering the user: " . mysqli_error($con);
            showErrorModal($statusAction, $statusMessage);
            exit();
        }

        $emailSubject="Confirm Your Email – Project Report Hub";
        $emailMessage = "
    <p>Dear $display_name,</p>

    <p>Thank you for signing up on <strong>ProjectReportHub.ng</strong>! To complete your registration
    and start exploring our platform, please verify your email address by clicking the link below:</p>

    <p><a href='$siteurl/verifymail.php?verify_status=$user_id'>Click here to verify your email</a></p>

    <p>Once verified, you can log in and start accessing premium reports, upload your content,
    or manage your dashboard.</p>

    <p>Thanks for joining us!</p>

    <p>Warm Regards,<br>
    Ikechukwu Anaekwe<br>
    Project Report Hub<br>
    <em>Customer Support Team</em></p>
";

        $adminmessage = "A new user has been registered($display_name)";
        $link="users.php";
        $msgtype='New User';
        $message_status=1;
        $emailMessage_admin="<p>A new user has been successfully registered!</p>";
        $emailSubject_admin="New User Registeration";
        insertadminAlert($con, $adminmessage, $link, $date, $msgtype, $message_status); 
        sendEmail($email, $display_name, $siteName, $siteMail, $emailMessage, $emailSubject);
        sendEmail($siteMail, $adminName, $siteName, $siteMail, $emailMessage_admin, $emailSubject_admin);
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
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
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
/*
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
*/
    // Insert affiliate details into the database
    $query = "INSERT INTO " . $siteprefix . "users 
    (display_name, first_name, middle_name, last_name, profile_picture, mobile_number, email, password, gender, address, type, status, last_login, created_date, preference, bank_name, bank_accname, bank_number, loyalty, wallet, affliate, seller, facebook, twitter, instagram, linkedln, kin_name, kin_number, kin_email, biography, kin_relationship) 
    VALUES ('$first_name', '$first_name', '$middle_name', '$last_name', '', '$phone', '$email', '$hashedPassword', '$gender', '$address', '$type', '$status', '$date', '$date', '', '', '', '0', '0', '0', '$affiliate', '0', '', '', '', '', '', '', '', '', '')";

if (mysqli_query($con, $query)) {
$user_id = mysqli_insert_id($con);
$statusAction = "Success!";
$message = "Affiliate registration successful! A confirmation email has been sent to $email.";
showSuccessModal($statusAction, $message); // Correctly pass the variable
header("refresh:1; url=https://affiliate.projectreporthub.ng/"); 
    } else {
        $statusAction = "Error!";
        $statusMessage = "There was an error registering the affiliate: " . mysqli_error($con);
        showErrorModal($statusAction, $statusMessage);
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
    $fileKey='profile_picture';
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

//updateproof
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_proof'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $date = date('Y-m-d H:i:s');

    // Handle file upload
    if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['proof_of_payment']['tmp_name'];
        $file_name = $_FILES['proof_of_payment']['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION); // File extension (optional, no validation here)
        $new_file_name = uniqid() . '.' . $file_ext;
        $upload_dir = 'uploads/'; // Subdirectory for payment proofs
        $upload_path = $upload_dir . $new_file_name;

        // Ensure the directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Update the proof of payment in the database
            $update_query = "UPDATE " . $siteprefix . "manual_payments 
                             SET proof = '$new_file_name', status = 'pending', date_created = '$date' 
                             WHERE order_id = '$order_id' AND user_id = '$user_id'";
            if (mysqli_query($con, $update_query)) {
                // Fetch admin email
                $admin_email = $siteMail; // Replace with your admin email variable
                $admin_name = $sitename; // Replace with your admin name variable

                // Fetch user details
                $user_query = "SELECT display_name, email FROM " . $siteprefix . "users WHERE s = '$user_id'";
                $user_result = mysqli_query($con, $user_query);

                if ($user_result && mysqli_num_rows($user_result) > 0) {
                    $user = mysqli_fetch_assoc($user_result);
                    $user_name = $user['display_name'];
                    $user_email = $user['email'];
                } else {
                    $user_name = "Unknown User";
                    $user_email = "Unknown Email";
                }

                // Email content for admin
                $emailSubject = "Payment Resent for Order #$order_id";
                $emailMessage = "
                    <p>A payment has been resent:</p>
                    <p><strong>Order ID:</strong> $order_id</p>
                    <p><strong>User:</strong> $user_name ($user_email)</p>
                    <p><strong>Date:</strong> $date</p>
                    <p>Please log in to the admin panel to verify the payment.</p>
                ";

                // Send email to admin
                sendEmail($admin_email, $admin_name, $siteName, $siteMail, $emailMessage, $emailSubject);

                // Success message for the user
                $statusAction = "Success!";
                $statusMessage = "Proof of payment updated successfully. The admin has been notified.";
                showSuccessModal($statusAction, $statusMessage);
                header("refresh:2;");
                
            } else {
                // Database update error
                $statusAction = "Error!";
                $statusMessage = "An error occurred while updating the proof of payment.";
                showErrorModal($statusAction, $statusMessage);
                header("refresh:2;");
                
            }
        } else {
            // File upload error
            $statusAction = "Error!";
            $statusMessage = "Failed to upload the proof of payment. Please try again.";
            showErrorModal($statusAction, $statusMessage);
            header("refresh:2;");
            
        }
    } else {
        // No file uploaded
        $statusAction = "Error!";
        $statusMessage = "No proof of payment uploaded. Please try again.";
        showErrorModal($statusAction, $statusMessage);
        header("refresh:2;");
        
    }
}

//manual payment
if (isset($_POST['submit_manual_payment'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
    $date = date('Y-m-d H:i:s');

    // Handle file upload
    if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['proof_of_payment']['tmp_name'];
        $file_name = $_FILES['proof_of_payment']['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid() . '.' . $file_ext;
            $upload_dir = 'uploads/'; // Subdirectory for payment proofs
            $upload_path = $upload_dir . $new_file_name;

            // Ensure the directory exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Insert payment proof into the database
                $query = "INSERT INTO " . $siteprefix . "manual_payments (order_id, user_id, amount, proof, status, date_created, rejection_reason) 
                          VALUES ('$order_id', '$user_id', '$amount', '$new_file_name', 'pending', '$date', '')";
                if (mysqli_query($con, $query)) {
                    // Update order status to pending
                    $update_order_query = "UPDATE " . $siteprefix . "orders SET status = 'pending' WHERE order_id = '$order_id'";
                    mysqli_query($con, $update_order_query);

                    // Fetch admin email
                    $admin_email = $siteMail; // Replace with your admin email variable
                    $admin_name = "Admin"; // Replace with your admin name variable

                    // Fetch user details
                    $user_query = "SELECT display_name, email FROM " . $siteprefix . "users WHERE s = '$user_id'";
                    $user_result = mysqli_query($con, $user_query);

                    if ($user_result && mysqli_num_rows($user_result) > 0) {
                        $user = mysqli_fetch_assoc($user_result);
                        $user_name = $user['display_name'];
                        $user_email = $user['email'];
                    } else {
                        $user_name = "Unknown User";
                        $user_email = "Unknown Email";
                    }

                    // Email content for admin
                    $emailSubject = "New Manual Payment Submitted";
                    $emailMessage = "
                        <p>A new manual payment has been submitted:</p>
                        <p><strong>Order ID:</strong> $order_id</p>
                        <p><strong>User:</strong> $user_name ($user_email)</p>
                        <p><strong>Amount:</strong> $sitecurrencyCode" . number_format($amount, 2) . "</p>
                        <p><strong>Date:</strong> $date</p>
                        <p>Please log in to the admin panel to verify the payment.</p>
                    ";

                    // Send email to admin
                    sendEmail($admin_email, $admin_name, $siteName, $siteMail, $emailMessage, $emailSubject);

                    // Success message for the user
                    $statusAction = "Success!";
                    $statusMessage = "Your payment proof has been submitted successfully. Your order is now pending verification.";
                    showSuccessModal($statusAction, $statusMessage);
                    header("refresh:2; url=checkout.php");
                
                } else {
                    $statusAction = "Error!";
                    $statusMessage = "An error occurred while submitting your payment proof. Please try again.";
                    showErrorModal($statusAction, $statusMessage);
                    header("refresh:2; url=checkout.php");
                    
                }
            } else {
                $statusAction = "Error!";
                $statusMessage = "Failed to upload the proof of payment. Please try again.";
                showErrorModal($statusAction, $statusMessage);
                header("refresh:2; url=checkout.php");
            
            }
        } else {
            $statusAction = "Error!";
            $statusMessage = "Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.";
            showErrorModal($statusAction, $statusMessage);
            header("refresh:2; url=checkout.php");
          
        }
    } else {
        $statusAction = "Error!";
        $statusMessage = "No proof of payment uploaded. Please try again.";
        showErrorModal($statusAction, $statusMessage);
        header("refresh:2; url=checkout.php");
       
    }
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

        $emailSubject="Dispute Submitted Successfully – Ticket No:$ticket_number";

        $emailMessage = "
        <p>Dear $display_name,</p>
        
        <p>Thank you for submitting your dispute. We’ve received your request and assigned it the following ticket number: <strong>$ticket_number</strong>.</p>
        
        <p>Our support team will review the details and get back to you as soon as possible.</p>
        
        <p>Visit <a href='https://www.projectreporthub.ng'>ProjectReportHub.ng</a> to track your dispute status or explore more resources.</p>
        
        <p>Warm regards,</p>
        <p>The Project Report Hub Team</p>
        <p><a href='mailto:hello@projectreporthub.ng'>hello@projectreporthub.ng</a> | <a href='https://www.projectreporthub.ng'>www.projectreporthub.ng</a></p>
        ";
        $adminmessage = "A new dispute has been submitted ($ticket_number)";
        $link="ticket.php?ticket_number=$ticket_number";
        $msgtype='New Dispute';
        $message_status=1;
        $emailMessage_admin="<p>Hello Dear Admin,a new dispute has been submitted!</p>";
        $emailSubject_admin="New Dispute";
        insertadminAlert($con, $adminmessage, $link, $date, $msgtype, $message_status);
        sendEmail($email, $display_name, $siteName, $siteMail, $emailMessage, $emailSubject);
        sendEmail($siteMail, $adminName, $siteName, $siteMail, $emailMessage_admin, $emailSubject_admin);
    
            if($recipient_id){
            $rDetails = getUserDetails($con, $siteprefix, $recipient_id);
            $r_email = $rDetails['email'];
            $r_name = $rDetails['display_name'];
            $r_emailSubject="New Dispute ($ticket_number)";
            $r_emailMessage="<p>A new dispute has been submitted with you as the recipient. Login to your dashboard to check</p>";
           sendEmail($r_email, $r_name, $siteName, $siteMail, $r_emailMessage, $r_emailSubject);
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


   // Handle Report Product Submission
if (isset($_POST['submit_report'])) {
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
    $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $reason = mysqli_real_escape_string($con, $_POST['reason']);
    $custom_reason = isset($_POST['custom_reason']) ? mysqli_real_escape_string($con, trim($_POST['custom_reason'])) : null;

    // Use custom reason if "Other" is selected
    if ($reason === "Other" && !empty($custom_reason)) {
        $reason = $custom_reason;
    }

    $date = date('Y-m-d H:i:s');

    // Fetch the product title
    $product_query = "SELECT title FROM " . $siteprefix . "reports WHERE id = '$product_id'";
    $product_result = mysqli_query($con, $product_query);

    if ($product_result && mysqli_num_rows($product_result) > 0) {
        $product = mysqli_fetch_assoc($product_result);
        $product_title = $product['title'];
    } else {
        $statusAction = "Error!";
        $statusMessage = "Product not found.";
        showErrorModal($statusAction, $statusMessage);
        header("refresh:2; url=product.php?id=$product_id");
        exit();
    }

    // Insert the report into the database
    $insert_query = "INSERT INTO " . $siteprefix . "product_reports (product_id, user_id, reason, report_date) 
                     VALUES ('$product_id', '$user_id', '$reason', '$date')";

    if (mysqli_query($con, $insert_query)) {
        // Fetch admin email
        $admin_email = $siteMail; // Replace with your admin email variable
       
        // Fetch user details
        $user_query = "SELECT display_name, email FROM " . $siteprefix . "users WHERE s = '$user_id'";
        $user_result = mysqli_query($con, $user_query);

        if ($user_result && mysqli_num_rows($user_result) > 0) {
            $user = mysqli_fetch_assoc($user_result);
            $user_name = $user['display_name'];
            $user_email = $user['email'];
        } else {
            $user_name = "Unknown User";
            $user_email = "Unknown Email";
        }

        // Email content
        $emailSubject = "New Product Report Submitted";
        $emailMessage = "
            <p>A new product report has been submitted:</p>
            <p><strong>Product Title:</strong> $product_title</p>
            <p><strong>User:</strong> $user_name ($user_email)</p>
            <p><strong>Reason:</strong> $reason</p>
            <p><strong>Date:</strong> $date</p>
        ";

        // Send email to admin
      //  sendEmail($siteMail, $adminName, $siteName, $siteMail, $emailMessage, $emailSubject);

        // Success message
        $statusAction = "Success!";
        $statusMessage = "Your report for <strong>$product_title</strong> has been submitted successfully.";
        showSuccessModal($statusAction, $statusMessage);
        header("refresh:2; url=product.php?id=$product_id");
      
    } else {
        // Error message
        $statusAction = "Error!";
        $statusMessage = "An error occurred while submitting your report. Please try again.";
        showErrorModal($statusAction, $statusMessage);
        header("refresh:2; url=product.php?id=$product_id");
       
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



$emailMessage="<p>We are writing to confirm that we have successfully received your withdrawal request in the amount of $sitecurrency$amount.<br>
Please note that your request is currently being processed and is expected to be completed within the next twenty-four (24) hours. Once the transaction has been finalized, you will receive a confirmation notification.<br>
Should you have any questions or require further assistance, please do not hesitate to contact our support team.<br>
Thank you for choosing our services.</p>";
$footer="<p>Warm regards,<br>
Ikechukwu Anaekwe<br>
Project Report Hub (Customer Support Team).</p>";

insertWithdraw($con, $user_id, $amount,$bank, $bankname, $bankno, $date, $status);
$emailSubject="Withdrawal Request - Recieved";
$emailMessage_admin="<p>A new withdrawal request has been recieved for ₦$amount. Please login into your dashboard to process it</p>";
$adminmessage = "New Withdrawal Request - &#8358;$amount";
$link="withdrawals.php";
$msgtype='New Withdrawal';
$message_status=1;
insertadminAlert($con, $adminmessage, $link, $date, $msgtype, $message_status); 
sendEmail($email, $name, $siteName, $siteMail, $emailMessage, $emailSubject);
sendEmail($siteMail, $adminName, $siteName, $siteMail, $emailMessage_admin, $emailSubject);
    
   
$statusAction="Successful";
$statusMessage="Withdrawal Request Sent Sucessfully!";
showSuccessModal($statusAction,$statusMessage);
header("Refresh: 4; url=wallet.php");
}


//add review
if (isset($_POST['submit-review'])) {
    $user_id = $_POST['user_id'];
    $report_id = $_POST['report_id'];
    $rating = $_POST['rating'];
    $review = trim($_POST['review']);

    // Check if user already has a review
    $check_query = "SELECT * FROM ".$siteprefix."reviews WHERE user = ? AND report_id = ?";
    $stmt = $con->prepare($check_query);
    $stmt->bind_param("si", $user_id, $report_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing review
        $update_query = "UPDATE ".$siteprefix."reviews SET rating = ?, review = ?, date = NOW() WHERE user = ? AND report_id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("issi", $rating, $review, $user_id, $report_id);
    } else {
        // Insert new review
        $insert_query = "INSERT INTO ".$siteprefix."reviews (report_id, user, rating, review, date) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $con->prepare($insert_query);
        $stmt->bind_param("isis", $report_id, $user_id, $rating, $review);
    }

    if ($stmt->execute()) {
        $statusAction="Successful";
        $statusMessage="Your review has been submitted successfully!";
        showSuccessModal($statusAction,$statusMessage);
    } else {
        $statusAction="Error";
        $statusMessage="An error occurred while submitting your review. Please try again.";
        showSuccessModal($statusAction,$statusMessage);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    // Sanitize input
    $user_id = $_POST['user_id'];
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($con, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
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

    $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
    $retypePassword = !empty($_POST['retypePassword']) ? trim($_POST['retypePassword']) : null;
    $oldPassword = !empty($_POST['oldpassword']) ? trim($_POST['oldpassword']) : null;
    $hashedPassword = null;

    if (!empty($password) || !empty($retypePassword) || !empty($oldPassword)) {
        if (empty($password) || empty($retypePassword) || empty($oldPassword)) {
            echo "<script>
            alert('All password fields (Password, Retype Password, and Old Password) must be filled out.');
            window.history.back(); // Go back to previous form state
        </script>";
        exit;
    }

      

        if ($password !== $retypePassword) {
            echo "<script>
            alert('New password and retype password do not match.');
            window.history.back();
        </script>";
        exit;
        }

        $stmt = $con->prepare("SELECT password FROM {$siteprefix}users WHERE s = ?");
        if (!$stmt) {
            echo "<script>
            alert('Error preparing password check statement.');
            window.history.back();
        </script>";
        exit;
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user || !password_verify($oldPassword, $user['password'])) {
            echo "<script>
            alert('Old password is incorrect.');
            window.history.back();
        </script>";
        exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    // Handle profile picture upload
    $uploadDir = 'uploads/';
    $fileKey = 'profile_picture';
    $profilePicture = $_FILES[$fileKey]['name'] ?? '';

    if (!empty($profilePicture)) {
        global $fileName;
        $profilePicture = handleFileUpload($fileKey, $uploadDir, $fileName);
    } else {
        $query = mysqli_query($con, "SELECT profile_picture FROM {$siteprefix}users WHERE s = '$user_id'");
        $row = mysqli_fetch_assoc($query);
        $profilePicture = $row['profile_picture'];
    }

    $passwordSql = $hashedPassword ? "password = '" . mysqli_real_escape_string($con, $hashedPassword) . "'," : "";

    $update_query = "
        UPDATE {$siteprefix}users 
        SET 
            first_name = '$first_name',
            middle_name = '$middle_name',
            last_name = '$last_name',
            email = '$email',
            $passwordSql
            mobile_number = '$mobile_number',
            address = '$address',
            gender = '$gender',
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

    // Execute query
    if (mysqli_query($con, $update_query)) {
        showSuccessModal("Success!", "Profile updated successfully!");
        header("refresh:1; url=settings.php");
        exit;
    } else {
        showErrorModal("Error!", "Failed to update profile: " . mysqli_error($con));
        exit;
    }
}

//contact us
if(isset($_POST['contact'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $emailMessage = "From: " . $name . "\nEmail: " . $email . "\nMessage:\n" . $message;
    sendEmail($sitemail, $sitename, $sitename, $sitemail, $emailMessage, $subject);
    
        $message='Message sent successfully. We will get back to you soon.';
        showSuccessModal('Success', $message);
        //$message='Failed to send message';
        //showErrorModal('Error', $message);
}
?>