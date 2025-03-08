<?php include "header.php"; 

$report_id = $_GET['report'] ?? null;
if (!$report_id) {
  header("Location: reports.php");
  exit();
}
 
$query = "SELECT r.*, u.display_name, l.category_name AS category, sc.category_name AS subcategory 
  FROM ".$siteprefix."reports r 
  LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
  LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
  LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
  WHERE r.id = '$report_id'";
$result = mysqli_query($con, $query);
if (!$result) {
    die('Query Failed: ' . mysqli_error($con));
}
$row = mysqli_fetch_assoc($result);
if ($row) {
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
    $created_date = $row['created_date'];
    $updated_date = $row['updated_date'];
    $status = $row['status'];
} else {
    die('Report not found.');
}
?>


<div class="container-xxl flex-grow-1 container-p-y">
<!-- Basic Layout -->
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Report</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <h6>Basic Information</h6>
                    <div class="mb-3">
                        <p>Current Images</p>
                        <div id="preview1" class="preview-container">
                         <?php
                        // Fetch existing images
                        $image_query = "SELECT * FROM ".$siteprefix."reports_images WHERE report_id = ?";
                        $image_stmt = $con->prepare($image_query);
                        $image_stmt->bind_param("i", $report_id);
                        $image_stmt->execute();
                        $image_result = $image_stmt->get_result();
                        while ($image_row = $image_result->fetch_assoc()) {
                            echo '<div class="image-preview">';
                            echo '<img  class="preview-image" src="../../uploads/'.$image_row['picture'].'" alt="Report Image">';
                            echo '<button type="button" class="delete-btn delete-image" data-image-id="'.$image_row['id'].'">X</button>';
                            echo '</div>';
                        }
                        $image_stmt->close();
                        ?></div>
                        <label class="form-label" for="imageInput">Upload New Images</label>
                        <input type="file" class="form-control" id="imageInput" name="images[]" multiple accept="image/*">
                        <div id="preview" class="preview-container"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="course-id">Report ID</label>
                        <input type="text" id="course-id" name="id" class="form-control" value="<?php echo $report_id; ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-fullname">Title</label>
                        <input type="text" class="form-control" name="title" id="basic-default-fullname" value="<?php echo $title; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-message">Description</label>
                        <textarea id="basic-default-message" name="description" class="form-control editor"><?php echo $description; ?></textarea>
                    </div>
                    <h6>Field Of Study: Select the industry or field where this template/model is most applicable</h6>
                    <div class="mb-3">
                        <select class="form-select" name="category" aria-label="Default select example" required>
                            <option selected value="<?php echo $category; ?>"><?php echo $category; ?></option>
                            <?php
                            $sql = "SELECT * FROM " . $siteprefix . "categories";
                            $sql2 = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_array($sql2)) {
                                echo '<option value="' . $row['id'] . '">' . $row['category_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-5" id="subcategory-container" style="display:block;">
                        <select class="form-select" name="subcategory" id="subcategory-select" required>
                            <option selected value="<?php echo $subcategory; ?>"><?php echo $subcategory; ?></option>
                        </select>
                    </div>
                    <h6>Pricing and File Upload</h6>
                    <div class="mb-3">
                        <label class="form-label" for="pricing-type">Pricing Type</label>
                        <select id="pricing-type" name="pricing" class="form-control" onchange="togglePrice()" required>
                            <option value="free" <?php echo ($pricing == 'free') ? 'selected' : ''; ?>>Free</option>
                            <option value="paid" <?php echo ($pricing == 'paid') ? 'selected' : ''; ?>>Paid</option>
                        </select>
                    </div>
                    <div class="mb-3" id="price-field" style="display:<?php echo ($pricing == 'paid') ? 'block' : 'none'; ?>;">
                        <label class="form-label" for="course-price">Price</label>
                        <input type="number" id="course-price" name="price" class="form-control" step="0.01" value="<?php echo $price; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="documentSelect" class="form-label">Select Document Types:</label>
                        <select class="form-select select-multiple" id="documentSelect" multiple required onchange="handleDocumentSelect(this)">
                            <option value="word">Word Document (.doc, .docx)</option>
                            <option value="excel">Excel Spreadsheet (.xls, .xlsx)</option>
                            <option value="powerpoint">PowerPoint Presentation (.ppt, .pptx)</option>
                            <option value="pdf">PDF Document (.pdf)</option>
                            <option value="text">Text File (.txt)</option>
                        </select>
                    </div>
                    <div id="pageInputs1"></div>
                    <div id="pageInputs"></div>
                    <h6>Additional Information</h6>
                    <div class="mb-3">
                        <label class="form-label" for="course-tags">Tags & Keywords</label>
                        <input type="text" id="course-tags" name="tags" class="form-control" value="<?php echo $tags; ?>" required>
                    </div>
                    <?php if ($user_type === 'admin'): ?>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="loyalty" name="loyalty" <?php echo ($loyalty) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="loyalty">List under our Loyalty Program</label>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                          <label class="form-label" for="status-type">Approval Status</label>
                          <select id="status-type" name="status" class="form-control" required>
                            <option value="pending" <?php echo ($status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo ($status == 'approved') ? 'selected' : ''; ?>>Approved</option>
                          </select>
                        </div>
                    <button type="submit" name="addcourse" value="course" class="btn btn-primary w-100">Update Report</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.querySelector('select[name="category"]').addEventListener('change', function() {
    let parentId = this.value;
    let subSelect = document.getElementById('subcategory-container');
    let subcategorySelect = document.getElementById('subcategory-select');
    fetch(`get_subcategories.php?parent_id=${parentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                subcategorySelect.innerHTML = '<option selected>- Select Subcategory -</option>';
                data.forEach(cat => {
                    subcategorySelect.innerHTML += `<option value="${cat.id}">${cat.category_name}</option>`;
                });
                subSelect.style.display = 'block';
            } else {
                subSelect.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching subcategories:', error);
        });
});
</script>
            <?php include "footer.php"; ?>
       