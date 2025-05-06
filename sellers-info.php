
<?php
// Fetch seller details
$seller_query = "SELECT u.s AS seller_id, u.display_name AS seller_name, u.profile_picture AS seller_photo, u.biography AS seller_about, 
                 u.facebook AS seller_facebook, u.twitter AS seller_twitter, u.instagram AS seller_instagram, u.linkedln AS seller_linkedin, 
                 COUNT(r.id) AS seller_resources_count 
                 FROM ".$siteprefix."users u 
                 LEFT JOIN ".$siteprefix."reports r ON u.s = r.user 
                 WHERE u.s = (SELECT user FROM ".$siteprefix."reports WHERE id = '$report_id') AND r.status = 'approved'";
$seller_result = mysqli_query($con, $seller_query);
$seller_data = mysqli_fetch_assoc($seller_result);

$seller_id = $seller_data['seller_id'];
$seller_name = $seller_data['seller_name'];
$seller_photo = $imagePath . $seller_data['seller_photo'];
$seller_about = $seller_data['seller_about'];
$seller_facebook = $seller_data['seller_facebook'];
$seller_twitter = $seller_data['seller_twitter'];
$seller_instagram = $seller_data['seller_instagram'];
$seller_linkedin = $seller_data['seller_linkedin'];
$seller_resources_count = $seller_data['seller_resources_count'];


// Fetch the number of followers
$followersQuery = "SELECT COUNT(*) AS total_followers FROM {$siteprefix}followers WHERE seller_id = '$seller_id'";
$followersResult = mysqli_query($con, $followersQuery);
$followersData = mysqli_fetch_assoc($followersResult);
$totalFollowers = $followersData['total_followers'] ?? 0;

// Fetch the number of followings
$followingsQuery = "SELECT COUNT(*) AS total_followings FROM {$siteprefix}followers WHERE user_id = '$seller_id'";
$followingsResult = mysqli_query($con, $followingsQuery);
$followingsData = mysqli_fetch_assoc($followingsResult);
$totalFollowings = $followingsData['total_followings'] ?? 0;
?>