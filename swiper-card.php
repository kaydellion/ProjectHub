<?php

         //check if its in wishlist
         $theinitialicon="";
        if($active_log==1){
        $checkEmail = mysqli_query($con, "SELECT * FROM ".$siteprefix."wishlist WHERE user='$user_id' AND product='$report_id'");
        if(mysqli_num_rows($checkEmail) >= 1 ) {
        $theinitialicon="added";}}?>



<div class="swiper-slide col-lg-3 col-6 product <?php echo removeAllWhitespace($subcategory); ?>">
                    <div class="single_product_item">
                    <div class="single_product_img" style="background: url('<?php echo $image_path; ?>')">
                    <div class="wishlist_icon"><a class="add-to-wishlist <?php echo $theinitialicon; ?>" data-product-id="<?php echo $report_id; ?>"><i class="ti-heart"></i></a></div>
                            </div>
                            <div class="single_product_text">
                            <a href="product.php?id=<?php echo $report_id; ?>"><h5 class="text-bold"><?php echo $title; ?></h5></a>
                            <div class="user_info">
                            <img src="<?php echo $user_picture; ?>" alt="<?php echo $user; ?>" class="img-fluid user-image">
                            <span><?php echo $user; ?></span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                            <h6><?php echo $sitecurrency; echo $price; ?></h6>
                            <div class="rating">
                            <i class="fa fa-star text-primary"></i>
                            <span class="text-bold">5.0</span>
                             </div>
                            </div>
                            </div></div></div>