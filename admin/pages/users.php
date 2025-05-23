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
                            <a class="dropdown-item delete <?= getDisplayClass() ?>" href="delete.php?action=delete&table=users&item=<?php echo $userid; ?>&page=<?php echo $current_page; ?>"><i class="bx bx-trash me-1"></i> Delete</a>
                           <?php if($status=="suspended"){}else {?> <a class="dropdown-item suspend" href="#" data-user-id="<?php echo $userid; ?>" data-user-name="<?php echo $display_name; ?>" data-bs-toggle="modal" data-bs-target="#suspendModal"><i class="bx bx-block me-1"></i> Suspend</a>
                         <?php } ?>
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

<!-- Suspend User Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="suspendModalLabel">Suspend User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="suspendUserId">
                    <div class="mb-3">
                        <label for="suspendUserName" class="form-label">User</label>
                        <input type="text" class="form-control" id="suspendUserName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="suspendDurationType" class="form-label">Duration</label>
                        <div class="d-flex align-items-center">
                            <select class="form-select me-2" name="duration_type" id="suspendDurationType" required>
                                <option value="days">Days</option>
                                <option value="months">Months</option>
                                <option value="years">Years</option>
                            </select>
                            <input type="number" class="form-control" name="duration_value" id="suspendDurationValue" placeholder="Enter number" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="suspendReason" class="form-label">Reason</label>
                        <textarea class="form-control" name="reason" id="suspendReason" rows="3" placeholder="Briefly state the reason – e.g., plagiarism, repeated customer complaints, inaccurate product listing, etc." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" name="suspend_user">Suspend User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.suspend').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            document.getElementById('suspendUserId').value = userId;
            document.getElementById('suspendUserName').value = userName;
        });
    });
</script>
<?php include "footer.php"; ?>
