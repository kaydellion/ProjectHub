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

            <div class="mb-3">
                <div class="d-flex align-items-center">
                    <div class="text-warning me-2">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="text-muted"> (128 reviews)</span>
                </div>
            </div>

            <p class="mb-4"><?php echo $description; ?></p>

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
                    <input type="radio" class="btn-check" value="<?php echo $file_id; ?>" name="btnradio" id="btnradio1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio1"><?php echo $file_extension;?></label>
<?php } ?>
            </div>

            <!-- Actions -->
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="button" name="add" id="addCart">Add to Cart</button>
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












<?php include "footer.php"; ?>