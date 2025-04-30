<?php
include "../backend/connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset-password'])) {
    $token = mysqli_real_escape_string($con, $_POST['token']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        showErrorModal('Oops', $message);
        header("refresh:2;");
    } else {
        // Check if the token is valid and not expired
        $query = "SELECT * FROM {$siteprefix}users WHERE reset_token = '$token' AND reset_token_expiry > NOW() AND type='admin'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            // Hash the password
            $hashed_password = hashPassword($password);

            // Update the password and clear the reset token
            $update_query = "UPDATE {$siteprefix}users SET password = '$hashed_password', reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = '$token'";
            if (mysqli_query($con, $update_query)) {
                $message = "Your password has been reset successfully.";
                showSuccessModal('Success', $message);
                header("refresh:2; url=index.php");
            } else {
                $message = "Failed to reset password. Please try again.";
                showErrorModal('Oops', $message);
                header("refresh:2;");
            }
        } else {
            $message = "Invalid or expired token.";
            showErrorModal('Oops', $message);
            header("refresh:2;");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <div>
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" name="reset-password">Reset Password</button>
        </form>
    </div>
</body>
</html>