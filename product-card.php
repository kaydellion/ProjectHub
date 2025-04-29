<?php

         //check if its in wishlist
         $theinitialicon="";
        if($active_log==1){
        $checkEmail = mysqli_query($con, "SELECT * FROM ".$siteprefix."wishlist WHERE user='$user_id' AND product='$report_id'");
        if(mysqli_num_rows($checkEmail) >= 1 ) {
        $theinitialicon="added";}}
        
        
//rating
$rating_data = calculateRating($report_id, $con, $siteprefix);
$average_rating = $rating_data['average_rating'];
?>
<style>
/* Overlay container for category and subcategory */
.category-overlay {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px; /* Space between category and subcategory buttons */
    z-index: 2;
}

/* Category button styling */
.category-btn {
    color: #fff; /* White text */
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: bold;
    text-transform: capitalize;
}

/* Subcategory button styling */
.subcategory-btn {
 
    color: #fff; /* White text */
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: bold;
    text-transform: capitalize;
}
</style>
<?php
// Fetch Resource Type and Education Level for each report
$sql_resource_type = "SELECT name FROM ".$siteprefix."resource_types WHERE id = '$selected_resource_type' ORDER BY is_new DESC, name ASC";
$result_resource_type = $con->query($sql_resource_type);
$resource_type = '';
if ($row_resource_type = $result_resource_type->fetch_assoc()) {
    $resource_type = $row_resource_type['name'];
}

$sql_education_level = "SELECT name FROM ".$siteprefix."education_levels WHERE id = '$selected_education_level' ORDER BY is_new DESC, name ASC";
$result_education_level = $con->query($sql_education_level);
$education_level = '';
if ($row_education_level = $result_education_level->fetch_assoc()) {
    $education_level = $row_education_level['name'];
}
?>

<div class="col-lg-3 col-6 product <?php echo removeAllWhitespace($subcategory); ?>">
                    <div class="single_product_item">
                    <div class="single_product_img" style="background: url('<?php echo $siteurl.$image_path; ?>')">
                    <div class="wishlist_icon"><a class="add-to-wishlist <?php echo $theinitialicon; ?>" data-product-id="<?php echo $report_id; ?>"><i class="ti-heart"></i></a></div>
                    <div class="category-overlay">
                <span class="category-btn bg-primary"><?php echo $category; ?></span>
                <span class="subcategory-btn bg-secondary"><?php echo $subcategory; ?></span>
            </div>
              
                </div>
    <div class="single_product_text">
    <a href="<?php echo $siteurl;?>product/<?php echo $slug; ?>">
    <h5 class="text-bold capitalize"><?php echo htmlspecialchars($title); ?></h5>
</a>
                            <a class="text-muted">
    <?php if (!empty($education_level)) { ?>
        <strong>Level:</strong> <?php echo $education_level; ?><br>
    <?php } ?>
    <?php if (!empty($resource_type)) { ?>
        <strong>Type:</strong> <?php echo $resource_type; ?><br>
    <?php } ?>
    <?php if (!empty($year_of_study)) { ?>
        <strong>Year:</strong> <?php echo $year_of_study; ?>
    <?php } ?>
</a>
                            <div class="user_info">
                            <img src="<?php echo "https://projectreporthub.ng/".$user_picture; ?>" alt="<?php echo $user; ?>" class="img-fluid user-image">
                            <span><?php echo $user; ?></span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                            <h6><?php echo $sitecurrency; echo $price; ?></h6>
                            <div class="rating">
                            <i class="fa fa-star text-primary"></i>
                            <span class="text-bold"><?php echo $average_rating; ?></span>
                             </div>
                            </div>
                            </div></div></div>