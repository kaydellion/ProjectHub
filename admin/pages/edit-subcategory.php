<?php include "header.php"; ?>

<?php
// Check if 'id' is provided in the URL
if (isset($_GET['id'])) {
    $subcategory_id = intval($_GET['id']);
} else {
    // Redirect if no ID is provided
    header("Location: manage-subcategory.php");
    exit;
}

// Initialize variables
$parentId = '';
$subCategoryName = '';

// Fetch existing subcategory data
$fetchQuery = "SELECT category_name, parent_id FROM {$siteprefix}categories WHERE id = $subcategory_id";
$result = mysqli_query($con, $fetchQuery);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $subCategoryName = $row['category_name'];
    $parentId = $row['parent_id'];
} else {
    // Redirect if subcategory not found
    header("Location: manage-subcategory.php");
    exit;
}
?>

<!-- Display Sub-Category Details (no form submission) -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
        <form method="POST">
            <p class="text-bold text-dark">Sub-Category Details</p>

            <form method="POST">
    <input type="hidden" name="subcategory_id" value="<?php echo $subcategory_id; ?>">

    <p class="text-bold text-dark">Edit Sub-Category</p>

    <div class="form-group mb-3">
        <label for="parentId">Select Parent Category</label>
        <select class="form-control" id="parentId" name="parentId">
            <option value="">-- Select Category --</option>
            <?php
            $mainCategories = mysqli_query($con, "SELECT id, category_name FROM {$siteprefix}categories WHERE parent_id IS NULL ORDER BY category_name ASC");
            while ($cat = mysqli_fetch_assoc($mainCategories)) {
                $selected = ($cat['id'] == $parentId) ? 'selected' : '';
                echo "<option value='{$cat['id']}' $selected>" . htmlspecialchars($cat['category_name']) . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="subCategoryName">Sub-Category Name</label>
        <input 
            type="text" 
            class="form-control" 
            id="subCategoryName" 
            name="subCategoryName" 
            placeholder="Enter sub-category name" 
            value="<?php echo htmlspecialchars($subCategoryName); ?>" 
            required
        >
    </div>

    <p>
        <button class="w-100 btn btn-primary" name="editSubCategory" value="edit-subcategory">Update Sub-Category</button>
    </p>
</form>

        </div>
    </div>
</div>

<?php include "footer.php"; ?>
