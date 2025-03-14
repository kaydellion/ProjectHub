<div class="col-md-4 mb-4">
    <div class="plan-card shadow-lg border-0 text-center">
        <div class="plan-card-body">
            <img src="<?= $image_path ?>" alt="<?= $name ?>" class="img-fluid rounded">
            <h5 class="plan-card-title"><?= $name ?></h5>
            <p class="plan-card-text"><?= $description ?></p>
            <h4 class="plan-card-price">₦<?= number_format($price) ?></h4>
            <small class="text-muted">Duration: <?= $duration ?></small>
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
            <a href="subscribe.php?plan=<?= $plan_id ?>" class="plan-card-btn">Subscribe Now</a>
        </div>
    </div>
</div>
