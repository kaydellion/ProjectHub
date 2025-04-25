
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
$query = "SELECT r.*, ri.picture 
          FROM ".$siteprefix."reports r 
          LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id 
          WHERE r.user = '$seller_id' AND r.status = 'approved' 
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
                    <!-- Follow/Unfollow Button -->
                     
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
            </div>
        </div>
   

    <!-- Products Section -->
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Product Count -->
                <div class="product-count" style="background-color: orange; color: white; padding: 5px 10px; border-radius: 5px;">
                    Found <?php echo $report_count; ?> product(s)
                </div>

                <!-- Sort By Dropdown -->
                <div class="sort-by">
                    <label for="sort-select" class="me-2">Sort By:</label>
                    <select id="sort-select" class="form-select" onchange="sortReports(this.value)">
                        <option value="relevance" <?php if ($sort === 'relevance') echo 'selected'; ?>>Relevance</option>
                        <option value="price_high" <?php if ($sort === 'price_high') echo 'selected'; ?>>Price - High To Low</option>
                        <option value="price_low" <?php if ($sort === 'price_low') echo 'selected'; ?>>Price - Low To High</option>
                    </select>
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

<?php include "footer.php"; ?>