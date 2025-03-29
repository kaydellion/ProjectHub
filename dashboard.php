<?php include "header.php"; ?>


<section>
<div class="row bg-dark p-5 mt-5 mb-5">
  <div class="col-lg-2 col-12">
    <img src="<?php echo $imagePath.'/'; echo $profile_picture; ?>" alt="Avatar" class="img-fluid rounded-circle">
  </div>
  <div class="col-lg-10 col-12 d-flex align-items-center pt-3 mb-5">
    <div class="d-flex flex-column">
        <div class="d-flex">
           <?php include "links.php"; ?>
        </div>
        <h2 class="title text-primary text-bold mt-3">Hi, <?php echo htmlspecialchars($username); ?></h2>
    <?php
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













<?php include "footer.php"; ?>