<?php include "header.php";

if($active_log=="1"){ header("location:index.php");}
if(isset($_POST['forget'])){
$email=$_POST['email'];

$check = "SELECT * FROM ".$siteprefix."users WHERE email= '$email' AND type='user'";
$query = mysqli_query($con, $check);
if (mysqli_affected_rows($con) == 0) {
$statusAction="Invalid User";
$statusMessage="User not found!";
showErrorModal($statusAction,$statusMessage);
} else {
    $sql= "SELECT * FROM ".$siteprefix."users WHERE email= '$email' AND type='user'";
    $sql2 = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($sql2)) {
        $user_name = $row['display_name'];
        $user_id = $row['s'];
        $user_email = $row['email'];}
        
$randomPassword = generateRandomHardPassword();
$emailMessage="<p>Your password has been reset successfully to <span style='color:F57C00;'>$randomPassword</span> <br>Please login with it to change your password to a desired format.</p>";
$emailSubject="Password Reset";
$statusAction="Successful";
$statusMessage="Password reset successfully. Please check your email!";
$randomPassword=hashPassword($randomPassword);
$submit = mysqli_query($con, "UPDATE " . $siteprefix . "users SET password ='$randomPassword' WHERE s = '$user_id'") or die('Could not connect: ' . mysqli_error($con));
sendEmail($user_email, $user_name, $siteName, $siteMail, $emailMessage, $emailSubject);
showSuccessModal($statusAction,$statusMessage);
}}

?>



    <!--================login_part Area =================-->
    <section class="login_part mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 order-2 order-md-1">
                    <div class="login_part_text text-center">
                        <div class="login_part_text_iner">
                            <h2>Forgot your password?</h2>
                            <p>Enter your email address below and we will send you a link to reset your password.</p>
                            <p> <a href="signin.php" class="btn_3">SIGN IN</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 p-3">
                    <div class="login_part_form">
                        <div class="login_part_form_iner">
                            <h4>Reset Password</h4>
                            <pp>Enter your email address below and we will send you a link to reset your password.</p>
                            <form class="row contact_form" method="post" >
                                <div class="col-md-12 mb-3 mt-3">
                                <input type="email" class="form-control" id="name" name="email" value="" placeholder="Enter email below" required>
                                </div>
                                    <button type="submit" value="submit" name="forget" class="btn_3">Send Password Reset Link</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
    </section>
    <!--================login_part end =================-->











<?php include "footer.php"; ?>