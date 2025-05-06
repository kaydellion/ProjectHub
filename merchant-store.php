
<?php include "header.php"; 

if (isset($_GET['seller_id'])) {
    $seller_id = $_GET['seller_id'];
    
    // Fetch seller details
    $seller_query = "SELECT display_name, profile_picture, biography FROM ".$siteprefix."users WHERE s = '$seller_id'";
    $seller_result = mysqli_query($con, $seller_query);
    $seller_data = mysqli_fetch_assoc($seller_result);

    if (!$seller_data) {
        echo '<div class="container py-5"><div class="alert alert-danger">Seller not found.</div></div>';
        include "footer.php";
        exit;
    }

    $user = $seller_data['display_name'];
    $user_picture = $imagePath . $seller_data['profile_picture'];
    $seller_about = $seller_data['biography'];
} else {
    header("Location: index.php");
    exit;
}

$limit = 16; // Number of reports per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Handle sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'relevance';
$order_by = "r.id DESC"; // Default sorting by relevance
if ($sort === 'price_high') {
    $order_by = "r.price DESC";
} elseif ($sort === 'price_low') {
    $order_by = "r.price ASC";
}





// Fetch seller's products
$query = "SELECT r.*, 
       ri.picture, 
       l.category_name AS category, 
       sc.category_name AS subcategory
FROM {$siteprefix}reports r
LEFT JOIN {$siteprefix}reports_images ri ON r.id = ri.report_id
LEFT JOIN {$siteprefix}categories l ON r.category = l.id
LEFT JOIN {$siteprefix}categories sc ON r.subcategory = sc.id
WHERE r.user = '$seller_id' 
  AND r.status = 'approved'
GROUP BY r.id
ORDER BY $order_by
LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $query);
$report_count = mysqli_num_rows($result);

// Get total number of reports
$total_query = "SELECT COUNT(*) as total FROM ".$siteprefix."reports WHERE status = 'approved' AND user='$seller_id'";
$total_result = mysqli_query($con, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_reports = $total_row['total'];
$total_pages = ceil($total_reports / $limit);
?>
<?php

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
<div class="container mt-5">
    <!-- Seller Information -->
  
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="d-flex align-items-center mb-3">
                <!-- Seller Image -->
                <img src="<?php echo $user_picture; ?>" alt="Seller Photo" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <!-- Seller Name -->
                    <h3><?php echo $user; ?></h3>
                    <!-- About Us -->
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
                    <!-- Follow/Unfollow Button -->
                    </div></div>
					</div>
                     
    <?php
    // Check if the user is already following the seller
    $followQuery = "SELECT * FROM {$siteprefix}followers WHERE user_id = ? AND seller_id = ?";
    $stmt = $con->prepare($followQuery);
    $stmt->bind_param("ii", $user_id, $seller_id);
    $stmt->execute();
    $followResult = $stmt->get_result();
    $isFollowing = $followResult->num_rows > 0;
    ?>
  <!-- Follow and Sort Controls -->
   <div class="col-lg-12">
  <div class="d-flex align-items-center">
                    <!-- Follow Seller -->
                    <form method="POST" class="d-inline me-3">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" name="seller_id" value="<?php echo $seller_id; ?>">
                        <input type="hidden" name="follow_seller_submit" value="1">

                        <?php if ($isFollowing): ?>
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
                            <button type="submit" name="action" value="follow" class="btn btn-outline-primary btn-sm">
                                Follow Seller
                            </button>
                            
                        <?php endif; ?>
                    </form>
                   
                    <div class="d-flex align-items-center d-none d-md-flex">
                <span class="product-count me-2" style="background-color: orange; color: white; padding: 5px 10px; border-radius: 5px;">Followers: <?php echo $totalFollowers; ?></span>
                <span class="product-count me-2" style="background-color: orange; color: white; padding: 5px 10px; border-radius: 5px;">Followings: <?php echo $totalFollowings; ?></span>
                   </div>
                   
                    <!-- Sort Dropdown -->
                    <div class="d-flex align-items-center me-2">
                        
                        <select id="sort-select" class="form-select form-select-sm" onchange="sortReports(this.value)" style="width: auto;">
                            <option value="relevance" <?php if ($sort === 'relevance') echo 'selected'; ?>>Relevance</option>
                            <option value="price_high" <?php if ($sort === 'price_high') echo 'selected'; ?>>Price - High To Low</option>
                            <option value="price_low" <?php if ($sort === 'price_low') echo 'selected'; ?>>Price - Low To High</option>
                        </select>
                    </div>
                    <div class="product-count me-2" style="background-color: orange; color: white; padding: 5px 10px; border-radius: 5px;">
                        Found <?php echo $report_count; ?> product(s)
                        </div>
                      


                        

                </div>
                <div class="col-lg-12 mt-2 d-block d-md-none">
    <div class="d-flex align-items-center">
        <span class="product-count me-2" style="background-color: orange; color: white; padding: 5px 10px; border-radius: 5px;">Followers: <?php echo $totalFollowers; ?></span>
        <span class="product-count me-2" style="background-color: orange; color: white; padding: 5px 10px; border-radius: 5px;">Followings: <?php echo $totalFollowings; ?></span>
    </div>
</div>
   
    </div>
   
            
    
            <div class="row mt-3">
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $report_id = $row['id'];
                        $title = $row['title'];
                        $description = $row['description'];
                        $price = $row['price'];
                        $category = $row['category'];
                        $subcategory = $row['subcategory'];
                        $pricing = $row['pricing'];
                        $price = $row['price'];
                        $tags = $row['tags'];
                        $loyalty = $row['loyalty'];
                        $created_date = $row['created_date'];
                        $updated_date = $row['updated_date'];
                        $status = $row['status'];
                        $image_path = $imagePath.$row['picture'];
                        $slug = strtolower(str_replace(' ', '-', $title));
                        $selected_education_level = $row['education_level'] ?? '';
                        $selected_resource_type = $row['resource_type'] ?? '';
                        $year_of_study = $row['year_of_study'] ?? '';
                        include "product-card.php";
                    }
                } else {
                    echo "<p>No products found.</p>";
                }
                ?>
            </div>

            <!-- Pagination -->
            <div class="justify-content-center pagination">
                <?php if ($page > 1): ?>
                    <a href="?seller_id=<?php echo $seller_id; ?>&page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>" class="btn btn-primary">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?seller_id=<?php echo $seller_id; ?>&page=<?php echo $i; ?>&sort=<?php echo $sort; ?>" class="btn btn-secondary <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?seller_id=<?php echo $seller_id; ?>&page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>" class="btn btn-primary">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function sortReports(sortValue) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('sort', sortValue);
        window.location.search = urlParams.toString();
    }
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
<?php include "footer.php"; ?>