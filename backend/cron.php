
<?php
include("connect.php"); // Include database connection


// Fetch expired subscriptions
$query = "SELECT lp.*, u.email, u.name AS display_name
          FROM pr_loyalty_purchases lp
          JOIN pr_users u ON lp.user_id = u.s
          WHERE lp.end_date <= NOW() AND u.loyalty != 0";

$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $email = $row['email'];
        $display_name = $row['display_name'];
        $plan_id = $row['loyalty_id'];
        $end_date = $row['end_date'];

        // Email details
        $emailSubject = "Your Subscription Has Expired";
        $emailMessage = "<p>Dear $display_name,</p>
                         <p>Your subscription for plan ID $plan_id has expired on $end_date. Please log in to your account to renew your subscription.</p>
                         <p>Thank you for using our service!</p>";

        // Send email to the user
        if (sendEmail($email, $display_name, $siteName, $siteMail, $emailMessage, $emailSubject)) {
            echo "Email sent to $email\n";
        } else {
            echo "Failed to send email to $email\n";
        }

        // Update user's loyalty status to 0
        $update_query = "UPDATE pr_users SET loyalty = 0 WHERE s = '$user_id'";
        if (mysqli_query($con, $update_query)) {
            echo "User loyalty status updated to 0 for user ID $user_id\n";
        } else {
            echo "Failed to update loyalty status for user ID $user_id: " . mysqli_error($con) . "\n";
        }
    }
} else {
    echo "No expired subscriptions found.\n";
}

mysqli_close($con);
?>