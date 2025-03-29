<?php include "header.php"; ?>

<section class="signup_part mt-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 col-md-12">
                <div class="signup_part_text">
                    <div class="signup_part_text_iner">
                        <h2>Become an Affiliate!</h2>
                        <p>Join our affiliate program and earn commissions by promoting our platform.</p>
                        <a href="affiliate/" class="btn_3 mb-3">Already registered? Sign In</a>
                    </div>
                </div>
                <div class="signup_part_form">
                    <div class="signup_part_form_iner">
                        <h3>Affiliate Registration</h3>
                        <form class="row contact_form" method="post" enctype="multipart/form-data" novalidate="novalidate">
                            <h4 class="col-md-12">Affiliate Details</h4>
                            <div class="col-md-4 form-group p_star mb-3">
                                <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
                            </div>
                            <div class="col-md-4 form-group p_star mb-3">
                                <input type="text" class="form-control" name="middle_name" placeholder="Middle Name">
                            </div>
                            <div class="col-md-4 form-group p_star mb-3">
                                <input type="text" class="form-control" name="last_name" placeholder="Last Name" required>
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                            </div>
                            <div class="col-md-6 form-group p_star mb-3 mb-3">
                                <input type="text" class="form-control" name="phone" placeholder="Phone Number" required>
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="country" placeholder="Country of Residence" required>
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="address" placeholder="Address" required>
                            </div>

                         
                            <div class="form-group col-md-6 p_star mb-3">
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" onclick="togglePasswordVisibility('password')">
                                                <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 p_star mb-3">
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="retypePassword" name="retypePassword" placeholder="Retype Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" onclick="togglePasswordVisibility('retypePassword')">
                                                <i class="fa fa-eye" id="toggleRetypePasswordIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <input type="text" class="form-control" name="website" placeholder="Website (if any)">
                            </div>
                            <div class="col-md-6 form-group p_star mb-3">
                                <label>Means of Identification:</label>
                                <input type="file" class="form-control" name="id_upload" required>
                            </div>
                            
                            <h4 class="col-md-12">Where Did You Learn About Our Affiliate Program?</h4>
                            <div class="col-md-12 form-group">
                                <select class="form-control" name="referral_source" required>
                                    <option value="">Select</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="Twitter">Twitter</option>
                                    <option value="Facebook">Facebook</option>
                                </select>
                            </div>
                            
                            <div class="col-md-12 form-group mb-3">
                                <input type="checkbox" value="1" id="agree_terms" name="agree_terms" required>
                                <label for="agree_terms">I agree to the terms and conditions</label>
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <button type="submit" value="submit" name="register-affiliate" class="btn_1 w-100">
                                  Register as Affiliate
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
