<?php include "header.php"; ?>

 <div class="container mt-5">
        <div class="row">
        <div class="col-lg-12">
        <h3>LOYALTY PROGRAM.</h3>
        <p>Our subscription program offers a cost-effective and convenient way to access project reports, insights, and exclusive discounts.</p>
</div>
</div>
       
        <div class="row mt-3 plans">
        <?php
$query = "SELECT * FROM ".$siteprefix."subscription_plans WHERE status = 'active' ORDER BY s DESC";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $plan_id = $row['s'];
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
        $discount = $row['discount'];
        $downloads = $row['downloads'];
        $duration = $row['duration'];
        $benefits = explode(',', $row['benefits']); // Convert benefits into an array
        $status = $row['status'];
        $image_path = !empty($row['image']) ? $imagePath.$row['image'] : $imagePath."default4.jpg";
        $created_at = $row['created_at'];

        include "plan-card.php"; // Include your plan display template
    }
} else {
    die('No subscription plans found.');
}
?>

</div></div>
        <?php include "footer.php"; ?>