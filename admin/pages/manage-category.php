<?php include "header.php"; ?>

<div class="container-xxl flex-grow-1 container-p-y">

  <!-- Hoverable Table rows -->
  <div class="card">
    <h5 class="card-header">Manage Categories</h5>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Category Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <?php
         $query = "SELECT * FROM {$siteprefix}categories WHERE parent_id IS NULL ORDER BY category_name ASC";

          $result = mysqli_query($con, $query);
          if (!$result) {
            die('Query Failed: ' . mysqli_error($con));
          }
          $i = 1;
          while ($row = mysqli_fetch_assoc($result)) {
            $category_id = $row['id'];
            $category_name = $row['category_name'];
          ?>
            <tr>
              <td><strong><?php echo $i; ?></strong></td>
              <td><?php echo $category_name; ?></td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn btn-primary text-small dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i> Manage
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="edit-category.php?id=<?php echo $category_id; ?>"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                    <a href="javascript:void(0);" class="dropdown-item" onclick="confirmCategoryDelete('categories', <?php echo $category_id; ?>, '<?php echo $current_page; ?>')">
                    <i class="bx bx-trash me-1"></i> Delete
                    </a>

                  </div>
                </div>
              </td>
            </tr>
          <?php $i++;
          } ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
<script>
  function confirmCategoryDelete(table, item, page) {
    if (confirm("Are you sure you want to delete this category?\n\n⚠️ All subcategories under it will also be deleted!")) {
      window.location.href = `delete.php?action=deletecategory&table=${table}&item=${item}&page=${page}`;
    }
  }
</script>

<?php include "footer.php"; ?>
