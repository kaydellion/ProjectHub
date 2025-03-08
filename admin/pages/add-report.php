<?php include "header.php"; ?>

<div class="container-xxl flex-grow-1 container-p-y">

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
                          <textarea id="basic-default-message" name="description" class="form-control editor" placeholder="This course is a course for ..."></textarea>
                        </div>
 

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

                        <div class="mb-5" id="subcategory-container" style="display:none;">
                          <select class="form-select" name="subcategory" id="subcategory-select" required>
                            <option selected>- Select Subcategory -</option>
                          </select>
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
        </select>
      </div>
      
      <div id="pageInputs"></div>

                        <h6>Additional Information</h6>

                        <div class="mb-3">
                          <label class="form-label" for="course-tags">Tags & Keywords</label>
                          <input type="text" id="course-tags" name="tags" class="form-control" placeholder="Separate tags with commas" required>
                        </div>

                        <?php if($user_type === 'admin'): ?>
                        <div class="mb-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="loyalty" name="loyalty">
                            <label class="form-check-label" for="loyalty">List under our Loyalty Program</label>
                          </div>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3">
                          <label class="form-label" for="status-type">Approval Status</label>
                          <select id="status-type" name="status" class="form-control" required>
                            <option value="pending" selected>Pending</option>
                            <option value="approved">Approved</option>
                          </select>
                        </div>
                      
                        <button type="submit" name="addcourse" value="course" class="btn btn-primary w-100">Add Report</button>
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
