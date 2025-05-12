<?php include "header.php"; ?>

 <!-- banner part start-->
 <section class="banner_part" style="margin:30px; height:690px; border-radius:30px; position: relative; background: url('img/hero-image.jpg') no-repeat center center/cover; overflow: hidden;">
    <div style="border-radius:30px; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(80deg, rgba(0, 0, 0, 0.8), rgba(255, 165, 0, 0.3));"></div>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="banner_text">
                    <div class="banner_text_iner" style="max-width: 800px;">
                        <h6 class="text-orange">Project Report Hub – Empowering Research, Fueling Success!</h6>
                        <p class="text-white text-hero" style="line-height: 1;">
                            ProjectReportHub is Nigeria’s premier digital platform designed exclusively for university students — from undergraduates to postgraduate (Master's and PhD) scholars.
                            Whether you're working on coursework, exam prep, or advanced research, ProjectReportHub provides everything you need to excel, all in one place.
                            Join thousands of smart users who trust ProjectReportHub for academic success and professional growth.
                            <br><br>
                            <?php
// Fetch education levels from the database
$query = "SELECT id, name FROM {$siteprefix}education_levels";
$result = mysqli_query($con, $query);


if ($result && mysqli_num_rows($result) > 0) {
    echo '<p class="text-white text-hero" style="line-height: 1;">| ';
    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row['name'];
        $id = $row['id'];

        // Shorten the education level name
        $shortenedTitle = $title;
        if (stripos($title, 'National Certificate in Education') !== false || stripos($title, 'NCE') !== false) {
            $shortenedTitle = 'NCE';
        } elseif (stripos($title, 'Higher National Degree') !== false || stripos($title, 'HND') !== false) {
            $shortenedTitle = 'HND';
        } elseif (stripos($title, 'Ordinary National Degree') !== false || stripos($title, 'OND') !== false) {
            $shortenedTitle = 'OND';
        } elseif (stripos($title, 'Postgraduate Diploma') !== false || stripos($title, 'PGD') !== false) {
            $shortenedTitle = 'PGD';
        } elseif (stripos($title, 'Master') !== false) {
            $shortenedTitle = 'Masters';
        } elseif (stripos($title, 'Doctor') !== false || stripos($title, 'Ph.D') !== false) {
            $shortenedTitle = 'Ph.D';
        }

        // Display the shortened title as a link
        echo '<a class="text-white" href="education_levels.php?education_levels=' . $id . '" style="text-decoration: none; margin-right: 10px;">' . htmlspecialchars($shortenedTitle) . '</a> | ';
    }
    echo '</p>';
} 
   
?>
                        </p>
                        <a href="marketplace.php" class="btn_1">Explore Now</a>
                        <!-- Trusted By Section -->
                        <div class="trusted-by mt-4">
                            <p class="text-white" style="font-size: 14px; font-weight: bold; line-height: 1.5;">
                                <i class="fas fa-users" style="color: #FFA500; font-size: 16px;"></i> 
                                Trusted by <br><span style="font-size: 1.5rem;">500+</span>
                                <span style="font-size: 1rem; margin-left:20px;">professionals</span>
                            </p>
                        </div>
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
        <h3>Popular Study Resources    </h3>
        <p>Discover top-rated academic materials designed to help you excel! From project
topics to research guides, simplify your study journey, save time, and achieve better
results with ease — all in one place.</p>
        <div class="row mt-3">
           
        <div class="swiper mySwiper">
        <div class="swiper-wrapper">
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
            $alt_title = $row['alt_title'];
            $description = $row['description'];
            $category = $row['category'];
            $subcategory = $row['subcategory'];
            $pricing = $row['pricing'];
            $price = $row['price'];  
            $tags = $row['tags'];
            $loyalty = $row['loyalty'];
            $user = $row['display_name'];
            $user_picture = $imagePath.$row['profile_picture'];
            $created_date = $row['created_date'];
            $updated_date = $row['updated_date'];
            $status = $row['status'];
            $image_path = $imagePath.$row['picture'];
            $selected_education_level = $row['education_level'] ?? '';
                        $selected_resource_type = $row['resource_type'] ?? '';
                        $year_of_study = $row['year_of_study'] ?? '';
                        
                        $slug = $alt_title;
    
            include "swiper-card.php";
            }?>
   </div>
    <!-- Add Arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
  </div>
</div>
<?php }else {  debug('No reports not found.'); }?>
</div>
</div></div>


