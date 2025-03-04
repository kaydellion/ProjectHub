<div class="col-lg-3 col-6 product <?php echo removeAllWhitespace($subcategory); ?>">
                    <div class="single_product_item">
                    <div class="single_product_img" style="background: url('<?php echo $image_path; ?>')">
                    <div class="wishlist_icon"><a href="#"><i class="ti-heart"></i></a></div>
                            </div>
                            <div class="single_product_text">
                            <a href="product.php?id=<?php echo $report_id; ?>"><h4><?php echo $title; ?></h4></a>
                            <div class="user_info">
                            <img src="<?php echo $user_picture; ?>" alt="<?php echo $user; ?>" class="img-fluid user-image">
                            <span><?php echo $user; ?></span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                            <h6>$<?php echo $price; ?></h6>
                            <div class="rating">
                            <i class="fa fa-star text-primary"></i>
                            <span class="text-bold">5.0</span>
                             </div>
                            </div>
                            </div></div></div>