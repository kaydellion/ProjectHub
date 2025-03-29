<div class="col-md-4 mb-4">
    <div class="plan-card shadow-lg border-0 text-center">
        <div class="plan-card-body">
            <img src="<?= $image_path ?>" alt="<?= $name ?>" class="plan-card-img">
            <h5 class="plan-card-title"><?= $name ?></h5>
            <p class="plan-card-text"><?= $description ?></p>
            <h4 class="plan-card-price">₦<?= number_format($price) ?></h4>
              <!-- Display formatted duration -->
              <small class="text-muted">
                Duration: 
                <?php
                if ($duration === 'Monthly') {
                    echo $no_of_duration . ' ' . ($no_of_duration > 1 ? 'Months' : 'Month');
                } elseif ($duration === 'Yearly') {
                    echo $no_of_duration . ' ' . ($no_of_duration > 1 ? 'Years' : 'Year');
                }
                ?>
            </small>
            <ul class="list-group list-group-flush mt-3">
                <li class="list-group-item"><strong>Discount:</strong> <?= $discount ?>% Off</li>
                <li class="list-group-item"><strong>Downloads:</strong> <?= $downloads ?> reports</li>
                <li class="list-group-item">
                    <strong>Benefits:</strong>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($benefits as $benefit): ?>
                            <li>✅ <?= trim($benefit) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
            <?php

$current_date = date("Y-m-d H:i:s");

// Check if the user has an active subscription
$subscription_query = "SELECT * FROM " . $siteprefix . "loyalty_purchases
    WHERE user_id = $user_id AND loyalty_id = $plan_id AND end_date > '$current_date' LIMIT 1";
$subscription_result = mysqli_query($con, $subscription_query);
$user_has_active_plan = mysqli_num_rows($subscription_result) > 0;
?>
          
                <?php if ($active_log == 1): ?>
                <?php if ($user_has_active_plan): ?>
                    <a href="loyalty-status.php" class="btn btn-primary">Manage Subscription</a>
                <?php else: ?>
                   <!-- Pay Button with Data Attributes -->
<button id="payButton" class="btn btn-primary"
    data-plan-id="<?= $plan_id ?>"
    data-amount="<?= $price ?>"
    data-plan-name="<?= htmlspecialchars($name, ENT_QUOTES) ?>"
    data-user-id="<?= $user_id ?>"
    data-email="<?= $email ?>">
    Subscribe
</button>
                <?php endif; ?>
            <?php else: ?>
                <!-- If user is not logged in, show the modal trigger -->
                <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Subscribe</button>
            <?php endif; ?>
        </div>
    </div>
</div>

