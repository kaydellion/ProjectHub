<?php

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $sql = "SELECT r.*, l.category_name as category_name, ri.picture , sc.category_name as subcategory_name, u.display_name, u.profile_picture 
    FROM " . $siteprefix . "reports r 
    LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
    LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
    LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
    LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id 
    WHERE r.id = '$id' GROUP BY r.id";
    
    $sql2 = mysqli_query($con, $sql);
    if (!$sql2) {die("Query failed: " . mysqli_error($con)); }
    if (mysqli_num_rows($sql2) == 0) { header("Location: $previousPage"); exit(); }
    $count = 0;
    while ($row = mysqli_fetch_array($sql2)) {
        $report_id = $row['id'];
        $title = $row['title'];
        $description = $row['description'];
        $preview = $row['preview'];
        $table_content = $row['table_content'];
        $category = $row['category'];
        $subcategory = $row['subcategory'];
        $pricing = $row['pricing'];
        $price = $row['price'];
        $tags = $row['tags'];
        $loyalty = $row['loyalty'];
        $user = $row['display_name'];
        $user_picture = $imagePath.$row['profile_picture'];
        $created_date = $row['created_date'];
        $updated_date = $row['updated_date'];
        $status = $row['status'];
        $image_path = $imagePath.$row['picture'];
        $methodology = $row['methodology'];
        $selected_education_level = $row['education_level'] ?? '';
        $selected_resource_type = $row['resource_type'] ?? '';
        $selected_resource_type_array = explode(',',$selected_resource_type); // assuming stored as comma-separated
        $selected_years = $row['year_of_study'] ?? '';
        $selected_years_array = explode(',',$selected_years); // assuming stored as comma-separated
        $chapter_count = $row['chapter'] ?? '';
        $answer = $row['answer'] ?? '';
    }
} else {
    header("Location: $previousPage");
}

$rating_data = calculateRating($report_id, $con, $siteprefix);
$average_rating = $rating_data['average_rating'];
$review_count = $rating_data['review_count'];


$initialtext = "Add to Wishlist";
$initialbtn = "btn-outline-secondary";
if($active_log==1){
$checkEmail = mysqli_query($con, "SELECT * FROM ".$siteprefix."wishlist WHERE user='$user_id' AND product='$report_id'");
if(mysqli_num_rows($checkEmail) >= 1 ) {
    $initialtext = "Remove from Wishlist";
    $initialbtn = "btn-primary";
}}

//getresource_type and education_level
$sql = "SELECT * FROM ".$siteprefix."resource_types WHERE id = '$selected_resource_type' ORDER BY is_new DESC, name ASC ";
$result = $con->query($sql);
$resource_types = [];
while ($row = $result->fetch_assoc()) {
    $resource_type = $row['name'];
}
$sql = "SELECT * FROM ".$siteprefix."education_levels WHERE id = '$selected_education_level' ORDER BY is_new DESC, name ASC ";
$result = $con->query($sql);
$education_levels = [];
while ($row = $result->fetch_assoc()) {
    $education_level = $row['name'];
}




?>