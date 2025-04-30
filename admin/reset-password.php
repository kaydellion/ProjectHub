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
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"data-assets-path="assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

    <title>Forgot Password | ProjectHub </title>

    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->


    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-text demo text-body fw-bolder">Administration</span>
                </a>
              </div>
              <!-- /Logo -->
              <p class="mb-4 text-center"></p>
              <h4 class="mb-2">Reset Password 🔒</h4>


              <form class="mb-3" action="" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <div>
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" name="reset-password" class="btn btn-primary d-grid w-100">Reset Password</button>
        </form>
     
        </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
