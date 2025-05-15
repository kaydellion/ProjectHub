

<?php include "header.php"; ?>

<!-- Sub-Category Form -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <p class="text-bold text-dark">Add a New Sub-Category</p>

                <div class="form-group mb-3">
                    <label for="parentId">Select Parent Category</label>
                    <select class="form-control" id="parentId" name="parentId" required>
                        <option value="">-- Select Category --</option>
                        <?php
                        $result = mysqli_query($con, "SELECT id, category_name FROM {$siteprefix}categories WHERE parent_id IS NULL");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['id']}'>" . htmlspecialchars($row['category_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="subCategoryName">Sub-Category Name</label>
                    <input type="text" class="form-control" id="subCategoryName" name="subCategoryName" placeholder="Enter sub-category name" required>
                </div>

                <p><button class="w-100 btn btn-primary" name="addSubCategory" value="add-subcategory">Add Sub-Category</button></p>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>