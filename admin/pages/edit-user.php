<?php include "header.php"; 

$user_id = $_GET['user'] ?? null;
if (!$user_id) {
  header("Location: users.php");
  exit();
}

$sql = "SELECT * FROM " . $siteprefix . "users WHERE s = '" .$user_id. "'";
$sql2 = mysqli_query($con, $sql);
if ($sql2 && mysqli_num_rows($sql2) > 0) {
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
        $active_log = 1;
        $user_reg_date = formatDateTime($created_date);
        $user_lastseen = formatDateTime($last_login);
    }
} else {
    // Redirect to users page if no matching record is found
    header("Location: users.php");
    exit;
}


?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <p class="text-bold text-dark">Edit User Account</p>
                <div class="row">
                            <div class="col-md-4 form-group p_star mb-3">
                                <input type="text" class="form-control" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                            </div>
                            <div class="col-md-4 form-group p_star mb-3">
                                <input type="hidden" name="user_id" value="<?php echo $userid; ?>">
                                <input type="text" class="form-control" name="middle_name" placeholder="Middle Name" value="<?php echo htmlspecialchars($middle_name); ?>">
                            </div>
                            <div class="col-md-4 form-group p_star mb-3">
                                <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="mobile_number" placeholder="Phone Number" value="<?php echo htmlspecialchars($mobile_number); ?>" required>
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo htmlspecialchars($address); ?>" required>
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="<?php echo htmlspecialchars($bank_name); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="bank_accname" placeholder="Bank Account Name" value="<?php echo htmlspecialchars($bank_accname); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="bank_number" placeholder="Bank Account Number" value="<?php echo htmlspecialchars($bank_number); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="facebook" placeholder="Facebook Profile" value="<?php echo htmlspecialchars($facebook); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="twitter" placeholder="Twitter Profile" value="<?php echo htmlspecialchars($twitter); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="instagram" placeholder="Instagram Profile" value="<?php echo htmlspecialchars($instagram); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="linkedln" placeholder="LinkedIn Profile" value="<?php echo htmlspecialchars($linkedln); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="kin_name" placeholder="Next of Kin Name" value="<?php echo htmlspecialchars($kin_name); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="kin_number" placeholder="Next of Kin Phone" value="<?php echo htmlspecialchars($kin_number); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="email" class="form-control" name="kin_email" placeholder="Next of Kin Email" value="<?php echo htmlspecialchars($kin_email); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="kin_relationship" placeholder="Relationship with Next of Kin" value="<?php echo htmlspecialchars($kin_relationship); ?>">
                            </div>
                            <div class="col-md-12 form-group p_star mb-3">
                                <textarea class="form-control" name="biography" placeholder="Biography"><?php echo htmlspecialchars($biography); ?></textarea>
                            </div>
                            <div class="form-group col-md-4 mb-3">
                        <label for="type">User Type</label>
                        <select class="form-select p-3" name="type" id="type" required>
                            <option value="user" <?php if ($type === 'user') echo 'selected'; ?>>User</option>
                            <option value="affiliate" <?php if ($type === 'affiliate') echo 'selected'; ?>>Affliate</option>
                        </select>
                    </div>
                <div class="form-group col-md-4 mb-3">
                    <label for="status">Status</label>
                    <select class="form-select p-3" name="status" id="status" required>
                        <option value="active" <?php if ($status === 'active') echo 'selected'; ?>>Active</option>
                        <option value="inactive" <?php if ($status === 'inactive') echo 'selected'; ?>>Inactive</option>
                    </select>
                </div> 
                <div class="col-md-4 form-group mb-3">
                <select class="form-control" id="gender" name="gender" required>
<option value="" <?php echo empty($gender) ? 'selected' : ''; ?>>-Select Gender-</option>
<option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
<option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
</select></div>
</div>
                <div class="mb-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="seller" name="seller"  <?php echo ($seller) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="seller">Make user a seller</label>
                          </div>
                        </div>
                <p><button class="w-100 btn btn-primary" name="update_profile_admin" value="update-user">Update Account</button></p>
                <input type="hidden" name="userid" value="<?php echo htmlspecialchars($userid, ENT_QUOTES, 'UTF-8'); ?>">
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
