<?php include "header.php"; ?>


  <!--================Checkout Area =================-->
  <section class="checkout_area section_padding">
    <div class="container">
      <div class="billing_details">
        <div class="row">
          <div class="col-lg-8">
            <h3>Billing Details</h3>
            <form class="row contact_form" id="paymentForm" novalidate="novalidate">
              <div class="col-md-6 form-group p_star">
                <input type="text" class="form-control" placeholder="Name" id="first-name" value="<?php echo $username;?>" name="name" />
              </div>
              <div class="col-md-6 form-group p_star">
                <input type="text" class="form-control" placeholder="Phone number" id="mobile-number" value="<?php echo $mobile_number;?>" name="number" />
              </div>
              <div class="col-md-12 form-group p_star">
                <input type="text" class="form-control" placeholder="Email Address" value="<?php echo $email;?>" id="email-address" name="compemailany" />
              </div>
              <input type="hidden" id="amount" value="<?php echo $order_total; ?>"/>
              <input type="hidden" id="ref"   value="<?php echo  $order_id; ?>  "  />
              <input type="hidden" id="refer" value="<?php echo $siteurl; ?>/pay_success.php?ref=<?php echo $order_id; ?> " />
          
          </div>
         <div class="col-lg-4">
  <div class="order_box">
    <h2>Your Order</h2>
    <ul class="list">
      <li>
        <a href="#">Product
          <span>Total</span>
        </a>
      </li>
      <?php 
        // Initialize
        $is_free_order = false;
        $item_count = 0;

        $sql = "SELECT oi.*, rf.title as file, r.title as report_title, r.pricing, oi.price, ri.picture 
                FROM ".$siteprefix."order_items oi
                JOIN ".$siteprefix."reports r ON oi.report_id = r.id
                LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id
                LEFT JOIN ".$siteprefix."reports_files rf ON r.id = rf.report_id
                WHERE oi.order_id = ? 
                GROUP BY oi.s";

        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 's', $order_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($item = mysqli_fetch_assoc($result)):
          $item_count++;
          if ($item['pricing'] == 'free') {
            $is_free_order = true;
          }
      ?>
      <li>
        <a href="#"><?php echo htmlspecialchars($item['report_title']); ?>
          <span class="middle">(<?php echo htmlspecialchars(getFileExtension($item['file'])); ?>)</span>
          <span class="last"><?php echo $sitecurrency; echo number_format($item['price'], 2); ?></span>
        </a>
      </li>
      <?php endwhile; mysqli_stmt_close($stmt); ?>
    </ul>

    <ul class="list list_2">
      <li>
        <a href="#">Subtotal
          <span><?php echo $sitecurrency; echo $order_total; ?></span>
        </a>
      </li>
      <li>
        <a href="#">Total
          <span><?php echo $sitecurrency; echo $order_total; ?></span>
        </a>
      </li>
    </ul>

    <?php if ($order_total > 0) { ?>
 
      <div class="payment_methods">
        <h4>Select Payment Method</h4>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="payment_method" id="paystack" value="paystack" checked>
          <label class="form-check-label" for="paystack">Pay with Paystack</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="payment_method" id="manual" value="manual">
          <label class="form-check-label" for="manual">Manual Bank Transfer</label>
        </div>
      </div>

      <!-- Paystack Button -->
      <button class="btn_1 w-100 text-center paystack-button" onClick="payWithPaystack()">Proceed to Payment</button>
 
      <!-- Manual Payment Button -->
      <button type="button" class="btn_1 w-100 text-center manual-button" data-toggle="modal" data-target="#manualPaymentModal" style="display: none;">
        Proceed with Manual Payment
      </button>
      </form>
    <?php } elseif ($order_total == 0 && $is_free_order && $item_count > 0) { ?>
      <!-- Place order for free product -->
     
      <form method="post" >
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <button type="submit" name="place_order" class="btn_1 w-100 text-center">Place Order</button>
      </form>
    <?php } else { 
        displayMessage('<a href="marketplace.php">Shop More</a>'); 
      } ?>
  </div>
</div>

        </div>
      </div>
    </div>
  </section>
  <!--================End Checkout Area =================-->
<!-- Manual Payment Modal -->
<!-- Manual Payment Modal -->
<div class="modal fade" id="manualPaymentModal" tabindex="-1" role="dialog" aria-labelledby="manualPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manualPaymentModalLabel">Manual Bank Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post"  enctype="multipart/form-data">
                <div class="modal-body">
                    <p>Please transfer the total amount to the following bank account:</p>
                    <ul>
                        <li><strong>Bank Name:</strong> <?php echo $site_bank; ?></li>
                        <li><strong>Account Name:</strong> <?php echo $siteaccname; ?></li>
                        <li><strong>Account Number:</strong> <?php echo $siteaccno; ?></li>
                    </ul>
                    <p><strong>Total Amount:</strong> <?php echo $sitecurrency . number_format($order_total, 2); ?></p>
                    <p>After making the payment, upload the proof of payment below:</p>
                    <div class="form-group">
                        <label for="proof_of_payment">Upload Proof of Payment</label>
                        <input type="file" class="form-control" id="proof_of_payment" name="proof_of_payment" required>
                    </div>
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="amount" value="<?php echo $order_total; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_manual_payment" class="btn btn-primary">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>




  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const paystackButton = document.querySelector(".paystack-button");
        const manualButton = document.querySelector(".manual-button");
        const paymentMethods = document.querySelectorAll("input[name='payment_method']");

        paymentMethods.forEach(method => {
            method.addEventListener("change", function () {
                if (this.value === "paystack") {
                    paystackButton.style.display = "block";
                    manualButton.style.display = "none";
                } else if (this.value === "manual") {
                    paystackButton.style.display = "none";
                    manualButton.style.display = "block";
                }
            });
        });
    });
</script>


<?php include "footer.php"; ?>