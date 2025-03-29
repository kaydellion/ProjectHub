<?php
include "header.php";
checkActiveLog($active_log);

// Fetch current loyalty status
$query = "SELECT ".$siteprefix."loyalty_purchases.*, ".$siteprefix."subscription_plans.name AS plan_name, ".$siteprefix."subscription_plans.price AS plan_price  
          FROM ".$siteprefix."loyalty_purchases
          JOIN ".$siteprefix."subscription_plans 
          ON ".$siteprefix."loyalty_purchases.loyalty_id = ".$siteprefix."subscription_plans.s 
          WHERE ".$siteprefix."loyalty_purchases.user_id = '$user_id' 
          ORDER BY ".$siteprefix."loyalty_purchases.end_date DESC";
$result = mysqli_query($con, $query);
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="text-primary">Loyalty Status</h1>
            <p class="text-muted">View your subscription history and resubscribe to a plan.</p>
        </div>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row mt-4">
            <div class="col-lg-12">
                <h3 class="text-secondary">Subscription History</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Plan Name</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $row['plan_name'] ?></td>
                                    <td>₦<?= $row['amount'] ?></td>
                                    <td><?= date("F j, Y, g:i A", strtotime($row['start_date'])) ?></td>
                                    <td><?= date("F j, Y, g:i A", strtotime($row['end_date'])) ?></td>
                                    <td>
                                        <?php if (strtotime($row['end_date']) > time()): ?>
                                            <span class="badge bg-success text-white">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger text-white">Expired</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (strtotime($row['end_date']) <= time()): ?>
                                            <button class="btn btn-primary btn-sm" onclick="resubscribe(<?= $row['loyalty_id'] ?>, <?= $row['plan_price'] ?>, '<?= $row['plan_name'] ?>', <?= $user_id ?>)">Resubscribe</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row mt-5">
            <div class="col-lg-12 text-center">
                <p class="text-muted">You have no purchase history.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    function resubscribe(planId, amount, planName, userId) {
        var handler = PaystackPop.setup({
            key: 'pk_test_3156df58b30d0b29f8319737a485b5b31fd97c9c', // Replace with your Paystack public key
            email: '<?= $email ?>', // Replace with the logged-in user's email
            amount: amount * 100, // Paystack expects the amount in kobo (multiply by 100)
            currency: 'NGN', // Currency in Nigerian Naira
            ref: 'PH-' + Math.floor((Math.random() * 1000000000) + 1), // Generate a unique reference
            metadata: {
                custom_fields: [
                    {
                        display_name: "Plan Name",
                        variable_name: "plan_name",
                        value: planName
                    }
                ]
            },
            callback: function(response) {
                alert('Payment successful! Reference: ' + response.reference);
                window.location.href = `backend/verify_payment.php?reference=${response.reference}&plan_id=${planId}&user_id=${userId}`;
            },
            onClose: function() {
                alert('Payment canceled.');
            }
        });
        handler.openIframe();
    }
</script>
<?php include "footer.php"; ?>