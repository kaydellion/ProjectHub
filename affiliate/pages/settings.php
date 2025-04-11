<?php include "header.php"; ?>

<section class="signup_part mt-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 col-md-12">
                <div class="signup_part_text">
                    <div class="signup_part_text_iner">
                        <h2>Edit Profile</h2>
                        <p>Update your profile information below.</p>
                    </div>
                </div>
                <div class="signup_part_form">
                    <div class="signup_part_form_iner">
                        <h3>Profile Details</h3>
                        <form class="row contact_form" method="POST" enctype="multipart/form-data">
                        <div class="col-md-12 form-group p_star mb-3">
                            <label for="profile_picture" class="form-label">Change Profile Picture</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" placeholder="Photo" required>
                            </div>
                            <div class="col-md-4 form-group p_star mb-3">
                                <input type="text" class="form-control" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                            </div>
                            <div class="col-md-4 form-group p_star mb-3">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
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
                            <div class="col-md-12 form-group">
                                <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>