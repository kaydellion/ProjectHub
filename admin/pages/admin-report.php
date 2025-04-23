
<?php include "header.php"; ?>


<div class="container-xxl flex-grow-1 container-p-y">

              <!-- Hoverable Table rows -->
              <div class="card">
                <h5 class="card-header">Manage Reports</h5>
                <div class="table-responsive text-nowrap ">
                  <table class="table table-hover">
                  <thead>
                  <tr>
                  <th>S/N</th>
                  <th>Report Title</th>
                  <th>Category</th>
                  <th>Subcategory</th>
                  <th>Pricing</th>
                  <th>Price</th>
                  <th>Tags</th>
                  <th>Loyalty</th>
                  <th>Uploaded by</th>
                  <th>Created Date</th>
                  <th>Last Updated</th>
                  <th>Status</th>
                  <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                <?php 
              
               $query = "SELECT r.*, u.display_name, l.category_name AS category, sc.category_name AS subcategory 
                         FROM ".$siteprefix."reports r 
                         LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
                         LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
                         LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
                         WHERE r.user = '$id'";

                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Query Failed: ' . mysqli_error($con));
                }
                $result = mysqli_query($con, $query);
                $i = 1;
                while($row = mysqli_fetch_assoc($result)) {
                $report_id = $row['id'];
                $report_row = $row['s'];
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
                ?>
                  <tr>
                  <td><strong><?php echo $i; ?></strong></td>
                  <td><?php echo $title; ?></td>
                  <td><?php echo $category; ?></td>
                  <td><?php echo $subcategory; ?></td>
                  <td><?php echo $pricing; ?></td>
                  <td><?php echo $price; ?></td>
                  <td><?php echo $tags; ?></td>
                  <td><?php echo $loyalty; ?></td>
                  <td><?php echo $user; ?></td>
                  <td><?php echo $created_date; ?></td>
                  <td><?php echo $updated_date; ?></td>
                  <td><span class="badge bg-label-<?php echo getBadgeColor($status); ?> me-1"><?php echo $status; ?></span></td>
                  <td>
                    <div class="dropdown">
                    <button type="button" class="btn btn-primary text-small dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>Manage
                    </button>
                    <div class="dropdown-menu">
                    <a class="dropdown-item" href="edit-report.php?report=<?php echo $report_id; ?>"><i class="bx bx-edit-alt me-1"></i> Edit Report</a>
                    <a class="dropdown-item delete" href="delete.php?action=delete&table=reports&item=<?php echo $report_row; ?>&page=<?php echo $current_page; ?>"><i class="bx bx-trash me-1"></i> Delete</a>
                    </div>
                    </div>
                  </td>
                  </tr>
                  <?php $i++; } ?>
                  </tbody>
                  </table>
                </div>
                </div>
              </div>
              <!--/ Hoverable Table rows -->

            

            </div>




<?php include "footer.php"; ?>
