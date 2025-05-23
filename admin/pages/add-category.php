
<?php include "header.php"; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <p class="text-bold text-dark">Create a New Category</p>

                <div class="form-group mb-3">
                    <label for="categoryName">Category Name</label>
                    <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Enter category name" required>
                </div>

                <p><button class="w-100 btn btn-primary" name="addCategory" value="add-category">Create Category</button></p>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>