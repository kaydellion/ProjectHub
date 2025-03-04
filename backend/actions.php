<?php

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



?>