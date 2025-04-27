<?php include "header.php"; include "product_details.php";  include "sellers-info.php"; 

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

<?php
// Log the view of the resource
if (isset($user_id) && isset($report_id)) {
    $log_view_query = "INSERT INTO ".$siteprefix."product_views  (user_id, report_id) VALUES ('$user_id', '$report_id')";
    mysqli_query($con, $log_view_query);
}
?>

<div class="container py-5">
    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6 mb-4">
    <div class="card">
        <!-- Main image stays untouched -->
        <img src="<?php $siteurl.$image_path; ?>" class="card-img-top" alt="Product Image">

        <div class="card-body">
            <div class="row g-2">
                <?php
                $sql3 = "SELECT * FROM ".$siteprefix."reports_images WHERE report_id = '$report_id'";
                $sql4 = mysqli_query($con, $sql3);
                if (!$sql4) { die("Query failed: " . mysqli_error($con)); }

                $allImages = [];
                while ($row = mysqli_fetch_array($sql4)) {
                    $allImages[] = $imagePath . $row['picture'];
                }

                foreach ($allImages as $index => $img) {
                ?>
                <div class="col-3">
                    <img src="<?php $siteurl.$img; ?>" class="img-thumbnail" alt="Thumbnail <?php echo $index + 1; ?>"
                         data-toggle="modal" data-target="#imageModal"
                         data-slide-to="<?php echo $index; ?>">
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal with Carousel for Bootstrap 4 -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <div id="carouselPreview" class="carousel slide" data-ride="false" data-interval="false">
          <div class="carousel-inner">
            <?php foreach ($allImages as $index => $img) { ?>
              <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php $siteurl.$img; ?>" style="max-height: 80vh; object-fit: contain;"  class="d-block w-100" alt="Preview <?php echo $index + 1; ?>">
              </div>
            <?php } ?>
          </div>
          <a class="carousel-control-prev" href="#carouselPreview" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </a>
          <a class="carousel-control-next" href="#carouselPreview" role="button" data-slide="next">
            <span class="carousel-control-next-icon"></span>
          </a>
        </div>
      </div>
      <div class="modal-footer bg-dark border-0 justify-content-center">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
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

    <p>
    <table class="table table-bordered">
    <thead style="background-color: orange; color: white;">
        <tr>
            <th>Resource Attributes</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody style="background-color: #f8f9fa; color: #333;">
        <tr>
            <td><strong>Resource Type</strong></td>
            <td><?php echo $resource_type; ?></td>
        </tr>
        <?php if ($answer != "") { ?>
        <tr>
            <td><strong>Answer Key</strong></td>
            <td><?php echo $answer; ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td><strong>Education Level</strong></td>
            <td><?php echo $education_level; ?></td>
        </tr>
        <tr>
            <td><strong>Year of Study</strong></td>
            <td><?php echo $selected_years; ?></td>
        </tr>
        <tr>
            <td><strong>Chapter Count</strong></td>
            <td><?php echo $chapter_count; ?></td>
        </tr>
        <tr>
            <td><strong>Tags</strong></td>
            <td><?php echo $tags; ?></td>
        </tr>
    </tbody>
</table>
    </p>

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

            <div class="d-flex justify-content-start align-items-center mt-3 mb-3">
 

    <!-- Add to Cart Button -->
    <input type="hidden" name="report_id"  id="current_report_id" value="<?php echo $report_id;?>">
                <input type="hidden" name="affliate_id" id="affliate_id" value="<?php echo $affliate_id;?>">
                <button class="btn btn-primary me-2" type="button" data-report="<?php echo $report_id;?>" name="add" id="addCart">Add to Cart</button>
                </form>
    

    <!-- Add to Wishlist Button -->
    <button class="btn <?php echo $initialbtn; ?> addtowishlist me-2" type="button" data-product-id="<?php echo $report_id; ?>"><i class="far fa-heart me-2"></i><?php echo $initialtext; ?></button>
       <!-- Report Product Button -->
   
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

            <!-- Additional Info -->
          <!-- Seller Information -->
<div class="mt-3">
    <div class="card p-3">
        <div class="d-flex align-items-center">
            <!-- Seller's Photo -->
            <img src="<?php $siteurl.$seller_photo; ?>" alt="Seller Photo" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
            <div>
                <!-- Seller's Name -->
                <h5 class="mb-1"><?php echo $seller_name;  ?></h5>
                <p class="mb-1 text-muted">
                    About the Seller: 
                    <span class="seller-bio-preview">
                        <?php 
                        $words = explode(' ', $seller_about);
                        echo implode(' ', array_slice($words, 0, 4)); // Display first 4 words
                        ?>
                    </span>
                    
                    <span class="seller-bio-full" style="display: none;">
                        <?php echo $seller_about; ?>
                    </span>
                    <?php if (str_word_count($seller_about) > 4) { ?>
        <button class="btn btn-link btn-sm p-0 read-mores-btn" style="text-decoration: none;">Read More</button>
    <?php } ?>
                
                </p>

                <!-- Follow Seller Button
                <button class="btn btn-outline-primary btn-sm follow-seller" data-seller-id="<?php echo $seller_id; ?>">Follow Seller</button>
             -->
            
            </div>
        </div>
        <div class="mt-3">
            <!-- View Merchant Store Link -->
            <a href="<?php echo $siteurl;?>merchant-store.php?seller_id=<?php echo $seller_id; ?>" class="btn btn-primary btn-sm">View Merchant Store</a>
            <!-- Number of Resources -->
            <p class="mt-2 mb-0"><strong>Resources:</strong> <?php echo $seller_resources_count; ?> resources available</p>
        </div>
        <div class="mt-3">
    <?php checkActiveLog($active_log); // Ensure the user is logged in ?>

    <?php
    // Check if the user is already following the seller
    $followQuery = "SELECT * FROM {$siteprefix}followers WHERE user_id = ? AND seller_id = ?";
    $stmt = $con->prepare($followQuery);
    $stmt->bind_param("ii", $user_id, $seller_id);
    $stmt->execute();
    $followResult = $stmt->get_result();
    $isFollowing = $followResult->num_rows > 0;
    ?>

    <form method="POST" class="d-inline">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" name="seller_id" value="<?php echo $seller_id; ?>">
        <input type="hidden" name="follow_seller_submit" value="1">

        <?php if ($isFollowing): ?>
            <!-- Following Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" id="followingDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Following
                </button>
                <ul class="dropdown-menu" aria-labelledby="followingDropdown">
                    <li>
                        <button type="submit" name="action" value="unfollow" class="dropdown-item">
                            Unfollow
                        </button>
                    </li>
                </ul>
            </div>
        <?php else: ?>
            <!-- Follow Button -->
            <button type="submit" name="action" value="follow" class="btn btn-outline-primary btn-sm">
                Follow Seller
            </button>
        <?php endif; ?>
    </form>
</div>

        <div class="mt-3">
    <h6>Connect with the Seller:</h6>
    <div class="d-flex">
        <?php if (!empty($seller_facebook)) { ?>
            <a href="https://www.facebook.com/<?php echo str_replace(' ', '-', $seller_facebook); ?>" target="_blank" class="text-decoration-none me-3">
                <i class="fab fa-facebook text-primary" style="font-size: 1.5rem;"></i>
            </a>
        <?php } ?>
        <?php if (!empty($seller_twitter)) { ?>
            <a href="https://twitter.com/<?php echo str_replace(' ', '-', $seller_twitter); ?>" target="_blank" class="text-decoration-none me-3">
                <i class="fab fa-twitter text-info" style="font-size: 1.5rem;"></i>
            </a>
        <?php } ?>
        <?php if (!empty($seller_instagram)) { ?>
            <a href="https://www.instagram.com/<?php echo str_replace(' ', '-', $seller_instagram); ?>" target="_blank" class="text-decoration-none me-3">
                <i class="fab fa-instagram text-danger" style="font-size: 1.5rem;"></i>
            </a>
        <?php } ?>
        <?php if (!empty($seller_linkedin)) { ?>
            <a href="https://www.linkedin.com/in/<?php echo str_replace(' ', '-', $seller_linkedin); ?>" target="_blank" class="text-decoration-none">
                <i class="fab fa-linkedin text-primary" style="font-size: 1.5rem;"></i>
            </a>
        <?php } ?>
    </div>
</div>
    </div>
</div>
 



        </div>
    </div>

<div class="col-12">

<!-- Report Product Button -->
<?php if ($active_log == 1): ?>
    <div class="d-flex justify-content-left mt-3 mb-3">
        <button class="btn btn-danger" data-toggle="modal" data-target="#reportProductModal">
            <i class="fas fa-flag"></i> Report Product
        </button>
    </div>
<?php else: ?>
    <div class="d-flex justify-content-left mt-3 mb-3">
        <button class="btn btn-secondary" disabled>
            <i class="fas fa-flag"></i> Report Product
        </button>
    </div>
<?php endif; ?>


<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Content Preview</button>
  </li>
  <?php if ($table_content) { ?>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Table of Contents</button>
  </li><?php } elseif($methodology) { ?>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#method" type="button" role="tab" aria-controls="profile" aria-selected="false">Methodology</button>
  </li><?php } ?>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Reviews</button>
  </li>
</ul>
<div class="tab-content" id="myTabContent">

  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"><!--preview -->
  <?php echo $preview; ?></div>

  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"><!--table-->
  <?php echo $table_content; ?></div>

  <div class="tab-pane fade" id="method" role="tabpanel" aria-labelledby="profile-tab"><!--table-->
  <?php echo $methodology; ?></div>

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
</div></div>




<!-- Related Products -->
<div class="container py-5">
    <h2 class="h4 mb-4">Related Products</h2>
    <div class="row">
        
    <?php
$sql = "SELECT r.*, u.display_name, u.profile_picture, ri.picture, 
        l.category_name AS category, sc.category_name AS subcategory 
        FROM ".$siteprefix."reports r
        LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id
        LEFT JOIN ".$siteprefix."users u ON r.user = u.s
        LEFT JOIN ".$siteprefix."categories l ON r.category = l.id
        LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id
        WHERE r.category = '$category' AND r.subcategory = '$subcategory' 
        AND r.id != '$report_id' AND r.status = 'approved' 
        GROUP BY r.id 
        LIMIT 4";
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
    $selected_education_level = $row['education_level'] ?? '';
    $selected_resource_type = $row['resource_type'] ?? '';
    $year_of_study = $row['year_of_study'] ?? '';

    $slug = strtolower(str_replace(' ', '-', $title));
    include "product-card.php";
}} else {
echo '<div class="alert alert-warning" role="alert">
    No related products found. <a href="'.$siteurl.'.marketplace.php" class="alert-link">View more reports in marketplace</a>
      </div>';
}
?>
</div></div>  
  
