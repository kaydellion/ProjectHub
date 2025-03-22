<?php

include("connect.php"); // Include your database connection

if (!isset($_GET['reference'])) {
    die("Error: Payment reference is missing.");
}

$reference = $_GET['reference']; // Retrieve the payment reference
$user_id = $_GET['user_id']; // Retrieve the user ID
$plan_id = $_GET['plan_id']; // Retrieve the plan ID


if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Retrieve plan details from the database
$query = "SELECT duration, no_of_duration,price FROM pr_subscription_plans WHERE s = '$plan_id'";
$plan_result = mysqli_query($con, $query);

if ($plan_result && mysqli_num_rows($plan_result) > 0) {
    $plan = mysqli_fetch_assoc($plan_result);
    $duration = $plan['duration'];
    $no_of_duration = $plan['no_of_duration'];
    $amount = $plan['price'];
    // Calculate subscription end date
    $start_date = date("Y-m-d H:i:s");
    if ($duration === "Monthly") {
        $end_date = date("Y-m-d H:i:s", strtotime("+$no_of_duration months", strtotime($start_date)));
    } elseif ($duration === "Yearly") {
        $end_date = date("Y-m-d H:i:s", strtotime("+$no_of_duration years", strtotime($start_date)));
    } else {
        $end_date = date("Y-m-d H:i:s", strtotime("+1 month", strtotime($start_date)));
    }

    // Insert subscription into the database
    $sql = "INSERT INTO pr_loyalty_purchases (user_id, loyalty_id, amount, start_date, end_date, payment_reference)
            VALUES ('$user_id', '$plan_id','$amount', '$start_date', '$end_date', '$reference')";
    if (mysqli_query($con, $sql)) {
        // Update user subscription
        $update_user = "UPDATE pr_users SET loyalty='$plan_id' WHERE s='$user_id'";
        if (mysqli_query($con, $update_user)) {
            header("Location: ../loyalty-status.php");
            exit;
        } else {
            echo "Error updating user subscription: " . mysqli_error($con);
        }
    } else {
        echo "Error inserting subscription: " . mysqli_error($con);
    }
} else {
    echo "Error: Subscription plan not found.";
}

mysqli_close($con);
?>