<?php include "header.php"; ?>



    <!--================signup_part Area =================-->
    <section class="signup_part mt-5 mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 col-md-12">
                    <div class="signup_part_text">
                        <div class="signup_part_text_iner">
                            <h2>Welcome to ProjectHub!</h2>
                            <p>Become a Publisher on ProjectReportHub by Monetizing Your Academic Works &
                            Reach Thousands of Students Nationwide </p>
                           

                            <a href="signin.php" class="btn_3 mb-3 ">Already have an account? Sign In</a>
                        </div>
                    </div>
                    <div class="signup_part_form">
                        <div class="signup_part_form_iner">
                            <h3>Create Your Account</h3>
                            <form class="row contact_form" method="post" enctype="multipart/form-data" novalidate="novalidate">
                                <div class="col-md-3 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="display_name" name="display_name" placeholder="Display Name / Company Name" value="<?php echo isset($_POST['display_name']) ? $_POST['display_name'] : ''; ?>" required>
                                </div>
                                <div class="col-md-3 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : ''; ?>" required>
                                </div>
                                <div class="col-md-3 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Middle Name" value="<?php echo isset($_POST['middle_name']) ? $_POST['middle_name'] : ''; ?>">
                                </div>
                                <div class="col-md-3 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : ''; ?>" required>
                                </div>

                                <div class="col-md-4 form-group p_star mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                                </div>
                                <div class="col-md-4 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile Number" value="<?php echo isset($_POST['mobile_number']) ? $_POST['mobile_number'] : ''; ?>" required>
                                </div>
                                <div class="col-md-4 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?>" required>
                                </div>

                                <div class="col-md-6 form-group p_star mb-3">
                                    <input type="file" class="form-control" id="profile_picture" name="profile_picture" placeholder="Photo" required>
                                </div>
                                <div class="col-md-6 form-group p_star mb-3">
                                    <select class="form-control" id="gender" name="gender" required>
                                    <option value="">-Select Gender-</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    </select> </div>
                                
                                <div class="col-md-12 form-group p_star mb-3">
                                    <textarea class="form-control" id="biography" name="biography" placeholder="About Me" required><?php echo isset($_POST['biography']) ? $_POST['biography'] : ''; ?></textarea>
                                </div>
                                
                               
                                <div class="form-group col-md-6 p_star mb-3">
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text p-3" onclick="togglePasswordVisibility('password')">
                                                <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 p_star mb-3">
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="retypePassword" name="retypePassword" placeholder="Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text p-3" onclick="togglePasswordVisibility('retypePassword')">
                                                <i class="fa fa-eye" id="toggleRetypePasswordIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="col-md-12">BANKING DETAILS</h4>
                                <div class="col-md-4 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name" value="<?php echo isset($_POST['bank_name']) ? $_POST['bank_name'] : ''; ?>" required>
                                </div>
                                <div class="col-md-4 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="bank_accname" name="bank_accname" placeholder="Account Name" value="<?php echo isset($_POST['bank_accname']) ? $_POST['bank_accname'] : ''; ?>" required>
                                </div>
                                <div class="col-md-4 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="bank_number" name="bank_number" placeholder="Account Number" value="<?php echo isset($_POST['bank_number']) ? $_POST['bank_number'] : ''; ?>" required>
                                </div>
                                <h4 class="col-md-12">SOCIAL MEDIA HANDLES</h4>
                                <div class="col-md-3 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="facebook" name="facebook" placeholder="Facebook" value="<?php echo isset($_POST['facebook']) ? $_POST['facebook'] : ''; ?>" >
                                </div>
                                <div class="col-md-3 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Twitter" value="<?php echo isset($_POST['twitter']) ? $_POST['twitter'] : ''; ?>" >
                                </div>
                                <div class="col-md-3 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="instagram" name="instagram" placeholder="Instagram" value="<?php echo isset($_POST['instagram']) ? $_POST['instagram'] : ''; ?>">
                                </div>
                                <div class="col-md-3 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="linkedln" name="linkedln" placeholder="LinkedIn" value="<?php echo isset($_POST['linkedln']) ? $_POST['linkedln'] : ''; ?>" >
                                </div>
                                <h4 class="col-md-12">CONTACT PERSON</h4>
                                <div class="col-md-4 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="kin_name" name="kin_name" placeholder="Name" value="<?php echo isset($_POST['kin_name']) ? $_POST['kin_name'] : ''; ?>" required>
                                </div>
                                <!---
                                <div class="col-md-4 form-group p_star mb-3 " style="display:hidden;>
                                    <select class="form-control" id="kin_relationship" name="kin_relationship"  hidden>
                                        <option value="" disabled selected>Designation</option>
                                        <option value="Parent" <?php echo (isset($_POST['kin_relationship']) && $_POST['kin_relationship'] == 'Parent') ? 'selected' : ''; ?>>Parent</option>
                                        <option value="Sibling" <?php echo (isset($_POST['kin_relationship']) && $_POST['kin_relationship'] == 'Sibling') ? 'selected' : ''; ?>>Sibling</option>
                                        <option value="Spouse" <?php echo (isset($_POST['kin_relationship']) && $_POST['kin_relationship'] == 'Spouse') ? 'selected' : ''; ?>>Spouse</option>
                                        <option value="Friend" <?php echo (isset($_POST['kin_relationship']) && $_POST['kin_relationship'] == 'Friend') ? 'selected' : ''; ?>>Friend</option>
                                        <option value="Other" <?php echo (isset($_POST['kin_relationship']) && $_POST['kin_relationship'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                --->
                               
    <input type="hidden" id="kin_relationship" name="kin_relationship" value="">

                                <div class="col-md-4 form-group p_star mb-3">
                                    <input type="text" class="form-control" id="kin_number" name="kin_number" placeholder="Mobile Number" value="<?php echo isset($_POST['kin_number']) ? $_POST['kin_number'] : ''; ?>" required>
                                </div>
                                <div class="col-md-4 form-group p_star mb-3">
                                    <input type="email" class="form-control" id="kin_email" name="kin_email" placeholder="E-Mail address" value="<?php echo isset($_POST['kin_email']) ? $_POST['kin_email'] : ''; ?>" required>
                                </div>
                                <div class="col-md-12 form-group p_star mb-3">
                                    <input type="checkbox" value="1" id="register_as_seller" name="register_as_seller" <?php echo isset($_POST['register_as_seller']) ? 'checked' : ''; ?>>
                                    <label for="register_as_seller">Register as a seller</label>
                                </div>
                                <div class="col-md-12 form-group">
                                    <button type="submit" value="submit" name="register-user" class="btn_1 w-100">
                                      Create Account
                                    </button>
                                    <a href="become_an_affliate.php" class="btn text-center btn-secondary w-100 mt-3">Become an Affiliate</a>
                                </div>
                            </form>


                        </div></div></div></div></div></div></section>







<?php include "footer.php"; ?>