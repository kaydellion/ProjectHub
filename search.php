<?php  include "header.php"; 
if (isset($_GET['searchterm'])) {
    $term = $_GET['searchterm'];
   } else {
header("Location: index.php");
}

$limit = 16; // Number of reports per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

$order_by = "r.id DESC"; // Default order
switch ($filter) {
    case 'low to high':
        $order_by = "r.price ASC";
        break;
    case 'high to low':
        $order_by = "r.price DESC";
        break;
    case 'newest':
        $order_by = "r.created_date DESC";
        break;
    case 'oldest':
        $order_by = "r.created_date ASC";
        break;
    case 'a-z':
        $order_by = "r.title ASC";
        break;
    case 'z-a':
        $order_by = "r.title DESC";
        break;
}

$query = "SELECT r.*, u.display_name, u.profile_picture, l.category_name AS category, sc.category_name AS subcategory, ri.picture 
FROM ".$siteprefix."reports r 
LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id 
WHERE r.status = 'approved' 
AND (r.title LIKE '%$term%' OR r.tags LIKE '%$term%' OR l.category_name LIKE '%$term%' OR sc.category_name LIKE '%$term%' OR u.display_name LIKE '%$term%') 
GROUP BY r.id ORDER BY $order_by LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $query);
//if (!$result) {die('Error in SQL query: ' . mysqli_error($con));}
$report_count = mysqli_num_rows($result);


// Get total number of reports
$total_query = "SELECT COUNT(DISTINCT r.id) as total
FROM ".$siteprefix."reports r 
LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id 
WHERE r.status = 'approved' 
AND (r.title LIKE '%$term%' OR r.tags LIKE '%$term%' OR l.category_name LIKE '%$term%' OR sc.category_name LIKE '%$term%' OR u.display_name LIKE '%$term%') 
GROUP BY r.id";
$total_result = mysqli_query($con, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_reports = $total_row['total'] ?? 0;
$total_pages = ceil($total_reports / $limit);
?>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Search for "<?php echo $term; ?>"</h3>
                <div class="filter">
                    <form id="filter-form" method="GET" action="search.php">
                        <input type="hidden" name="searchterm" value="<?php echo htmlspecialchars($term); ?>">
                        <select id="filter-select" name="filter" class="form-select" onchange="document.getElementById('filter-form').submit();">
                            <option value="">- Filter by -</option>
                            <option value="low to high">Price (low to high)</option>
                            <option value="high to low">Price (high to low)</option>
                            <option value="newest">Newest</option>
                            <option value="oldest">Oldest</option>
                            <option value="a-z">Name (A-Z)</option>
                            <option value="z-a">Name (Z-A)</option>
                        </select>
                    </form>
                </div>
            </div>
            <p id="report-count">Found <?php echo $report_count; ?> report(s)</p>
            <div class="row mb-3">
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
                        $methodology = $row['methodology'];
                        $selected_education_level = $row['education_level'] ?? '';
                        $selected_resource_type = $row['resource_type'] ?? '';
                        $selected_resource_type_array = explode(',', $selected_resource_type);
                        $slug = strtolower(str_replace(' ', '-', $title));
                        include "product-card.php";
                    }
                } else {
                    debug('No reports found.');
                }
                ?>
            </div>



    <div class="justify-content-center pagination">
    <?php if ($page > 1): ?>
        <a href="?searchterm=<?php echo $term; ?>&page=<?php echo $page - 1; ?>" class="btn btn-primary">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?searchterm=<?php echo $term; ?>&page=<?php echo $i; ?>" class="btn btn-secondary <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="?searchterm=<?php echo $term; ?>&page=<?php echo $page + 1; ?>" class="btn btn-primary">Next</a>
    <?php endif; ?>
</div>


        </div>
    </div>
</div>











<?php include "footer.php"; ?>