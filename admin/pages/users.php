<?php include "header.php"; ?>


<div class="container-xxl flex-grow-1 container-p-y">

              <!-- Hoverable Table rows -->
              <div class="card">
                <h5 class="card-header">All Users</h5>
                <div class="table-responsive text-nowrap ">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>S/N</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Registered_Date</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
<?php $sql = "SELECT * FROM ".$siteprefix."users  WHERE type  != 'admin'";
      $sql2 = mysqli_query($con, $sql);
      $i=1;
      while ($row = mysqli_fetch_array($sql2)) {
        $userid = $row["s"];
        $display_name = $row['display_name'];
        $first_name = $row['first_name']; 
        $middle_name = $row['middle_name'];
        $last_name = $row['last_name'];
        $profile_picture = !empty($row['profile_picture']) ? $row['profile_picture'] : 'user.png';
        $mobile_number = $row['mobile_number'];
        $email = $row['email'];
        $password = $row['password'];
        $gender = $row['gender'];
        $address = $row['address'];
        $type = $row['type'];
        $seller = $row['seller'];
        $status = $row['status'];
        $last_login = $row['last_login'];
        $created_date = $row['created_date'];
        $preference = $row['preference'];
        $bank_name = $row['bank_name'];
        $bank_accname = $row['bank_accname'];
        $bank_number = $row['bank_number'];
        $loyalty = $row['loyalty'];
        $wallet = $row['wallet'];
        $affliate = $row['affliate'];
        $facebook = $row['facebook'];
        $twitter = $row['twitter'];
        $instagram = $row['instagram'];
        $linkedln = $row['linkedln'];
        $kin_name = $row['kin_name'];
        $kin_number = $row['kin_number'];
        $kin_email = $row['kin_email'];
        $biography = $row['biography'];
        $kin_relationship = $row['kin_relationship'];

            $formatedupdatedate=formatDateTime($last_login);
            $formateduploaddate=formatDateTime($created_date);
            
            ?>
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php echo $i; ?></strong></td>
                        <td><?php echo $display_name; ?></td>
                        <td><?php echo $email; ?></td>
                        <td><span class="badge bg-label-<?php echo getUserColor($type); ?> me-1"><?php echo $type; ?></span></td>
                        <td><span class="badge bg-label-<?php echo getBadgeColor($status); ?> me-1"><?php echo $status; ?></span></td>
                        <td><?php echo $formateduploaddate; ?></td>
                        <td><?php echo $formatedupdatedate; ?></td>
                        <td><div class="dropdown">
                        <button type="button" class="btn btn-primary text-small dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>Manage</button>
                            <div class="dropdown-menu">
                            <a class="dropdown-item" href="edit-user.php?user=<?php echo $userid; ?>"><i class="bx bx-edit-alt me-1"></i> Edit </a>
                            <a class="dropdown-item delete" href="delete.php?action=delete&table=users&item=<?php echo $userid; ?>&page=<?php echo $current_page; ?>"><i class="bx bx-trash me-1"></i> Delete</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <?php $i++; } ?> 
                    </tbody>
                  </table>
                </div>
              </div>
              <!--/ Hoverable Table rows -->

            

            </div>




<?php include "footer.php"; ?>
