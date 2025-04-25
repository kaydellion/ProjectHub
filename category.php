<?php include "header.php"; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM " . $siteprefix . "categories WHERE id = " . $id;
    $sql2 = mysqli_query($con, $sql);
    $count = 0;
    while ($row = mysqli_fetch_array($sql2)) {
        $category_name = $row['category_name'];
    }
} else {
    header("Location: index.php");
}

$limit = 16; // Number of reports per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Handle sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'relevance';
$order_by = "r.id DESC"; // Default sorting by relevance
if ($sort === 'price_high') {
    $order_by = "r.price DESC";
} elseif ($sort === 'price_low') {
    $order_by = "r.price ASC";
}

// Handle subcategory filtering
$subcategory_filter = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';
$subcategory_condition = '';

if (!empty($subcategory_filter) && $subcategory_filter !== 'all') {
    $subcategory_condition = "AND sc.category_name = '".mysqli_real_escape_string($con, $subcategory_filter)."'";
}

$query = "SELECT r.*, u.display_name, u.profile_picture, l.category_name AS category, sc.category_name AS subcategory, ri.picture 
          FROM ".$siteprefix."reports r 
          LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
          LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
          LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
          LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id 
          WHERE r.status = 'approved' AND r.category='$id' $subcategory_condition 
          GROUP BY r.id 
          ORDER BY $order_by 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $query);
$report_count = mysqli_num_rows($result);

// Get total number of reports

$total_query = "SELECT COUNT(*) as total 
                FROM ".$siteprefix."reports r 
                LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
                WHERE r.status = 'approved' AND r.category='$id' $subcategory_condition";
$total_result = mysqli_query($con, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_reports = $total_row['total'];
$total_pages = ceil($total_reports / $limit);
?>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
    <h3><?php echo $category_name; ?></h3>
    <?php if ($active_log != "0"): // Only display the button if the user is logged in ?>
        <form method="POST" class="d-inline">
            <?php
            // Check if the user is already following the category
            $followCategoryQuery = "SELECT * FROM ".$siteprefix."followers WHERE user_id = '$user_id' AND category_id = '$id'";
            $followCategoryResult = mysqli_query($con, $followCategoryQuery);
            $isFollowingCategory = mysqli_num_rows($followCategoryResult) > 0;
            ?>
            <?php if ($isFollowingCategory): ?>
                <!-- Unfollow Button -->
                <button type="submit" name="action" value="unfollow_category" class="btn btn-outline-danger btn-sm">
                    Unfollow Category
                </button>
            <?php else: ?>
                <!-- Follow Button -->
                <button type="submit" name="action" value="follow_category" class="btn btn-outline-primary btn-sm">
                    Follow Category
                </button>
            <?php endif; ?>
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
         
            <input type="hidden" name="category_id" value="<?php echo $id; ?>">
            <input type="hidden" name="subcategory_id" value="">
            <input type="hidden" name="follow_category_submit" value="1">
        </form>
    <?php endif; ?>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="subcategories">
    <select id="subcategory-select" class="form-select" onchange="filterBySubcategory(this.value)">
        <option value="">Filter by Subcategory</option>
        <option value="all" <?php if (!isset($_GET['subcategory']) || $_GET['subcategory'] === 'all') echo 'selected'; ?>>Show All</option>
        <?php
        $subcat_query = "SELECT DISTINCT category_name AS subcategory 
                         FROM ".$siteprefix."categories 
                         WHERE parent_id = $id";
        $subcat_result = mysqli_query($con, $subcat_query);
        while ($subcat_row = mysqli_fetch_assoc($subcat_result)) {
            $subcategoryValue = removeAllWhitespace($subcat_row['subcategory']);
            $selected = (isset($_GET['subcategory']) && $_GET['subcategory'] === $subcategoryValue) ? 'selected' : '';
            echo '<option value="'.$subcategoryValue.'" '.$selected.'>'.$subcat_row['subcategory'].'</option>';
        }
        ?>
    </select>
</div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <!-- Product Count -->
                <div class="product-count" style="background-color: orange; color: white; padding: 5px 10px; border-radius: 5px;">
                    Found <?php echo $report_count; ?> report(s)
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
                        include "product-card.php";
                    }
                } else {
                    echo "<p>No reports found.</p>";
                }
                ?>
            </div>

            <!-- Pagination -->
            <div class="justify-content-center pagination">
                <?php if ($page > 1): ?>
                    <a href="?id=<?php echo $id; ?>&page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>" class="btn btn-primary">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?id=<?php echo $id; ?>&page=<?php echo $i; ?>&sort=<?php echo $sort; ?>" class="btn btn-secondary <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?id=<?php echo $id; ?>&page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>" class="btn btn-primary">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<!-- Last Purchased Reports Section -->
<div class="container mt-5 mb-3">
    <div class="row">
        <div class="col-lg-12">
            <h3>Last Purchased Reports</h3>
            <p>Check out the most recently purchased reports in this category. Stay updated with trending academic materials!</p>
            <div class="row mt-3">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php
                        // Query to fetch last purchased reports for the specified category
                        $latestSalesQuery = "
                            SELECT DISTINCT r.id AS report_id, r.title, r.description, r.price, ri.picture, u.display_name, u.profile_picture 
                            FROM ".$siteprefix."orders o
                            JOIN ".$siteprefix."order_items oi ON o.order_id = oi.order_id
                            JOIN ".$siteprefix."reports r ON r.id = oi.report_id
                            LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id
                            LEFT JOIN ".$siteprefix."users u ON r.user = u.s
                            WHERE o.status = 'paid' AND r.status = 'approved' AND r.category = '$id'
                            GROUP BY r.id
                            ORDER BY o.date DESC
                            LIMIT 10"; // Limit to 10 reports for display
                        
                        $latestSalesResult = mysqli_query($con, $latestSalesQuery);

                        if ($latestSalesResult && mysqli_num_rows($latestSalesResult) > 0) {
                            while ($row = mysqli_fetch_assoc($latestSalesResult)) {
                                $report_id = $row['report_id'];
                                $title = $row['title'];
                                $description = $row['description'];
                                $price = $row['price'];
                                $image_path = $imagePath . $row['picture'];
                                $user = $row['display_name'];
                                $user_picture = $imagePath . $row['profile_picture'];
                                ?>
                                <div class="swiper-slide">
                                    <div class="card">
                                        <img src="<?php echo $image_path; ?>" class="card-img-top" alt="Product Image">
                                        <div class="card-body">
                                        <a href="product.php?id=<?php echo $report_id; ?>"><h5 class="text-bold"><?php echo $title; ?></h5></a>
                                            <p class="card-text"><?php echo $sitecurrency . $price; ?></p>
                                          
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <!-- Add Swiper Navigation -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <!-- Add Swiper Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
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
    function filterBySubcategory(subcategory) {
        const urlParams = new URLSearchParams(window.location.search);
        if (subcategory === "all") {
            urlParams.delete('subcategory'); // Remove subcategory filter if "Show All" is selected
        } else {
            urlParams.set('subcategory', subcategory); // Set the selected subcategory
        }
        window.location.search = urlParams.toString(); // Reload the page with updated query parameters
    }
</script>

<?php include "footer.php"; ?>