<?php 
include "header.php"; 

// Check if the education level is passed in the URL
if (isset($_GET['education_levels'])) {
    $education_level_id = intval($_GET['education_levels']); // Sanitize input

    // Fetch the education level details
    $sql = "SELECT * FROM " . $siteprefix . "education_levels WHERE id = '$education_level_id'";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $education_level_title = $row['name'];
    } else {
        // Redirect if the education level is not found
        header("Location: index.php");
        exit();
    }
} else {
    // Redirect if no education level is passed
    header("Location: index.php");
    exit();
}

// Pagination setup
$limit = 16;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Sorting setup
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'relevance';
$order_by = "r.id DESC";
if ($sort === 'price_high') {
    $order_by = "r.price DESC";
} elseif ($sort === 'price_low') {
    $order_by = "r.price ASC";
}

// Fetch reports for the selected education level
$query = "
    SELECT r.*, 
           u.display_name, 
           u.profile_picture, 
           ri.picture, 
           l.category_name AS category, 
           sc.category_name AS subcategory
    FROM {$siteprefix}reports r 
    LEFT JOIN {$siteprefix}users u ON r.user = u.s 
    LEFT JOIN {$siteprefix}reports_images ri ON r.id = ri.report_id 
    LEFT JOIN {$siteprefix}categories l ON r.category = l.id 
    LEFT JOIN {$siteprefix}categories sc ON r.subcategory = sc.id 
    WHERE r.status = 'approved' 
      AND FIND_IN_SET('$education_level_id', r.education_level)
    GROUP BY r.id 
    ORDER BY $order_by 
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($con, $query);
$report_count = mysqli_num_rows($result);

// Count total reports for pagination
$total_query = "
    SELECT COUNT(*) as total 
    FROM {$siteprefix}reports r 
    WHERE r.status = 'approved' 
      AND FIND_IN_SET('$education_level_id', r.education_level)
";
$total_result = mysqli_query($con, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_reports = $total_row['total'];
$total_pages = ceil($total_reports / $limit);
?>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col-lg-12">

            <!-- Education Level Title -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><?php echo htmlspecialchars($education_level_title); ?></h3>
            </div>

            <!-- Report Count and Sorting -->
            <div class="d-flex justify-content-between align-items-center">
                <div class="product-count" style="background-color: orange; color: white; padding: 5px 10px; border-radius: 5px;">
                    Found <?php echo $report_count; ?> report(s)
                </div>

                <div class="sort-by">
                    <label for="sort-select" class="me-2">Sort By:</label>
                    <select id="sort-select" class="form-select" onchange="sortReports(this.value)">
                        <option value="relevance" <?php if ($sort === 'relevance') echo 'selected'; ?>>Relevance</option>
                        <option value="price_high" <?php if ($sort === 'price_high') echo 'selected'; ?>>Price - High To Low</option>
                        <option value="price_low" <?php if ($sort === 'price_low') echo 'selected'; ?>>Price - Low To High</option>
                    </select>
                </div>
            </div>

            <!-- Reports List -->
            <div class="row mt-3">
                <?php
                if ($result && $report_count > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $report_id = $row['id'];
                        $title = $row['title'];
                        $alt_title = $row['alt_title'];
                        $description = $row['description'];
                        $pricing = $row['pricing'];
                        $price = $row['price'];
                        $tags = $row['tags'];
                        $loyalty = $row['loyalty'];
                        $user = $row['display_name'];
                        $category = $row['category'];
                        $subcategory = $row['subcategory'];
                        $user_picture = $imagePath . $row['profile_picture'];
                        $created_date = $row['created_date'];
                        $updated_date = $row['updated_date'];
                        $status = $row['status'];
                        $image_path = $imagePath . $row['picture'];
                        $selected_education_level = $row['education_level'] ?? '';
                        $selected_resource_type = $row['resource_type'] ?? '';
                        $year_of_study = $row['year_of_study'] ?? '';
                        $slug = $alt_title;

                        include "product-card.php";
                    }
                } else {
                    echo "<p>No reports found for this education level.</p>";
                }
                ?>
            </div>

            <!-- Pagination -->
            <div class="justify-content-center pagination">
                <?php if ($page > 1): ?>
                    <a href="?education_level=<?php echo urlencode($education_level_id); ?>&page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>" class="btn btn-primary">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?education_level=<?php echo urlencode($education_level_id); ?>&page=<?php echo $i; ?>&sort=<?php echo $sort; ?>" class="btn btn-secondary <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?education_level=<?php echo urlencode($education_level_id); ?>&page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>" class="btn btn-primary">Next</a>
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