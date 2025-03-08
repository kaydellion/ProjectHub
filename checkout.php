<?php include "header.php"; ?>


  <!--================Checkout Area =================-->
  <section class="checkout_area section_padding">
    <div class="container">
      <div class="billing_details">
        <div class="row">
          <div class="col-lg-8">
            <h3>Billing Details</h3>
            <form class="row contact_form" action="#" method="post" novalidate="novalidate">
              <div class="col-md-6 form-group p_star">
                <input type="text" class="form-control" placeholder="Name" id="first" value="<?php echo $username;?>" name="name" />
              </div>
              <div class="col-md-6 form-group p_star">
                <input type="text" class="form-control" placeholder="Phone number" id="number" value="<?php echo $mobile_number;?>" name="number" />
              </div>
              <div class="col-md-12 form-group p_star">
                <input type="text" class="form-control" placeholder="Email Address" value="<?php echo $email;?>" id="email" name="compemailany" />
              </div>
            </form>
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
                    // Assuming database connection exists
                    $sql = "SELECT oi.*, rf.title as file, r.title as report_title, oi.price, ri.picture 
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

                    while ($item = mysqli_fetch_assoc($result)): ?>
                <li>
                  <a href="#"><?php echo htmlspecialchars($item['report_title']); ?>
                    <span class="middle">(<?php echo htmlspecialchars(getFileExtension($item['file'])); ?>) </span>
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
              <div class="creat_account">
                <input type="checkbox" id="f-option4" name="selector" />
                <label for="f-option4">I’ve read and accept the </label>
                <a href="terms.php">terms & conditions*</a>
              </div>
              <?php if ($order_total > 0) { ?>
                <a class="btn_1 w-100 text-center" href="#">Proceed to Payment</a>
              <?php } else {  displayMessage('<a href="marketplace.php">Shop More </a>'); } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================End Checkout Area =================-->












<?php include "footer.php"; ?>