<!-- Last Purchased Reports Section -->
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <h3>Last Purchased Reports</h3>
            <p>Check out the most recently purchased reports by our users. Stay updated with trending academic materials!</p>
            <div class="row mt-3">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php
                        // Query to fetch all last purchased reports
                        $latestSalesQuery = "
                            SELECT DISTINCT r.id AS report_id, r.title,r.alt_title, r.description, r.price, ri.picture, u.display_name, u.profile_picture 
                            FROM ".$siteprefix."orders o
                            JOIN ".$siteprefix."order_items oi ON o.order_id = oi.order_id
                            JOIN ".$siteprefix."reports r ON r.id = oi.report_id
                            LEFT JOIN ".$siteprefix."reports_images ri ON r.id = ri.report_id
                            LEFT JOIN ".$siteprefix."users u ON r.user = u.s
                            WHERE o.status = 'paid' AND r.status = 'approved'
                            GROUP BY r.id
                            ORDER BY o.date DESC
                        ";
                        $latestSalesResult = mysqli_query($con, $latestSalesQuery);

                        if ($latestSalesResult && mysqli_num_rows($latestSalesResult) > 0) {
                            while ($row = mysqli_fetch_assoc($latestSalesResult)) {
                                $report_id = $row['report_id'];
                                $title = $row['title'];
                                $alt_title = $row['alt_title'];
                                $description = $row['description'];
                                $price = $row['price'];
                                $image_path = $imagePath . $row['picture'];
                                $user = $row['display_name'];
                                $user_picture = $imagePath . $row['profile_picture'];
                                $slug = $alt_title;


                                // Include the swiper card for each report
                                include "swiper-card.php";
                            }
                        } else {
                            echo "<p>No recent purchases found.</p>";
                        }
                        ?>
                    </div>
                    <!-- Add Arrows -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- How It Works Section Start -->
 <div class="container mt-5">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h3>How It Works</h3>
            <p>Follow these simple steps to get started with our platform.</p>
        </div>
    </div>
    <div class="row mt-4 gy-4">
        <!-- Step 1 -->
        <div class="col-lg-4 text-center">
            <div class="how-it-works-card" style="background-color: #f8f9fa; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div class="icon mb-3" style="position: relative; display: inline-block; width: 80px; height: 80px; background-color: #F57C00; border-radius: 50%; color: #fff; line-height: 80px; font-size: 30px;">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h5>Step 1: Sign Up</h5>
                <p>Create an account on our platform to access premium reports and projects.</p>
            </div>
        </div>
        <!-- Step 2 -->
        <div class="col-lg-4 text-center">
            <div class="how-it-works-card" style="background-color: #f8f9fa; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div class="icon mb-3" style="position: relative; display: inline-block; width: 80px; height: 80px; background-color: #F57C00; border-radius: 50%; color: #fff; line-height: 80px; font-size: 30px;">
                    <i class="fas fa-search"></i>
                </div>
                <h5>Step 2: Browse Reports</h5>
                <p>Explore a wide range of categories and find the perfect report for your needs.</p>
            </div>
        </div>
        <!-- Step 3 -->
        <div class="col-lg-4 text-center">
            <div class="how-it-works-card" style="background-color: #f8f9fa; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div class="icon mb-3" style="position: relative; display: inline-block; width: 80px; height: 80px; background-color: #F57C00; border-radius: 50%; color: #fff; line-height: 80px; font-size: 30px;">
                    <i class="fas fa-download"></i>
                </div>
                <h5>Step 3: Download & Use</h5>
                <p>Purchase and download the report instantly to start using it for your project.</p>
            </div>
        </div>
    </div>
</div>
<!-- How It Works Section End -->


<!-- Become an Affiliate Section -->
<div class="container mt-5 mb-5">
    <div class="row align-items-center" style="background-color: #212121; border-radius: 15px; padding: 30px; color: #fff;">
        <div class="col-lg-6 order-lg-2">
            <img src="img/affiliate.jpg" alt="Become an Affiliate" class="img-fluid" style="border-radius: 15px;">
        </div>
        <div class="col-lg-6 order-lg-1">
            <h2 class="text-white">Become an <span class="text-orange">Affiliate Partner</span></h2>
            <p class="text-white">Earn while you share! Join our affiliate program and start earning commissions by
promoting ProjectReportHub.ng. Simply share our academic resources and project
reports with your network—whether online or offline—and get rewarded for every
successful referral.
</p>
            <a href="become_an_affliate.php" class="btn-2 mt-3">Start earning today — Join as an Affiliate</a>
        </div>
    </div>
</div>

