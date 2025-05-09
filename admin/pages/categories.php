<?php include "header.php"; ?>

<div class="container-xxl flex-grow-1 container-p-y">

<!-- Basic Layout -->
               <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h4 class="mb-0">Update Category Information</h4>
                    </div>
                    <div class="card-body">
                      <form method="POST" enctype="multipart/form-data">
     <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Category Name</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM " . $siteprefix . "categories";
      $sql2 = mysqli_query($con, $sql);
      while ($row = mysqli_fetch_array($sql2)) {
          echo '<tr>';
          echo '<td><input type="hidden" name="ids[]" value="' . $row['id'] . '">' . $row['id'] . '</td>';
          echo '<td><input type="text" name="category_names[]" value="' . htmlspecialchars($row['category_name']) . '" class="form-control"></td>';
          echo '</tr>';
      }
      ?>
    </tbody>
  </table>

                        <button type="submit" name="update-category" value="course" class="btn btn-primary w-100">Update Category</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>



          
            <?php include "footer.php"; ?>
