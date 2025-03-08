<?php include "header.php"; 

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $sql = "SELECT r.*, l.category_name as category_name, ri.picture , sc.category_name as subcategory_name, u.display_name, u.profile_picture 
    FROM " . $siteprefix . "reports r 
    LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
    LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
    LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
    LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id 
    WHERE r.id = '$id' AND r.status = 'approved' GROUP BY r.id";
    
    $sql2 = mysqli_query($con, $sql);
    if (!$sql2) {die("Query failed: " . mysqli_error($con)); }
    if (mysqli_num_rows($sql2) == 0) { header("Location: $previousPage"); exit(); }
    $count = 0;
    while ($row = mysqli_fetch_array($sql2)) {
        $report_id = $row['id'];
        $title = $row['title'];
        $description = $row['description'];
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
    }
} else {
    header("Location: $previousPage");
}

$rating_data = calculateRating($report_id, $con, $siteprefix);
$average_rating = $rating_data['average_rating'];
$review_count = $rating_data['review_count'];
?>


<div class="container py-5">
    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <img src="<?php echo $image_path; ?>" class="card-img-top" alt="Product Image">
                <div class="card-body">
                    <div class="row g-2">
                        <?php
                        $sql3 = "SELECT * FROM ".$siteprefix."reports_images WHERE report_id = '$report_id'";   
                        $sql4 = mysqli_query($con, $sql3);
                        if (!$sql4) {die("Query failed: " . mysqli_error($con)); }
                        while ($row = mysqli_fetch_array($sql4)) {
                            $image_path = $imagePath.$row['picture'];
                        ?>
                        <div class="col-3">
                            <img src="<?php echo $image_path; ?>" class="img-thumbnail" alt="Thumbnail 1">
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="h2 mb-3"><?php echo $title; ?></h1>
            <div class="mb-3">
                <span class="h4 me-2"><?php echo $sitecurrency; echo $price; ?></span><br>
                <span class="badge text-light bg-danger ms-2">Loyalty Material</span>
            </div>

            <div class="mb-1">
                <div class="d-flex align-items-center">
                    <div class="text-warning me-2">
                        <?php
                        for($i = 1; $i <= 5; $i++) {
                            if($i <= $average_rating) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif($i - $average_rating > 0 && $i - $average_rating < 1) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <span class="text-muted"> (<?php echo $review_count;?> reviews)</span>
                </div>
            </div>

            <div><?php echo $description; ?></div>

            <!-- Color Selection -->
            <form method="post">
            <div class="mb-3">
                <h6 class="mb-3">Available File Formats</h6>
                <div class="btn-group mb-3" role="group" aria-label="Basic radio toggle button group">
<?php 
$sql = "SELECT * FROM ".$siteprefix."reports_files WHERE report_id = '$report_id'";
$sql2 = mysqli_query($con, $sql);
if (!$sql2) {die("Query failed: " . mysqli_error($con)); }
while ($row = mysqli_fetch_array($sql2)) {
    $file_id = $row['id'];
    $file_title = $row['title'];
    $file_pages = $row['pages'];
    $file_updated_at = $row['updated_at'];
    $file_extension = getFileExtension($file_title);
?>
                    <input type="radio" class="btn-check" value="<?php echo $file_id; ?>" name="btnradio" id="btnradio<?php echo $file_id; ?>" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio<?php echo $file_id; ?>"><?php echo $file_extension;?> (p.<?php echo $file_pages;?>)</label>
<?php } ?>
            </div>

            <!-- Actions -->
            <div class="d-grid gap-2">
                <input type="hidden" name="report_id"  id="current_report_id" value="<?php echo $report_id;?>">
                <button class="btn btn-primary" type="button" data-report="<?php echo $report_id;?>" name="add" id="addCart">Add to Cart</button>
                </form>
                <button class="btn btn-outline-secondary" type="button"> <i class="far fa-heart me-2"></i>Add to Wishlist </button>
            </div>

            <!-- Additional Info -->
            <div class="mt-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shield-alt text-primary me-2"></i>
                    <span>Verified seller </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reviews -->
<div class="container py-5">
    <h2 class="h4 mb-4">Reviews</h2>
    <div class="row">
    <div class="mt-3 mb-3">
                  <?php
                      $review_query = "SELECT r.*, u.display_name FROM ".$siteprefix."reviews r 
                              LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
                              WHERE r.report_id = '$report_id' 
                              ORDER BY r.date DESC LIMIT 10";
                      $review_result = mysqli_query($con, $review_query);

                      while ($review = mysqli_fetch_assoc($review_result)) {
                        echo '<div class="mb-3">';
                        echo '<div class="d-flex align-items-center">';
                        echo '<strong>' . htmlspecialchars($review['name']) . '</strong>';
                        echo '<div class="ms-3">';
                        for ($i = 1; $i <= $review['rating']; $i++) {
                          echo '<i class="bi bi-star-fill text-warning"></i>';
                        }
                        echo '</div></div>';
                        echo '<p class="mt-2">' . htmlspecialchars($review['review']) . '</p>';
                        echo '<small class="text-muted">' . date('M d, Y', strtotime($review['date'])) . '</small>';
                        echo '</div>';
                      }
                      ?>
                  </div>
    </div>
</div>


<!-- Related Products -->
<div class="container py-5">
    <h2 class="h4 mb-4">Related Products</h2>
    <div class="row">
<?php
$sql = "SELECT r.*, ri.picture FROM ".$siteprefix."reports r
LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id
WHERE r.category = '$category' AND r.subcategory = '$subcategory' AND r.id != '$report_id' AND r.status = 'approved' GROUP BY r.id LIMIT 4";
$sql2 = mysqli_query($con, $sql);
if (!$sql2) {die("Query failed: " . mysqli_error($con)); }
if (mysqli_num_rows($sql2) > 0) {
while ($row = mysqli_fetch_array($sql2)) {
    $related_report_id = $row['id'];
    $related_title = $row['title'];
    $related_price = $row['price'];
    $related_image_path = $imagePath.$row['picture'];

    include "product-card.php";
}} else {
echo '<div class="alert alert-warning" role="alert">
    No related products found. <a href="marketplace.php" class="alert-link">View more reports in marketplace</a>
      </div>';
}
?>
</div></div>  <!-- / .row -->

</div>  <!-- / .container -->
<?php include "footer.php"; ?>