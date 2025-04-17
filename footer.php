  <!--::footer_part start::-->
  <footer class="footer_part">
        <div class="footer_iner bg-dark">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-4">
                            <div class="footer_logo">
                                <a href="index.php"><img class="logo" src="img/<?php echo $siteimg; ?>" alt="#"/></a>
                            </div>
                            <p class="mt-3 mb-3 text-light"><?php echo $sitedescription; ?></p>
                            <div class="social_icon">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-google-plus-g"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                    <div class="footer_menu_item">
                    <h4>Quick Links</h4>
                                <a href="index.php">Home</a>
                                <a href="about-us.php">About Us</a>
                                <a href="marketplace.php">Products</a>
                                <a href="tickets.php">Support Tickets</a>
                            </div>
                    </div>
                    <div class="col-lg-4">
                    <h4 class="mt-3 d-sm-none">Company</h4>
                    <h4 class="d-none d-sm-block">Company</h4>
                      <div class="footer_menu_item">
<a href="privacy_policy.php">Privacy Policy</a>
<a href="cookies.php">Cookie Policy</a>
<a href="disclaimer.php">Disclaimer and Phishing Claims</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="copyright_part">
            <div class="container">
                <div class="row ">
                    <div class="col-lg-12">
                        <div class="copyright_text">
                            <P>
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | <?php echo $sitename; ?></P>
                            <div class="copyright_link">
                                <a href="terms.php">Terms & Conditions</a>
                                <a href="faq.php">FAQ</a>
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
    <script src="js/popper.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- magnific popup js -->
    <script src="js/jquery.magnific-popup.js"></script>
    <!-- carousel js -->
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
    <?php include "backend/datatable.php"; ?>
    <!-- slick js -->
    <script src="js/slick.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/contact.js"></script>
    <script src="js/jquery.ajaxchimp.min.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/jquery.validate.min.js"></script>
   <!--- <script src="js/mail-script.js"></script>  -->
    <!-- custom js -->
    <script src="js/custom.js"></script>
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