<!-- other resources --> 

<div class="container py-5">
    <h2 class="h4 mb-4">Other Resources from This Seller</h2>
    <div class="row">
        <div class="col-lg-12">
    <div class="swiper mySwiper">
    <div class="swiper-wrapper">
    <?php
$seller_resources_query = "
    SELECT 
        r.*, 
        u.display_name, 
        u.profile_picture, 
        l.category_name AS category, 
        sc.category_name AS subcategory, 
        ri.picture 
    FROM {$siteprefix}reports r
    LEFT JOIN {$siteprefix}categories l ON r.category = l.id
    LEFT JOIN {$siteprefix}users u ON r.user = u.s
    LEFT JOIN {$siteprefix}categories sc ON r.subcategory = sc.id
    LEFT JOIN {$siteprefix}reports_images ri ON r.id = ri.report_id
    WHERE r.user = '$seller_id' 
        AND r.id != '$report_id' 
        AND r.status = 'approved'
    GROUP BY r.id
    LIMIT 10
";

$seller_resources_result = mysqli_query($con, $seller_resources_query);

if (mysqli_num_rows($seller_resources_result) > 0) {
    while ($row = mysqli_fetch_assoc($seller_resources_result)) {
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
        $user_picture = $imagePath . $row['profile_picture'];
        $created_date = $row['created_date'];
        $updated_date = $row['updated_date'];
        $status = $row['status'];
        $image_path = $imagePath . $row['picture'];
        $selected_education_level = $row['education_level'] ?? '';
        $selected_resource_type = $row['resource_type'] ?? '';
        $year_of_study = $row['year_of_study'] ?? '';
        
        $slug = strtolower(str_replace(' ', '-', $title));
        include "swiper-card.php";
    }?>
</div>
<!-- Add Arrows -->
<div class="swiper-button-next"></div>
<div class="swiper-button-prev"></div>
<!-- Add Pagination -->
<div class="swiper-pagination"></div>
</div>
<?php
} else {
echo '<div class="alert alert-warning" role="alert">
No related products found. <a href="'.$siteurl.'marketplace.php" class="alert-link">View more reports in marketplace</a>
  </div>';
}
?>
</div></div></div>


