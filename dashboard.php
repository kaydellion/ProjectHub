<?php include "header.php"; ?>


<section>
<div class="row bg-dark p-3 mt-5 mb-5">
  <div class="col-lg-2 col-12">
    <img src="<?php echo $imagePath.'/'; echo $profile_picture; ?>" alt="Avatar" class="img-fluid rounded-circle">
  </div>
  <div class="col-lg-10 col-12 d-flex align-items-center pt-3 mb-5">
  <div class="d-flex flex-wrap">
    <?php include "links.php"; ?>
</div>
        <h2 class="title text-primary text-bold mt-3">Hi, <?php echo htmlspecialchars($username); ?></h2>
    <?php

    //if user is not a seller yet
    if($seller==0){ echo "<p><a href='contract.php?user_login=$user_id&name=$first_name $middle_name $last_name&address=$address&display_name=$display_name&email=$email&phone=$mobile_number' class='btn-kayd m-3'>Become a seller</a></p>";}
    // Fetch last 4 notifications where status is 0
    $sql = "SELECT message, date FROM ".$siteprefix."notifications WHERE user = ? AND status = 0 ORDER BY date DESC LIMIT 4";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <?php foreach ($notifications as $notification): ?>
    <p class="text-light"><?php echo htmlspecialchars($notification['message']); ?></p>
    <?php endforeach; ?>
    </div>
  </div> 
</div>




<!-- Loyalty Program CTA Start -->
<div class="container mt-5">
    <div class="row align-items-center" style="background-color: #f8f9fa; border-radius: 15px; padding: 30px;">
        <div class="col-lg-6">
            <img src="img/seller.png" alt="Become a Seller" class="img-fluid" style="border-radius: 15px;">
        </div>
        <div class="col-lg-6">
            <h2>Join Our  <span class="text-orange">Loyalty Program</span></h2>
            <p>Our loyalty program offers exclusive benefits and rewards for our valued customers. By joining, you gain access to special discounts, early access to new reports, and other perks that enhance your experience and provide greater value.</p>
            <a href="loyalty-program.php" class="btn-kayd mt-3">Start Saving Now</a>
        </div>
    </div>
</div>









<?php include "footer.php"; ?>