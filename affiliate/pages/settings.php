<?php include "header.php"; ?>

<section class="signup_part mt-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 col-md-12">
                <div class="signup_part_text">
                    <div class="signup_part_text_iner">
                        <h2>Update Affiliate Details</h2>
                        <p>Update your affiliate profile information below.</p>
                    </div>
                </div>
                <div class="signup_part_form">
                    <div class="signup_part_form_iner">
                        <h3>Affiliate Profile</h3>
                        <form class="row contact_form" method="post" enctype="multipart/form-data" novalidate="novalidate">
                            <h4 class="col-md-12">Affiliate Details</h4>
                            <div class="col-md-4 form-group p_star">
                                <input type="text" class="form-control" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($affiliate_data['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4 form-group p_star">
                                <input type="text" class="form-control" name="middle_name" placeholder="Middle Name" value="<?php echo htmlspecialchars($affiliate_data['middle_name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4 form-group p_star">
                                <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($affiliate_data['last_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <input type="email" class="form-control" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($affiliate_data['email'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <input type="text" class="form-control" name="phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($affiliate_data['phone'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <input type="text" class="form-control" name="country" placeholder="Country of Residence" value="<?php echo htmlspecialchars($affiliate_data['country'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo htmlspecialchars($affiliate_data['address'] ?? ''); ?>" required>
                            </div>

                            <div class="col-md-6 form-group p_star">
                                <input type="text" class="form-control" name="website" placeholder="Website (if any)" value="<?php echo htmlspecialchars($affiliate_data['website'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <label>Means of Identification:</label>
                                <input type="file" class="form-control" name="id_upload">
                                <small>Current File: <?php echo htmlspecialchars($affiliate_data['id_upload'] ?? 'No file uploaded'); ?></small>
                            </div>
                            
                            <h4 class="col-md-12">Where Did You Learn About Our Affiliate Program?</h4>
                            <div class="col-md-12 form-group">
                                <select class="form-control" name="referral_source" required>
                                    <option value="">Select</option>
                                    <option value="Referral" <?php echo (isset($affiliate_data['referral_source']) && $affiliate_data['referral_source'] == 'Referral') ? 'selected' : ''; ?>>Referral</option>
                                    <option value="Instagram" <?php echo (isset($affiliate_data['referral_source']) && $affiliate_data['referral_source'] == 'Instagram') ? 'selected' : ''; ?>>Instagram</option>
                                    <option value="Twitter" <?php echo (isset($affiliate_data['referral_source']) && $affiliate_data['referral_source'] == 'Twitter') ? 'selected' : ''; ?>>Twitter</option>
                                    <option value="Facebook" <?php echo (isset($affiliate_data['referral_source']) && $affiliate_data['referral_source'] == 'Facebook') ? 'selected' : ''; ?>>Facebook</option>
                                </select>
                            </div>
                            
                            <div class="col-md-12 form-group">
                                <input type="checkbox" value="1" id="agree_terms" name="agree_terms" <?php echo (isset($affiliate_data['agree_terms']) && $affiliate_data['agree_terms'] == 1) ? 'checked' : ''; ?> required>
                                <label for="agree_terms">I agree to the terms and conditions</label>
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" value="submit" name="update-affiliate" class="btn_1 w-100">
                                  Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>