<!-- Customers Who Viewed This Resource Also Viewed --> 

<div class="container py-5">
    <h2 class="h4 mb-4">Customers Who Viewed This Resource Also Viewed</h2>
    <div class="row">
        <div class="col-lg-12">
    <div class="swiper mySwiper">
    <div class="swiper-wrapper">
    <?php
$viewed_also_query = "
SELECT 
    r.*, 
    u.display_name, 
    u.profile_picture, 
    l.category_name AS category, 
    sc.category_name AS subcategory, 
    ri.picture, 
    COUNT(*) as view_count
FROM {$siteprefix}product_views rv1
JOIN {$siteprefix}product_views rv2 ON rv1.user_id = rv2.user_id
JOIN {$siteprefix}reports r ON rv2.report_id = r.id
LEFT JOIN {$siteprefix}users u ON r.user = u.s
LEFT JOIN {$siteprefix}categories l ON r.category = l.id
LEFT JOIN {$siteprefix}categories sc ON r.subcategory = sc.id
LEFT JOIN {$siteprefix}reports_images ri ON r.id = ri.report_id
WHERE rv1.report_id = '$report_id' 
    AND rv2.report_id != '$report_id' 
    AND r.status = 'approved'
GROUP BY r.id
ORDER BY view_count DESC
LIMIT 10
";
$also_viewed_result = mysqli_query($con, $viewed_also_query);

