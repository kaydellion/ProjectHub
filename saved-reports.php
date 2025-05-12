<?php  include "header.php"; 
checkActiveLog($active_log);

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
$total_query = "SELECT COUNT(DISTINCT r.id) as total FROM 
".$siteprefix."reports r 
LEFT JOIN ".$siteprefix."wishlist w ON w.product = r.id 
LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id 
WHERE r.status = 'approved' AND w.user='$user_id' GROUP BY r.id";
$total_result = mysqli_query($con, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_reports = $total_row['total'] ?? 0;
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
            <div class="product-count" style="background-color: orange; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold;">
    Found <?php echo $report_count; ?> report(s)
</div>
            <div class="row">
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $report_id = $row['id'];
                        $alt_title = $row['alt_title'];
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
                        $slug =$alt_title;
                        include "product-card.php";
                    }
                } else {
                    debug('No reports found.');
                }
                ?>
            </div>   



<div class="justify-content-center pagination mt-1">
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