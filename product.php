<?php include "header.php"; include "product_details.php"; 

//get and decode affliate_id if it exists
$affliate_id = isset($_GET['affliate']) ? base64_decode($_GET['affliate']) : 0;

// Check if user has purchased THIS product
$purchase_query = "SELECT * FROM ".$siteprefix."orders o 
JOIN ".$siteprefix."order_items oi ON o.order_id = oi.order_id 
WHERE o.user = ? AND oi.report_id = ?";
$stmt = $con->prepare($purchase_query);
$stmt->bind_param("ss", $user_id, $report_id);
$stmt->execute();
$purchase_result = $stmt->get_result();
$user_purchased = $purchase_result->num_rows > 0;


// Check if user already left a review
$existing_review_query = "SELECT * FROM ".$siteprefix."reviews WHERE user = ? AND report_id = ?";
$stmt = $con->prepare($existing_review_query);
$stmt->bind_param("si", $user_id, $report_id);
$stmt->execute();
$existing_review_result = $stmt->get_result();
$user_review = $existing_review_result->fetch_assoc();

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
               <?php if($loyalty==1){ ?> <span class="badge text-light bg-danger ms-2">Loyalty Material</span> <?php } ?>
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

     <div>
        <div class="description-container">
            <!-- Hidden full description -->
            <div class="full-description" style="display: none;">
             <?php echo $description; ?>
            </div>
            <!-- Visible preview -->
            <div class="preview-description"></div>
            <span class="read-more-btn">Read More</span>
        </div>
    </div>

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
                <input type="hidden" name="affliate_id" id="affliate_id" value="<?php echo $affliate_id;?>">
                <button class="btn btn-primary" type="button" data-report="<?php echo $report_id;?>" name="add" id="addCart">Add to Cart</button>
                </form>
                <button class="btn <?php echo $initialbtn; ?> addtowishlist" type="button" data-product-id="<?php echo $report_id; ?>"><i class="far fa-heart me-2"></i><?php echo $initialtext; ?></button>
            </div>

            <!-- Additional Info -->
            <div class="mt-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shield-alt text-primary me-2"></i>
                    <span>Verified seller </span>
                </div>
            </div>
           <!-- Social Share Icons -->
<!-- Social Share Icons -->
<div class="d-flex mt-3">
    <?php
    $share_url = urlencode("https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    $share_title = urlencode($title);
    $share_text = urlencode("Check out this report: " . $title);
    ?>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="text-decoration-none">
        <i class="fab fa-facebook text-primary p-2" style="font-size: 1.5rem;"></i>
    </a>
    <a href="https://twitter.com/intent/tweet?text=<?php echo $share_text; ?>&url=<?php echo $share_url; ?>" target="_blank" class="text-decoration-none">
        <i class="fab fa-twitter text-info p-2" style="font-size: 1.5rem;"></i> 
    </a>
    <a href="https://api.whatsapp.com/send?text=<?php echo $share_text . ' ' . $share_url; ?>" target="_blank" class="text-decoration-none">
        <i class="fab fa-whatsapp text-success p-2" style="font-size: 1.5rem;"></i> 
    </a>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" target="_blank" class="text-decoration-none">
        <i class="fab fa-linkedin text-primary p-2" style="font-size: 1.5rem;"></i> 
    </a>
   
</div>




        </div>
    </div>
</div>

<!-- Report Product Button -->
<?php if ($active_log == 1): ?>
    <div class="d-flex justify-content-left mt-3">
        <button class="btn btn-danger" data-toggle="modal" data-target="#reportProductModal">
            <i class="fas fa-flag"></i> Report Product
        </button>
    </div>
<?php else: ?>
    <div class="d-flex justify-content-left mt-3">
        <button class="btn btn-secondary" disabled>
            <i class="fas fa-flag"></i> Report Product
        </button>
    </div>
<?php endif; ?>


<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Content Preview</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Table of Contents</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Reviews</button>
  </li>
</ul>
<div class="tab-content" id="myTabContent">

  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"><!--preview -->
  <?php echo $preview; ?></div>

  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"><!--table-->
  <?php echo $table_content; ?></div>

  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
    <!-- Reviews -->
<div class="container py-5">
    <h2 class="h4 mb-4">Reviews</h2>

    <!-- Allow user to leave a review if they purchased the product -->
    <?php if ($user_purchased) { ?>
        <div class="card p-3 mb-4">
            <h5 class="mb-3"><?php echo $user_review ? "Edit Your Review" : "Leave a Review"; ?></h5>
            <form action="" method="post">
                <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                <div class="mb-3">
                    <label for="rating" class="form-label">Rating</label>
                    <select class="form-select" name="rating" required>
                        <option value="5" <?php if ($user_review && $user_review['rating'] == 5) echo "selected"; ?>>⭐️⭐️⭐️⭐️⭐️</option>
                        <option value="4" <?php if ($user_review && $user_review['rating'] == 4) echo "selected"; ?>>⭐️⭐️⭐️⭐️</option>
                        <option value="3" <?php if ($user_review && $user_review['rating'] == 3) echo "selected"; ?>>⭐️⭐️⭐️</option>
                        <option value="2" <?php if ($user_review && $user_review['rating'] == 2) echo "selected"; ?>>⭐️⭐️</option>
                        <option value="1" <?php if ($user_review && $user_review['rating'] == 1) echo "selected"; ?>>⭐️</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="review" class="form-label">Your Review</label>
                    <textarea class="form-control" name="review" rows="3" required><?php echo $user_review ? htmlspecialchars($user_review['review']) : ''; ?></textarea>
                </div>

                <button type="submit" name="submit-review" value="review" class="btn btn-primary"><?php echo $user_review ? "Update Review" : "Submit Review"; ?></button>
            </form>
        </div>
    <?php } ?>

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
                        echo '<strong>' . htmlspecialchars($review['display_name']) . '</strong>';
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
</div></div>
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

    include "product-card.php";
}} else {
echo '<div class="alert alert-warning" role="alert">
    No related products found. <a href="marketplace.php" class="alert-link">View more reports in marketplace</a>
      </div>';
}
?>
</div></div>  <!-- / .row -->

</div>  <!-- / .container -->


<!-- Report Product Modal -->
<div class="modal fade" id="reportProductModal" tabindex="-1" role="dialog" aria-labelledby="reportProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportProductModalLabel">Report Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="product_id" value="<?php echo $report_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Reporting</label>
                        <select class="form-select" name="reason" id="reason" required>
                            <option value="Inappropriate Content">Inappropriate Content</option>
                            <option value="Copyright Violation">Copyright Violation</option>
                            <option value="Spam or Misleading">Spam or Misleading</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3" id="customReasonContainer" style="display: none;">
                        <label for="custom_reason" class="form-label">Custom Reason</label>
                        <textarea class="form-control" name="custom_reason" id="custom_reason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_report" class="btn btn-danger">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const reasonSelect = document.getElementById("reason");
        const customReasonContainer = document.getElementById("customReasonContainer");

        reasonSelect.addEventListener("change", function () {
            if (this.value === "Other") {
                customReasonContainer.style.display = "block";
            } else {
                customReasonContainer.style.display = "none";
            }
        });
    });
</script>
<?php include "footer.php"; ?>