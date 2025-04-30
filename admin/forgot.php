
<?php

include "../backend/connect.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit-reset-link'])) {
    $email = mysqli_real_escape_string($con, $_POST['username']); // Sanitize email input

    // Check if the email exists in the admin table
    $query = "SELECT * FROM {$siteprefix}users WHERE email = '$email' AND type='admin'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expires in 1 hour

        // Store the token and expiry in the database
        $update_query = "UPDATE {$siteprefix}users SET reset_token = '$token', reset_token_expiry = '$expiry' WHERE email = '$email' ";
        if (mysqli_query($con, $update_query)) {
            // Prepare email content
            $reset_link = $siteurl . "admin/reset-password.php?token=" . $token;
            $emailSubject = "Password Reset Request";
            $emailMessage = "
                <p>Dear Admin,</p>
                <p>We received a request to reset your password. Please click the link below to reset your password:</p>
                <p><a href='$reset_link'>$reset_link</a></p>
                <p>If you did not request this, please ignore this email.</p>
                <p>Thank you,<br>Project Report Hub Team</p>
            ";

            // Send the email
            if (sendEmail($email, "Admin", $siteName, $siteMail, $emailMessage, $emailSubject)) {
                $message = "A password reset link has been sent to your email.";
                showSuccessModal('Processed', $message);
                header("refresh:2;");
            } else {
                $message = "Failed to send the reset email. Please try again.";
                showErrorModal('Oops', $message);
                header("refresh:2;");
            }
        } else {
            $message = "Failed to generate reset link. Please try again.";
            showErrorModal('Oops', $message);
            header("refresh:2;");
        }
    } else {
        $message = "Email not found.";
        showErrorModal('Oops', $message);
        header("refresh:2;");
    }
}
?>