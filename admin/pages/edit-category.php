<?php include "header.php"; ?>

<?php
// Check if 'id' is provided in the URL
if (isset($_GET['id'])) {
    $category_id = intval($_GET['id']);
} else {
    // Redirect if no ID is provided
    header("Location: manage-category.php");
    exit;
}

// Fetch existing category (must be a main category with parent_id IS NULL)
$category_name = '';
$query = "SELECT category_name FROM {$siteprefix}categories WHERE id = $category_id AND parent_id IS NULL";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $category_name = $row['category_name'];
} else {
    // Category not found or not a main category, redirect back
    header("Location: manage-category.php");
    exit;
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">

                <p class="text-bold text-dark">Edit Category</p>

                <div class="form-group mb-3">
                    <label for="category_name">Category Name</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="category_name" 
                        name="category_name" 
                        placeholder="Enter category name" 
                        value="<?php echo htmlspecialchars($category_name); ?>" 
                        required
                    >
                </div>

                <p>
                    <button class="w-100 btn btn-primary" name="editCategory" value="edit-category">Update Category</button>
                </p>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
