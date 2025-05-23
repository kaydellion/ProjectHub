 <!--::footer_part start::-->
 <footer class="footer_part">
        <div class="footer_iner bg-dark">
        <div class="container">
    <div class="row justify-content-between align-items-start">
        <!-- Footer Logo and Description -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="footer_logo">
                <a href="<?php echo $siteurl; ?>/index.php">
                    <img class="logo" src="<?php echo $siteurl;?>img/<?php echo $siteimg; ?>" alt="Logo" />
                </a>
            </div>
            <p class="mt-3 mb-3 text-light"><?php echo $sitedescription; ?></p>
            <div class="social_icon">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-google-plus-g"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

        <!-- Company Links -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="footer_menu_item">
                <h4>Company</h4>
                <a href="<?php echo $siteurl; ?>index.php">Home</a>
                <a href="<?php echo $siteurl; ?>about-us.php">About Us</a>
                <a href="<?php echo $siteurl; ?>privacy_policy.php">Privacy Policy</a>
                <a href="<?php echo $siteurl; ?>cookies.php">Cookie Policy</a>
                <a href="<?php echo $siteurl; ?>terms.php">Terms & Conditions</a>
                <a href="<?php echo $siteurl; ?>disclaimer.php">Disclaimer and Phishing Claims</a>
            </div>
        </div>

        <!-- Market Place Links -->
        <div class="col-lg-3 col-md-6 mb-4">
            <h4>Market Place</h4>
            <div class="footer_menu_item">
                <?php
                $sql = "SELECT * FROM " . $siteprefix . "categories WHERE parent_id IS NULL LIMIT 6";
                $sql2 = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($sql2)) {
                    $category_name = $row['category_name'];
                    $alt_names = $row['slug'];
                    $slugs = $alt_names;
                    echo '<a href="'.$siteurl.'category/' . $slugs . '">' . $category_name . '</a>';
                }
                ?>
            </div>
        </div>

        <!-- Resources Links -->
        <div class="col-lg-3 col-md-6 mb-4">
            <h4>Resources</h4>
            <div class="footer_menu_item">
                <?php
                $sql = "SELECT * FROM " . $siteprefix . "resource_types WHERE parent_id IS NULL LIMIT 8";
                $sql2 = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($sql2)) {
                    $title = $row['name'];
                    $id = $row['id'];
                    echo '<a href="'.$siteurl.'resource.php?resources=' . $id . '">' . $title . '</a>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
        
        <div class="copyright_part">
            <div class="container">
                <div class="row ">
                <div class="col-lg-12 mb-3">

    <div class="disclaimer_text text-light">
    <p style="color: white; margin-bottom: 1rem;">
    Disclaimer: The materials and project reports available on this website are provided solely for educational and reference purposes. They are intended to guide and support research and learning and should not be submitted as original work for academic credit or in fulfillment of any educational qualification.
    </p></div>
            </div><div class="col-lg-12">
            <div class="copyright_text">
        
                            <P>
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | <?php echo $sitename; ?></P>
                        <div class="footer_links">
                                <a href="<?php echo $siteurl;?>affiliates.php">Affiliate Program</a> |
                                <a href="<?php echo $siteurl;?>loyalty-program.php">Loyalty Program</a> |
                                <a href="<?php echo $siteurl;?>marketplace.php">MarketPlace</a> |
                                <a href="<?php echo $siteurl;?>tickets.php">Support Tickets</a> |
                                <a href="<?php echo $siteurl;?>terms.php">Terms & Conditions</a> |
                                <a href="<?php echo $siteurl;?>faq.php">FAQ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<script src="https://js.paystack.co/v1/inline.js"></script> 

    <!--::footer_part end::-->

    <!-- jquery plugins here
    <script src="js/jquery-1.12.1.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- popper js -->
    <script src="<?php echo $siteurl;?>js/popper.min.js"></script>
    <!-- bootstrap js -->
    <script src="<?php echo $siteurl;?>js/bootstrap.min.js"></script>
    <!-- magnific popup js -->
    <script src="<?php echo $siteurl;?>js/jquery.magnific-popup.js"></script>
    <!-- carousel js -->
    <script src="<?php echo $siteurl;?>js/owl.carousel.min.js"></script>
    <script src="<?php echo $siteurl;?>js/jquery.nice-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- slick js -->
    <script src="<?php echo $siteurl;?>js/slick.min.js"></script>
    <script src="<?php echo $siteurl;?>js/jquery.counterup.min.js"></script>
    <script src="<?php echo $siteurl;?>js/contact.js"></script>
    <script src="<?php echo $siteurl;?>js/jquery.ajaxchimp.min.js"></script>
    <script src="<?php echo $siteurl;?>js/jquery.form.js"></script>
    <script src="<?php echo $siteurl;?>js/jquery.validate.min.js"></script>
   <!--- <script src="js/mail-script.js"></script> 
   <script src="js/waypoints.min.js"></script> -->
    <!-- custom js -->
    <script src="<?php echo $siteurl;?>js/custom.js"></script>
<script type="text/javascript">const paymentForm = document.getElementById('paymentForm');
paymentForm.addEventListener("submit", payWithPaystack, false);
function payWithPaystack(e) {
  e.preventDefault();
  let handler = PaystackPop.setup({
    key: '<?php echo $apikey; ?>', // Replace with your public key
    email:  document.getElementById("email-address").value,
    amount: document.getElementById("amount").value * 100,
    ref: document.getElementById("ref").Value, // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
    // label: "Optional string that replaces customer email"
    metadata: {
               custom_fields: [
                  {
                      display_name: "Mobile Number",
                      variable_name: "mobile_number",
                      value: document.getElementById("mobile-number").value,
                  }
               ]
            },
    onClose: function(){
      alert('Window closed.');
    },
    callback: function(response){ 
	window.location.href = document.getElementById("refer").value;
	}
  });
  handler.openIframe();
}</script>
</body>

</html>