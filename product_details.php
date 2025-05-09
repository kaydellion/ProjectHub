<?php

if (isset($_GET['slug'])) {
    // Convert slug back to title-style for DB lookup
    $raw_slug = $_GET['slug'];
    $title_like = str_replace('-', ' ', $raw_slug);
    $title = mysqli_real_escape_string($con, $title_like);

    // Get report ID by matching title (case-insensitive)
    $slug_sql = "SELECT id FROM " . $siteprefix . "reports 
                 WHERE LOWER(alt_title) = LOWER('$title') AND status = 'approved' LIMIT 1";

    $slug_result = mysqli_query($con, $slug_sql);

    if ($slug_row = mysqli_fetch_assoc($slug_result)) {
        $id = $slug_row['id'];

        $sql = "SELECT r.*, 
                       l.category_name as category_name, 
                       ri.picture, 
                       sc.category_name as subcategory_name, 
                       u.display_name, 
                       u.profile_picture 
                FROM " . $siteprefix . "reports r 
                LEFT JOIN " . $siteprefix . "categories l ON r.category = l.id 
                LEFT JOIN " . $siteprefix . "users u ON r.user = u.s 
                LEFT JOIN " . $siteprefix . "categories sc ON r.subcategory = sc.id 
                LEFT JOIN " . $siteprefix . "reports_images ri ON r.id = ri.report_id 
                WHERE r.id = '$id' AND r.status = 'approved' 
                GROUP BY r.id";

        $sql2 = mysqli_query($con, $sql);

        if (!$sql2) {
            die("Query failed: " . mysqli_error($con));
        }

        if (mysqli_num_rows($sql2) == 0) {
            header("Location: $previousPage");
            exit();
        }

        $row = mysqli_fetch_assoc($sql2);

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
        $user_picture = $imagePath . $row['profile_picture'];
        $created_date = $row['created_date'];
        $updated_date = $row['updated_date'];
        $status = $row['status'];
        $image_path = $imagePath . $row['picture'];
        $methodology = $row['methodology'];
        $selected_education_level = $row['education_level'] ?? '';
        $selected_resource_type = $row['resource_type'] ?? '';
        $selected_resource_type_array = explode(',', $selected_resource_type);
        $selected_years = $row['year_of_study'] ?? '';
        $selected_years_array = explode(',', $selected_years);
        $chapter_count = $row['chapter'] ?? '';
        $answer = $row['answer'] ?? '';

    } else {
        header("Location: $previousPage");
        exit();
    }

} else {
    header("Location: $previousPage");
    exit();
}


$rating_data = calculateRating($report_id, $con, $siteprefix);
$average_rating = $rating_data['average_rating'];
$review_count = $rating_data['review_count'];

$loyalty_id=0;
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