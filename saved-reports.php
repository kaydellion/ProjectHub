<?php  include "header.php"; 


$limit = 16; // Number of reports per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT r.*, u.display_name, u.profile_picture, l.category_name AS category, sc.category_name AS subcategory, ri.picture 
FROM ".$siteprefix."reports r 
LEFT JOIN ".$siteprefix."wishlist w ON w.product = r.id 
LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id 
WHERE r.status = 'approved' AND w.user='$user_id' GROUP BY r.id ORDER BY r.id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $query);
$report_count = mysqli_num_rows($result);

// Get total number of reports
$total_query = "SELECT COUNT(*) as total FROM ".$siteprefix."reports WHERE status = 'approved'";
$total_result = mysqli_query($con, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_reports = $total_row['total'];
$total_pages = ceil($total_reports / $limit);
?>






<div class="container mt-5">
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>My Saved Reports</h3>
                <div class="subcategories">
                    <select id="subcategory-select" class="form-select">
                        <option value="">Filter by Subcategory</option>
                        <option value="all">Show All</option>
                        <?php
                        $subcat_query = "SELECT DISTINCT category_name AS subcategory 
                                         FROM ".$siteprefix."categories 
                                         WHERE parent_id = $id";
                        $subcat_result = mysqli_query($con, $subcat_query);
                        while ($subcat_row = mysqli_fetch_assoc($subcat_result)) {
                            echo '<option value="'.removeAllWhitespace($subcat_row['subcategory']).'">'.$subcat_row['subcategory'].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <p id="report-count">Found <?php echo $report_count; ?> report(s)</p>
            <div class="row">
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

                        include "product-card.php";
                    }
                } else {
                    debug('No reports found.');
                }
                ?>
            </div>



<div class="justify-content-center pagination">
    <?php if ($page > 1): ?>
        <a href="?id=<?php echo $id; ?>&page=<?php echo $page - 1; ?>" class="btn btn-primary">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?id=<?php echo $id; ?>&page=<?php echo $i; ?>" class="btn btn-secondary <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="?id=<?php echo $id; ?>&page=<?php echo $page + 1; ?>" class="btn btn-primary">Next</a>
    <?php endif; ?>
</div>


        </div>
    </div>
</div>














<?php include "footer.php"; ?>