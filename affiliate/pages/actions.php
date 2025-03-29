<?php


$sql = "SELECT * FROM  ".$siteprefix."alerts WHERE status='0' ORDER BY s DESC LIMIT 5";
$sql2 = mysqli_query($con,$sql);
$notification_count = mysqli_num_rows($sql2);
 
if (isset($_GET['action']) && $_GET['action'] == 'read-message') {
    $sql = "UPDATE dv_alerts SET status='1' WHERE status='0'";
    $sql2 = mysqli_query($con,$sql);
    $message="All notifications marked as read.";
    showToast($message);
    header("refresh:2; url=notifications.php");
}


// add to affiliate list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_affiliate_list'])) {
    $user_id = $_POST['user_id']; 
    $affliate_id = $_POST['affliate_id'];
    // Assuming user ID is stored in the session
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']); // Sanitize product ID

    // Check if the product is already in the affiliate's list
    $check_query = "SELECT * FROM " . $siteprefix . "affiliate_products WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Product is already in the list
        $message = "This product is already in your affiliate list.";
        showToast($message);
        header("refresh:2; url=reports.php");
        exit();
    }


  
    // Generate affiliate link
    $base_url = "http://text/project_hub/ProjectHub/product.php";
    $affiliate_link = $base_url . "?id=" . urlencode($product_id) . "&ref=" . urlencode($affliate_id);
    // Add product to affiliate's list
    $insert_query = "INSERT INTO " . $siteprefix . "affiliate_products (user_id, product_id, affiliate_link,affiliate_id) 
                     VALUES ('$user_id', '$product_id', '$affiliate_link','$affliate_id')";
    if (mysqli_query($con, $insert_query)) {
        $message = "Product added to your affiliate list successfully!";
        showSuccessModal('Processed', $message);
        header("refresh:1; url=reports.php");
        exit();
    } else {
        $message = "Failed to add product to your affiliate list: " . mysqli_error($con);
        showErrorModal('Oops', $message);
        exit();
    }
}

?>