<!-- Recent Reports Section Start -->
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
        $alt_title = $row['alt_title'];
        $description = $row['description'];
        $category = $row['category'];
        $subcategory = $row['subcategory'];
        $pricing = $row['pricing'];
        $price = $row['price'];
        $tags = $row['tags'];
        $loyalty = $row['loyalty'];
        $user = $row['display_name'];
        $user_picture = $imagePath.$row['profile_picture'];
        $created_date = $row['created_date'];
        $updated_date = $row['updated_date'];
        $status = $row['status'];
        $image_path = $imagePath.$row['picture'];
        $slug = $alt_title;

        include "product-card.php";
}
?>
</div>
<div class="col-lg-12 mt-1">
<div class="text-right"> <a href="marketplace.php" class="btn-kayd">View More</a></div>
<?php } else {  debug('No reports not found.'); }?>

</div></div></div></div>
<div class="container mt-5">
    <div class="row align-items-center" style="background-color: #f8f9fa; border-radius: 15px; padding: 30px; padding-bottom:0px;">
        <div class="col-lg-6">
            <h2>Ready to Get Started?</h2>
            <p>Join ProjectReportHub.ng today and elevate your academic or entrepreneurial 
journey with access to expertly crafted reports, research materials, and innovative 
project ideas. </p>
            <a href="signup.php" class="btn_1">SIGN-UP NOW</a>
        </div>
        <div class="col-lg-6">
            <img src="img/get-started.png" alt="CTA Image" class="img-fluid" style="border-radius:2px;">
        </div>
    </div>
</div>

<!-- Categories Section Start -->
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <h3>Resource Categories</h3>
            <p> Discover our wide selection of verified reports and cutting-edge projects, carefully
curated to meet your academic and research needs.
</p>
            <div class="row">
                <?php
                $query = "SELECT * FROM ".$siteprefix."categories WHERE parent_id IS NULL";
                $result = mysqli_query($con, $query);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $category_id = $row['id'];
                        $category_name = $row['category_name'];
                        $slugs = strtolower(str_replace(' ', '-', $category_name));
                        
                        ?>
                        <div class="col-md-3 mb-3">
                                <a href="category/<?php echo $slugs; ?>" class="btn btn-dark btn-block w-100">
                                <?php echo $category_name; ?>
                            </a>
                           
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




<!-- Testimonials Section Start -->
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="text-center">What Our Clients Say</h3>
            <p class="text-center">Hear from our satisfied customers who have benefited from our platform.</p>
        </div>
    </div>
    <div class="row mt-4 gy-4">
        <!-- Testimonial 1 -->
        <div class="col-lg-4">
            <div class="testimonial-card" style="background-color: #f8f9fa; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <p style="font-style: italic;">"I was able to find the perfect report for my project. The quality of the report was top-notch and the delivery was prompt."</p>
                <h5 style="margin-top: 15px; font-weight: bold; color: #F57C00;">Chidera Obioma Okafor</h5>
                <span style="color: #6c757d;">Undergraduate</span>
            </div>
        </div>
        <!-- Testimonial 2 -->
        <div class="col-lg-4">
            <div class="testimonial-card" style="background-color: #f8f9fa; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <p style="font-style: italic;">"The platform is user-friendly, and I was able to access high-quality reports that helped me complete my project successfully."</p>
                <h5 style="margin-top: 15px; font-weight: bold; color: #F57C00;">Ayodele Olumide Adebayo
                </h5>
                <span style="color: #6c757d;">Masters Student</span>
            </div>
        </div>
        <!-- Testimonial 3 -->
        <div class="col-lg-4">
            <div class="testimonial-card" style="background-color: #f8f9fa; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <p style="font-style: italic;">"This platform has been a game-changer for my business. The reports are detailed and well-researched."</p>
                <h5 style="margin-top: 15px; font-weight: bold; color: #F57C00;"> Aminu Idris Muhammad </h5>
                <span style="color: #6c757d;">PHD Student</span>
            </div>
        </div>
    </div>
</div>
<!-- Testimonials Section End -->




<!-- Become a Seller Section -->
<div class="container mt-5 mb-5">
    <div class="row align-items-center" style="background-color: #f8f9fa; border-radius: 15px; padding: 30px;">
        <div class="col-lg-6">
            <img src="img/seller.png" alt="Become a Seller" class="img-fluid" style="border-radius: 15px;">
        </div>
        <div class="col-lg-6">
            <h2>Become a <span class="text-orange">Seller</span></h2>
            <p>Turn your knowledge into income. Join ProjectReportHub.ng as a seller and share
your academic reports, research work, and study materials with thousands of
students nationwide. Earn while empowering others to succeed.
</p>
            <a href="become_a_seller.php" class="btn-kayd mt-3">Get Started as a Seller</a>
        </div>
    </div>
</div>



<?php include "footer.php"; ?>