if (mysqli_num_rows($also_viewed_result) > 0) {
    while ($row = mysqli_fetch_assoc($also_viewed_result)) {
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
        $user_picture = $imagePath . $row['profile_picture'];
        $created_date = $row['created_date'];
        $updated_date = $row['updated_date'];
        $status = $row['status'];
        $image_path = $imagePath . $row['picture'];
        $selected_education_level = $row['education_level'] ?? '';
        $selected_resource_type = $row['resource_type'] ?? '';
        $year_of_study = $row['year_of_study'] ?? '';
        
        $slug = strtolower(str_replace(' ', '-', $title));
        include "swiper-card.php";
    }?>
</div>
<!-- Add Arrows -->
<div class="swiper-button-next"></div>
<div class="swiper-button-prev"></div>
<!-- Add Pagination -->
<div class="swiper-pagination"></div>
</div>
<?php
} else {
echo '<div class="alert alert-warning" role="alert">
No related products found. <a href="'.$siteurl.'marketplace.php" class="alert-link">View more reports in marketplace</a>
  </div>';
}
?>
</div></div></div>



<!-- Subscription plan -->
<div class="container py-5">
    <h2 class="h4 mb-4">Buy for Less – Sign Up as a Loyalty Member Today</h2>
    <div class="row">
    <?php
$query = "SELECT * FROM ".$siteprefix."subscription_plans WHERE status = 'active' ORDER BY s DESC Limit 3";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $plan_id = $row['s'];
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
        $discount = $row['discount'];
        $downloads = $row['downloads'];
        $duration = $row['duration'];
        $benefits = explode(',', $row['benefits']); // Convert benefits into an array
        $status = $row['status'];
        $no_of_duration = $row['no_of_duration'];
        $image_path = !empty($row['image']) ? $imagePath.$row['image'] : $imagePath."default4.jpg";
        $created_at = $row['created_at'];

        include "plan-card.php"; // Include your plan display template
    }
} else {
    debug('No subscription plans found.');
}
?>
</div></div> 




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
<script>
   document.addEventListener("DOMContentLoaded", function () {
    const readMoreButtons = document.querySelectorAll(".read-mores-btn");

    readMoreButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent the default behavior (e.g., page reload)

            const bioPreview = this.previousElementSibling.previousElementSibling; // The preview text
            const bioFull = this.previousElementSibling; // The full text

            if (bioFull.style.display === "none") {
                bioFull.style.display = "inline";
                bioPreview.style.display = "none";
                this.textContent = "Read Less";
            } else {
                bioFull.style.display = "none";
                bioPreview.style.display = "inline";
                this.textContent = "Read More";
            }
        });
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let payButtons = document.querySelectorAll(".payButton");

    payButtons.forEach(function(button) {
        button.addEventListener("click", function () {
            let planId = button.dataset.planId;
            let amount = parseFloat(button.dataset.amount) * 100; // Convert to kobo
            let planName = button.dataset.planName;
            let userId = button.dataset.userId;
            let email = button.dataset.email;

            if (!email || isNaN(amount)) {
                alert("Invalid payment details. Please try again.");
                return;
            }

            var handler = PaystackPop.setup({
                key: '<?php echo $apikey; ?>', // Replace with live key in production
                email: email,
                amount: amount,
                currency: 'NGN',
                ref: 'PH-' + Date.now() + '-' + Math.floor(Math.random() * 1000),
                metadata: {
                    custom_fields: [{
                        display_name: "Plan Name",
                        variable_name: "plan_name",
                        value: planName
                    }]
                },
                callback: function (response) {
                    window.location.href = `<?php echo $siteurl;?>backend/verify_payment.php?action=verify_payment&reference=${response.reference}&plan_id=${planId}&user_id=${userId}`;
                },
                onClose: function () {
                    alert('Payment was canceled.');
                }
            });
            handler.openIframe();
        });
    });
});

</script>


<?php include "footer.php"; ?>