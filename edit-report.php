<?php include "header.php"; 

$report_id = $_GET['report'] ?? null;
if (!$report_id) {
  header("Location: reports.php");
  exit();
}
 
$query = "SELECT r.*, u.display_name, l.category_name AS categoryname, sc.category_name AS subcategoryname 
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
    $preview = $row['preview'];
    $table_content = $row['table_content'];
    $category = $row['categoryname'];
    $subcategory = $row['subcategoryname'];
    $category_id = $row['category'];
    $subcategory_id = $row['subcategory'];
    $pricing = $row['pricing'];
    $price = $row['price'];
    $tags = $row['tags'];
    $loyalty = $row['loyalty'];
    $user = $row['display_name'];
    $created_date = $row['created_date'];
    $updated_date = $row['updated_date'];
    $status = $row['status'];
    $methodology = $row['methodology'];
    $answer = $row['answer'] ?? '';

$selected_education_level = $row['education_level'] ?? '';
$selected_resource_type = explode(',', $row['resource_type'] ?? ''); // assuming stored as comma-separated
$selected_years = explode(',', $row['year_of_study'] ?? ''); // assuming stored as comma-separated
$chapter_count = $row['chapter'] ?? '';
} else {
    die('Report not found.');
}

$yearsOfStudy = [
    "Post UTME",
    "Year 1",
    "Year 2",
    "Year 3",
    "Year 4",
    "Year 5",
    "Year 6",
    "Post Graduation / Work"
  ];

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
                         $sql3 = "SELECT * FROM ".$siteprefix."reports_images WHERE report_id = '$report_id'";   
                         $sql4 = mysqli_query($con, $sql3);
                         if (!$sql4) {die("Query failed: " . mysqli_error($con)); }
                         while ($image_row = mysqli_fetch_array($sql4)) {
                               echo '<div class="image-preview">';
                               echo '<img class="preview-image" src="../../uploads/' .$image_row['picture'] . '" alt="Report Image">';
                               echo '<button type="button" class="delete-btn delete-image" data-image-id="' .$image_row['id'] . '">X</button>';
                               echo '</div>';
                           }
                       
                        ?>
                        </div>
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
                    <div class="mb-3">
                          <label class="form-label" for="basic-default-message">Preview Content</label>
                          <textarea id="basic-default-message" name="preview" class="form-control editor" placeholder="Write required information or details here...."><?php echo $preview; ?></textarea>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="basic-default-message">Table of Contents</label>
                          <textarea id="basic-default-message" name="table_content" class="form-control editor" placeholder="Write required information or details here...."><?php echo $table_content; ?></textarea>
                        </div>


                        <div class="mb-3">
                          <label class="form-label" for="basic-default-message">Methodology</label>
                          <textarea id="basic-default-message" name="methodology" class="form-control editor" placeholder="Write required information or details here...."><?php echo $methodology; ?></textarea>
                        </div>

                    <h6>Field Of Study: Select the industry or field where this template/model is most applicable</h6>
                    <div class="mb-3">
                        <select class="form-select" name="category" aria-label="Default select example" required>
                            <option selected value="<?php echo $category_id; ?>"><?php echo $category; ?></option>
                            <?php
                            $sql = "SELECT * FROM " . $siteprefix . "categories";
                            $sql2 = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_array($sql2)) {
                                echo '<option value="' . $row['id'] . '">' . $row['category_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3" id="subcategory-container" style="display:block;">
                        <select class="form-select" name="subcategory" id="subcategory-select" required>
                            <option selected value="<?php echo $subcategory_id; ?>"><?php echo $subcategory; ?></option>
                        </select>
                    </div>

<!-- Education Level -->
<div class="mb-3">
<select name="education_level" class="form-control" required>
    <option value="">-- Select Education Level --</option>
    <?php $sql = "SELECT * FROM  " . $siteprefix . "education_levels ORDER BY is_new DESC, name ASC";
$result = $con->query($sql);
while ($row = $result->fetch_assoc()): ?>
        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $selected_education_level) echo 'selected'; ?>>
            <?php echo $row['name']; ?>
        </option>
    <?php endwhile; ?>
</select>
</div>

<!-- Resource Type -->
<div class="mb-3">
<label class="form-label" for="resourceType">Resource Type</label>
<select name="resource_type[]" id="resourceType" multiple class="form-select mb-4 select-multiple"  onchange="togglePast()"  required>
<option value="">-- Select Resource Type --</option>
<?php
$sql = "SELECT * FROM " . $siteprefix . "resource_types ORDER BY parent_id ASC, name ASC";
$result = $con->query($sql);
$resources = [];
while ($row = $result->fetch_assoc()) {
    $resources[$row['parent_id']][] = $row;
}
foreach ($resources[NULL] as $parent) {
    echo '<option disabled>' . $parent['name'] . '</option>';
    if (!empty($resources[$parent['id']])) {
        foreach ($resources[$parent['id']] as $child) {
            $selected = in_array($child['id'], $selected_resource_type ?? []) ? 'selected' : '';
            echo '<option value="' . $child['id'] . '" ' . $selected . '> &nbsp;&nbsp;&nbsp; ' . $child['name'] . '</option>';
        }
    }
}
?>
</select>
</div>

<div class="mb-3" id="past-field" style="display:none;">
<label class="form-label" for="past">Does the resource have answer?</label>
<select name="answer" class="form-control"required>
<option value="Yes" <?php echo ($answer == 'Yes') ? 'selected' : ''; ?>>Yes</option>
<option value="No" <?php echo ($answer == 'No') ? 'selected' : ''; ?>>No</option>
</select>
</div>

<!-- Year of Study -->
<div class="mb-3">
    <label><strong>What Year of Study Does the Resource Apply:</strong></label><br>
    <select name="year_of_study[]" multiple class="form-select select-multiple" required>
        <?php foreach ($yearsOfStudy as $year): ?>
            <?php
            $selected = in_array($year, $selected_years) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($year) . '" ' . $selected . '>' . htmlspecialchars($year) . '</option>';
            ?>
        <?php endforeach; ?>
    </select>
</div> 


<!-- Chapter Input -->
<div class="mb-3">
<label class="form-label" for="chapter">Number of Chapters</label>
<input type="number" id="chapter" name="chapter" class="form-control" step="1" min="1" required value="<?= htmlspecialchars($chapter_count) ?>">
</div>


                    <h6>Pricing and File Upload</h6>
                    <div class="mb-3">
                        <label class="form-label" for="pricing-type">Pricing Type</label>
                        <select id="pricing-type" name="pricing" class="form-control" onchange="togglePrice()" required>
                            <option value="free" <?php echo ($pricing == 'free') ? 'selected' : ''; ?>>Free</option>
                            <option value="paid" <?php echo ($pricing == 'paid') ? 'selected' : ''; ?>>Paid</option>
                            <option value="donation" <?php echo ($pricing == 'donation') ? 'selected' : ''; ?>>Donation</option>
                        </select>
                    </div>
                    <div class="mb-3" id="price-field" style="display:<?php echo ($pricing == 'paid') ? 'block' : 'none'; ?>;">
                        <label class="form-label" for="course-price">Price</label>
                        <input type="number" id="course-price" name="price" class="form-control" step="0.01" value="<?php echo $price; ?>">
                    </div>


<div class="mb-3">
<label>Click to delete uploaded documents</label>
<?php 
$sql = "SELECT * FROM ".$siteprefix."reports_files WHERE report_id = '$report_id'";
$sql2 = mysqli_query($con, $sql);
if (!$sql2) {die("Query failed: " . mysqli_error($con)); }
while ($row = mysqli_fetch_array($sql2)) {
    $file_id = $row['id'];
    $file_title = $row['title'];
    $file_pages = $row['pages'];
    $file_updated_at = $row['updated_at'];
    $file_extension = getFileExtension($file_title);
?>
<span class="file-preview">
<input type="radio" class="btn-check" value="<?php echo $file_id; ?>" name="btnradio" id="btnradio<?php echo $file_id; ?>" autocomplete="off">
<label class="btn btn-outline-primary deletefile" data-file-id="<?php echo $file_id; ?>" for="btnradio<?php echo $file_id; ?>"><?php echo $file_extension;?> (p.<?php echo $file_pages;?>)</label>
</span>
<?php } ?></div>


                        <div class="mb-3">
                        <label for="documentSelect" class="form-label">Upload New Document Types:</label>
                        <select class="form-select select-multiple" id="documentSelect" multiple  onchange="handleDocumentSelect(this)">
                            <option value="word">Word Document (.doc, .docx)</option>
                            <option value="excel">Excel Spreadsheet (.xls, .xlsx)</option>
                            <option value="powerpoint">PowerPoint Presentation (.ppt, .pptx)</option>
                            <option value="pdf">PDF Document (.pdf)</option>
                            <option value="text">Text File (.txt)</option>
                            <option value="zip">Zip File (.zip)</option>
                        </select>
                    </div>
                    <div id="pageInputs"></div>
                    <h6>Additional Information</h6>
                    <div class="mb-3">
                        <label class="form-label" for="course-tags">Tags & Keywords</label>
                        <input type="text" id="course-tags" name="tags" class="form-control" value="<?php echo $tags; ?>" required>
                    </div>
                    <button type="submit" name="update-report" value="course" class="btn btn-primary w-100">Update Report</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
const selectedSubcategoryId = <?= json_encode($subcategory_id ?? null) ?>;
function fetchSubcategories(parentId) {
    let subSelect = document.getElementById('subcategory-container');
    let subcategorySelect = document.getElementById('subcategory-select');

    if (!parentId) {
        subSelect.style.display = 'none';
        return;
    }

    fetch(`get_subcategories.php?parent_id=${parentId}`)
    .then(response => response.json())
    .then(data => {
        if (data.length > 0) {
            subcategorySelect.innerHTML = '<option disabled>- Select Subcategory -</option>';
            data.forEach(cat => {
                const selected = (parseInt(cat.id) === parseInt(selectedSubcategoryId)) ? 'selected' : '';
                subcategorySelect.innerHTML += `<option value="${cat.s}" ${selected}>${cat.title}</option>`;
            });
            subSelect.style.display = 'block';
        } else {
            subSelect.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error fetching subcategories:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.querySelector('select[name="category"]');

    // Trigger fetch if there's already a selected value (e.g. during editing)
    if (categorySelect.value) {
        fetchSubcategories(categorySelect.value);
    }

    // Attach change event
    categorySelect.addEventListener('change', function() {
        fetchSubcategories(this.value);
    });
});

document.addEventListener('DOMContentLoaded', function() {
    if (typeof togglePast === 'function') {
        togglePast();
    }
});
</script>
<?php include "footer.php"; ?>
       