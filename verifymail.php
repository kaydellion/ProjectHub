<?php include "header.php"; ?>



    <!--================login_part Area =================-->
    <section class="login_part mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 order-2 order-md-1">
                    <div class="login_part_text text-center">
                        <div class="login_part_text_iner">
                            <h2>New to Project Hub?</h2>
                            <p>Join Project Hub today to access a wide range of reports and e-commerce features. Stay updated with the latest advancements in science and technology.</p>
                            <a href="signup.php" class="btn_3">Create an Account</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                <?php 
                if(isset($_GET['verify_status'])){
                    $user_log = $_GET['verify_status'];
                    $sql = "SELECT * from ".$siteprefix."users where s='$user_log'";
                    $sql2 = mysqli_query($con, $sql);
                    if (mysqli_affected_rows($con) == 0){
                        $message = 'User does not exist!';
                        showErrorModal('Error', $message);
                    } else {
                        while($row = mysqli_fetch_array($sql2)) {
                            $id = $row["s"];   
                            $name = $row["display_name"];
                            $email = $row["email"];
                        }
                        $subject = "Welcome to Project Report Hub";
$emailMessage = "
    <p>Dear $name,</p>
    <p>Your email has been successfully verified — welcome aboard!</p>
    <p>You can now access your dashboard, explore premium academic resources, and start uploading or downloading reports.</p>
    <p><a href='https://www.projectreporthub.ng'>Visit ProjectReportHub.ng</a> to get started.</p>
    <p>We're excited to have you on the platform!</p>
    <br>
    <p>Warm regards,</p>
    <p>The Project Report Hub Team</p>
    <p><a href='mailto:hello@projectreporthub.ng'>hello@projectreporthub.ng</a> | 🌐 <a href='https://www.projectreporthub.ng'>www.projectreporthub.ng</a></p>
";
                        if(mysqli_query($con, "UPDATE ".$siteprefix."users SET status='active' where s='$user_log'")) {
                            if(sendEmail($email, $name, $siteName, $siteMail, $emailMessage, $subject)) {
                                $message = 'Email Verified Successfully!';
                                showSuccessModal('Success', $message);
                                header("refresh:2;url=signin.php?verify_login=$user_log");
                            } else {
                                $message = 'Verification successful'; //but failed to send email
                                showSuccessModal('Success', $message);
                                header("refresh:2;url=signin.php?verify_login=$user_log");
                            }
                        } else {
                            $message = 'Failed to verify';
                            showErrorModal('Error', $message);
                        }
                    }
                } else { header('Location: signin.php'); exit(); }
                ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================login_part end =================-->










<?php include "footer.php"; ?>