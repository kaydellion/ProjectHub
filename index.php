<?php include "header.php"; ?>

 <!-- banner part start-->
 <section class="banner_part" style="margin:30px; border-radius:30px; position: relative; background: url('img/bann.jpg') no-repeat center center/cover;">
    <div style=" border-radius:30px; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5);"></div>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row align-items-center">
            <div class="col-md-10">
                <div class="banner_text">
                    <div class="banner_text_iner">
                        <h2 class="text-white"><?php echo $sitename; ?></h2>
                        <p class="text-white">Your one-stop platform for premium project reports and research documentation in Nigeria. 
                            Whether you're a student, entrepreneur, or professional, we provide expertly crafted reports tailored to your needs. </p>
                        <a href="reports.php" class="btn_1">Explore Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- banner part start-->

   <!-- recent reports start-->
        <div class="container mt-5">
        <div class="row">
        <div class="col-lg-12">
        <h3>Popular Products</h3>
        <p>Explore a wide range of topics, gain valuable insights, and achieve your goals with ease. Simplify your research journey today!</p>
        <div class="row mt-3">
           

    <?php
    $query = "SELECT r.*, u.display_name, u.profile_picture, l.category_name AS category, sc.category_name AS subcategory, ri.picture 
        FROM ".$siteprefix."reports r 
        LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
        LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
        LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
        LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id 
        WHERE r.status = 'approved' GROUP BY r.id";
    $result = mysqli_query($con, $query);
    if (!$result) {
            die('Query Failed: ' . mysqli_error($con));
    }
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
            $report_id = $row['id'];
            $title = $row['title'];
            $description = $row['description'];
            $category = $row['category'];
            $subcategory = $row['subcategory'];
            $pricing = $row['pricing'];
            $price = $row['price'];
            $tags = $row['tags'];
            $loyalty = $row['loyalty'];
            $user = $row['display_name'];
            $user_picture = $row['profile_picture'];
            $created_date = $row['created_date'];
            $updated_date = $row['updated_date'];
            $status = $row['status'];
            $image_path = $imagePath.$row['picture'];
    
            include "product-card.php";
            }} else {  die('No reports not found.'); }?>
<div class="col-lg-12 mt-1">
<div class="text-right"> <a href="reports.php" class="btn-kayd">View More</a> </div></div>
</div>
</div></div></div>



<div class="container mt-5">
<div class="row">
<div class="col-lg-12">
<h3>Recent Reports</h3>
<div class="row">
<?php
$query = "SELECT r.*, u.display_name, u.profile_picture, l.category_name AS category, sc.category_name AS subcategory, ri.picture 
    FROM ".$siteprefix."reports r 
    LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
    LEFT JOIN ".$siteprefix."users u ON r.user = u.s 
    LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id 
    LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id 
    WHERE r.status = 'approved' GROUP BY r.id ORDER BY r.id DESC LIMIT 4";
$result = mysqli_query($con, $query);
if ($result) {
while ($row = mysqli_fetch_assoc($result)) {
        $report_id = $row['id'];
        $title = $row['title'];
        $description = $row['description'];
        $category = $row['category'];
        $subcategory = $row['subcategory'];
        $pricing = $row['pricing'];
        $price = $row['price'];
        $tags = $row['tags'];
        $loyalty = $row['loyalty'];
        $user = $imagePath.$row['display_name'];
        $user_picture = $imagePath.$row['profile_picture'];
        $created_date = $row['created_date'];
        $updated_date = $row['updated_date'];
        $status = $row['status'];
        $image_path = $imagePath.$row['picture'];

        include "product-card.php";

}} else {  die('No reports not found.'); }?>

</div></div></div></div>
<div class="container mt-5">
    <div class="row align-items-center" style="background-color: #f8f9fa; border-radius: 15px; padding: 30px;">
        <div class="col-lg-6">
            <h2>Ready to Get Started?</h2>
            <p>Join us today and take your business to the next level with our comprehensive reports and innovative projects.</p>
            <a href="signup.php" class="btn_1">Sign Up Now</a>
        </div>
        <div class="col-lg-6">
            <img src="img/cta-image.png" alt="CTA Image" class="img-fluid" style="border-radius: 15px;">
        </div>
    </div>
</div>

<!-- Categories Section Start -->
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <h3>Categories</h3>
            <p> View our diverse collection of verified reports and innovative projects.</p>
            <div class="row">
                <?php
                $query = "SELECT * FROM ".$siteprefix."categories WHERE parent_id IS NULL";
                $result = mysqli_query($con, $query);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $category_id = $row['id'];
                        $category_name = $row['category_name'];
                        ?>
                        <div class="col-md-3 mb-3">
                            <a href="category.php?id=<?php echo $category_id; ?>" class="btn btn-dark btn-block"><?php echo $category_name; ?></a>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No categories found.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Categories Section End -->




<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <h3>Testimonials</h3>
            <div class="row">
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <div class="testimonial-image">
                            <img src="img/client_1.png" alt="Testimonial Image" class="img-fluid">
                        </div>
                        <div class="testimonial-text">
                            <p>"I was able to find the perfect report for my project. The quality of the report was top-notch and the delivery was prompt. I highly recommend this platform."</p>
                            <h5>John Doe</h5>
                            <span>CEO, XYZ Company</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <div class="testimonial-image">
                            <img src="img/client_2.png" alt="Testimonial Image" class="img-fluid">
                        </div>
                        <div class="testimonial-text">
                            <p>"I was able to find the perfect report for my project. The quality of the report was top-notch and the delivery was prompt. I highly recommend this platform."</p>
                            <h5>Jane Doe</h5>
                            <span>CEO, ABC Company</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <div class="testimonial-image">
                            <img src="img/client_1.png" alt="Testimonial Image" class="img-fluid">
                        </div>
                        <div class="testimonial-text">
                            <p>"I was able to find the perfect report for my project. The quality of the report was top-notch and the delivery was prompt. I highly recommend this platform."</p>
                            <h5>John Smith</h5>
                            <span>CEO, PQR Company</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Loyalty Program CTA Start -->

<div class="row align-items-center mt-5 mb-0" style="background-color: #f8f9fa; border-radius: 15px; padding: 30px;">
        <div class="col-lg-8">
            <h2>Join Our Loyalty Program</h2>
            <p>Our loyalty program offers exclusive benefits and rewards for our valued customers. By joining, you gain access to special discounts, early access to new reports, and other perks that enhance your experience and provide greater value.</p>
            <p><a href="loyalty-program.php" class="btn-kayd">Learn More</a></p>
        </div>
        <div class="col-lg-4">
            <img src="img/loyalty-program.jpg" alt="Loyalty Program" class="img-fluid">
        </div>
    </div>
<!-- Loyalty Program CTA End -->

<?php include "footer.php"; ?>