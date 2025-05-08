<?php include "header.php"; ?>


<div class="container-xxl  py-5">
<!-- Basic Layout -->
               <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h4 class="mb-0">Upload New Report</h4>
                    </div>
                    <div class="card-body">
                      <form method="POST" enctype="multipart/form-data">

                      <h6>Basic Information</h6>

                      <div class="mb-3">
                      <input type="file" class="form-control" id="imageInput" name="images[]" multiple accept="image/*">
                      <div id="preview" class="preview-container"></div>
                      </div>
              
                      <div class="mb-3">
                          <label class="form-label" for="course-id">Report ID</label>
                            <input type="text" id="course-id" name="id" class="form-control" value="PH<?php echo sprintf('%06d', rand(1, 999999)); ?>" readonly required>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">Title</label>
                          <input type="text" class="form-control" name="title" id="basic-default-fullname" placeholder="Learning loops" required>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="basic-default-message">Description</label>
                          <textarea id="basic-default-message" name="description" class="form-control editor" placeholder="Write required information or details here...."></textarea>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="basic-default-message">Preview Content</label>
                          <textarea id="basic-default-message" name="preview" class="form-control editor" placeholder="Write required information or details here...."></textarea>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="basic-default-message">Table of Contents</label>
                          <textarea id="basic-default-message" name="table_content" class="form-control editor" placeholder="Write required information or details here...."></textarea>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="basic-default-message">Methodology</label>
                          <textarea id="basic-default-message" name="methodology" class="form-control editor" placeholder="Write required information or details here...."></textarea>
                        </div>
                        <input type="hidden" name="user" value="<?php echo $user_id; ?>">

                        <h6>Field Of Study: Select the industry or field where this template/model is most applicable</h6>
                        <div class="mb-3">
                        <select class="form-select" name="category" aria-label="Default select example" required>
                          <option selected>- Select Category -</option>
                          <?php
                     $sql = "SELECT * FROM " . $siteprefix . "categories WHERE parent_id IS NULL ";
                     $sql2 = mysqli_query($con, $sql);
                     while ($row = mysqli_fetch_array($sql2)) {
                     echo '<option value="' . $row['id'] . '">' . $row['category_name'] . '</option>'; }?>
                        </select>
                        </div>

                        <div class="mb-3" id="subcategory-container" style="display:none;">
                          <select class="form-select" name="subcategory" id="subcategory-select" required>
                            <option selected>- Select Subcategory -</option>
                          </select>
                        </div>



<?php
$sql = "SELECT * FROM  " . $siteprefix . "education_levels ORDER BY is_new DESC, name ASC";
$result = $con->query($sql);
?>
<div class="mb-3">
<select name="education_level" class="form-control" required>
    <option value="">-- Select Education Level --</option>
    <?php while ($row = $result->fetch_assoc()): ?>
        <option value="<?php echo $row['id']; ?>">
            <?php echo $row['name']; ?>
        </option>
    <?php endwhile; ?>
</select>
</div>


<?php
// Fetch all
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
$sql = "SELECT * FROM " . $siteprefix . "resource_types ORDER BY parent_id ASC, name ASC";
$result = $con->query($sql);
$resources = [];
while ($row = $result->fetch_assoc()) {
    $resources[$row['parent_id']][] = $row;
}
?>
<div class="mb-3">
<label class="form-label" for="resourceType">Resource Type</label>
<select name="resource_type[]" id="resourceType" multiple class="form-select mb-4 select-multiple"  onchange="togglePast()"  required>
<option value="">-- Select Resource Type --</option>
    <?php
    foreach ($resources[NULL] as $parent) {
        echo '<option disabled>' . $parent['name'] . '</option>';
        if (!empty($resources[$parent['id']])) {
            foreach ($resources[$parent['id']] as $child) {
                echo '<option value="' . $child['id'] . '"> &nbsp;&nbsp;&nbsp; ' . $child['name'] . '</option>';
            }
        }
    }
    ?>
</select>
</div>

<div class="mb-3" id="past-field" style="display:none;">
<label class="form-label" for="past">Does the resource have answer?</label>
<select name="answer" class="form-control"required>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</div>

<div class="mb-3">
<label><strong>What Year of Study Does the Resource Apply:</strong></label><br>
<select name="year_of_study[]" multiple class="form-select mb-4 select-multiple" required>
    <?php foreach ($yearsOfStudy as $year): ?>
    <option value="<?= htmlspecialchars($year) ?>"><?= $year ?></option>
    <?php endforeach; ?>
</select></div>

<div class="mb-3">
<label class="form-label" for="chapter">Number of Chapters</label>
<input type="number" id="chapter" name="chapter" class="form-control" step="1" min="1" required>
</div>



                        <h6>Pricing and File Upload</h6>
                        <div class="mb-3">
                          <label class="form-label" for="pricing-type">Pricing Type</label>
                          <select id="pricing-type" name="pricing" class="form-control" onchange="togglePrice()" required>
                            <option value="free">Free</option>
                            <option value="paid">Paid</option>
                           
                          </select>
                        </div>

                        <div class="mb-3" id="price-field" style="display:none;">
                          <label class="form-label" for="course-price">Price</label>
                          <p class="text-muted">Note: Each document type attracts a portion of the total price. For example, if your product includes both a Word document and an Excel spreadsheet,
                          and you intend to sell the bundle for ₦ 2,000, enter ₦ 1,000 as the price for each document type here.</p>
                          <input type="number" id="course-price" name="price" class="form-control" step="0.01">
                        </div>

          <div class="mb-3">
        <label for="documentSelect" class="form-label ">Select Document Types:</label>
        <select class="form-select select-multiple" id="documentSelect"  multiple required  onchange="handleDocumentSelect(this)">
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
                          <input type="text" id="course-tags" name="tags" class="form-control" placeholder="Separate tags with commas" required>
                        </div>

                        <button type="submit" name="addcourse" value="course" class="btn btn-primary w-100">Add Report</button>
                        <button type="submit" name="saveReport" value="save" class="btn btn-secondary w-100 mb-2">Save As Draft</button>

                      
                        
                      </form>
                    </div>
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
                              console.log('Received data:', data);
                              if (data.length > 0) {
                                subcategorySelect.innerHTML = '<option selected>- Select Subcategory -</option>';
                                data.forEach(cat => {
                                  console.log('Processing category:', cat);
                                  subcategorySelect.innerHTML += `<option value="${cat.s}">${cat.title}</option>`;
                                });
                                subSelect.style.display = 'block';
                              } else {
                                console.log('No subcategories found');
                                subSelect.style.display = 'none';
                              }
                            })
                            .catch(error => {
                              console.error('Error fetching subcategories:', error);
                            });
                        });
                        </script>
            <?php include "footer.php"; ?>
