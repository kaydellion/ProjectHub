
<?php include "header.php"; ?>

<div class="container-xxl flex-grow-1 container-p-y">

  <!-- Hoverable Table rows -->
  <div class="card">
    <h5 class="card-header">Manage Subcategories</h5>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Subcategory Name</th>
            <th>Parent Category</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <?php
        $query = "
        SELECT 
          sc.id AS sub_id, 
          sc.category_name AS subcategory, 
          pc.category_name AS parent_category
        FROM {$siteprefix}categories sc
        INNER JOIN {$siteprefix}categories pc ON sc.parent_id = pc.id
        WHERE sc.parent_id IS NOT NULL
        ORDER BY pc.category_name ASC, sc.category_name ASC
    ";
    
          $result = mysqli_query($con, $query);
          if (!$result) {
              die('Query Failed: ' . mysqli_error($con));
          }
          $i = 1;
          while ($row = mysqli_fetch_assoc($result)) {
              $subcategory_id = $row['sub_id'];
              $subcategory = $row['subcategory'];
              $parent_category = $row['parent_category'];
          ?>
            <tr>
              <td><strong><?php echo $i; ?></strong></td>
              <td><?php echo $subcategory; ?></td>
              <td><?php echo $parent_category; ?></td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn btn-primary text-small dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i> Manage
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="edit-subcategory.php?id=<?php echo $subcategory_id; ?>">
                      <i class="bx bx-edit-alt me-1"></i> Edit
                    </a>
                    <a class="dropdown-item delete" href="delete.php?action=deletesubcategory&table=categories&item=<?php echo $subcategory_id; ?>&page=<?php echo $current_page ?? ''; ?>">
                      <i class="bx bx-trash me-1"></i> Delete
                    </a>
                  </div>
                </div>
              </td>
            </tr>
          <?php 
            $i++; 
          } ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php include "footer.php"; ?>
