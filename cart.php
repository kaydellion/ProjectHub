<?php include "header.php"; ?>


<div class="container py-5">
    <h1 class="mb-5">Your Shopping Cart</h1>
    <div class="row">
        <div class="col-lg-8">
            <!-- Cart Items -->
            <div class="card mb-4">
                <div class="card-body">
                    <?php 
                    if($active_log== 0) {
                        $message="You need to login to view your cart. <a href='signin.php'>Login here</a>";
                        displayMessage($message);
                    } else if (getCartCount($con, $siteprefix, $order_id) == 0) {
                        $message="Your cart is empty. <a href='marketplace.php'>Start shopping here</a>";
                        displayMessage($message);
                    } else {
                    // Assuming database connection exists
                    $sql = "SELECT oi.*,oi.s as fileid, rf.title as file, r.title as report_title, oi.price, ri.picture 
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
                    $picture =$imagePath.$item['picture'];
                    $title=$item['report_title'];
                    $slug = strtolower(str_replace(' ', '-', $title)); ?>
                        <div class="row cart-item mb-3" id="cart-item-<?php echo htmlspecialchars($item['s']); ?>">
                        <div class="col-md-3">
                            <img src="<?php echo htmlspecialchars($picture); ?>"  alt="<?php echo htmlspecialchars($item['report_title']); ?>" class="img-fluid img-small rounded">
                        </div>
                        <div class="col-md-5">
                            <a href="product?slug=<?php echo $slug; ?>"><h5 class="card-title"><?php echo htmlspecialchars($item['report_title']); ?></h5></a>
                            <p class="text-muted">Type: <?php echo htmlspecialchars(getFileExtension($item['file'])); ?></p>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                            <input style="max-width:100px" type="text" 
                                   class="form-control form-control-sm text-center quantity-input" 
                                   value="1" readonly>
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <p class="fw-bold"><?php echo $sitecurrency; echo number_format($item['price'], 2); ?></p>
                            <button class="btn btn-sm btn-outline-danger delete-cart-item" 
                                    data-item-id="<?php echo htmlspecialchars($item['fileid']); ?>">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        </div>
                    <?php endwhile;
                    mysqli_stmt_close($stmt); }
                    ?>
                    <hr>
                </div>
            </div>
            <!-- Continue Shopping Button -->
            <div class="text-start mb-4">
                <a href="marketplace.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Cart Summary -->
            <div class="card cart-summary">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <span><?php echo $sitecurrency; ?><span class="cart-total"><?php echo $order_total;?></span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>VAT</span>
                        <span><?php echo $sitecurrency;?>0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                       <span><?php echo $sitecurrency;?><strong class='cart-total'><?php echo $order_total;?></strong></span> 
                    </div>
                    <a href="checkout.php" class="btn btn-primary w-100">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include "footer.php"; ?>