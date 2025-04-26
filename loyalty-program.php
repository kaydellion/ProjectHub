<?php include "header.php";
checkActiveLog($active_log);
?>

 <div class="container mt-5">
        <div class="row">
        <div class="col-lg-12">
            <h3>Loyalty Program</h3>
            <p>
                Project Report Hub NG is a premier platform dedicated to providing high-quality, up-to-date academic and research reports, including project papers, core study materials, assignments & research works, laboratory & practical works, exam preparation & study aids, and presentation & seminar materials across a wide range of disciplines.
            </p>
            <p>
                Backed by a robust network of researchers, analysts, and verified data sources, we deliver in-depth content that spans market trends, industry forecasts, competitive analysis, and strategic insights.
            </p>
            <p>
                To make access even more affordable and seamless, we offer a flexible subscription program. This allows users to unlock unlimited access to our rich library of reports and resources—without the need for repeated individual purchases.
            </p>
            <p>
                With a subscription, you’re always connected to the latest research and insights to support your academic or professional goals.
            </p>
            <p>
                Explore our subscription plans below and enjoy exclusive discounts on all academic resources programs available on our platform.
            </p>
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
        $no_of_duration = $row['no_of_duration'];
        $image_path = !empty($row['image']) ? $imagePath.$row['image'] : $imagePath."default4.jpg";
        $created_at = $row['created_at'];

        include "plan-card.php"; // Include your plan display template
    }
} else {
    debug('No subscription plans found.');
}
?>

</div></div>
<!-- Modal for login prompt -->
<!-- Modal for login prompt -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Login Required</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You need to be logged in to subscribe to a plan.</p>
                <a href="signin.php" class="btn btn-primary">Go to Login</a>
            </div>
        </div>
    </div>
</div>


<?php include "footer.php"; ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let payButtons = document.querySelectorAll(".payButton");

    payButtons.forEach(function(button) {
        button.addEventListener("click", function () {
            let planId = button.dataset.planId;
            let amount = parseFloat(button.dataset.amount) * 100; // Convert to kobo
            let planName = button.dataset.planName;
            let userId = button.dataset.userId;
            let email = button.dataset.email;

            if (!email || isNaN(amount)) {
                alert("Invalid payment details. Please try again.");
                return;
            }

            var handler = PaystackPop.setup({
                key: '<?php echo $apikey; ?>', // Replace with live key in production
                email: email,
                amount: amount,
                currency: 'NGN',
                ref: 'PH-' + Date.now() + '-' + Math.floor(Math.random() * 1000),
                metadata: {
                    custom_fields: [{
                        display_name: "Plan Name",
                        variable_name: "plan_name",
                        value: planName
                    }]
                },
                callback: function (response) {
                    window.location.href = `https://projectreporthub.ng/backend/verify_payment.php?action=verify_payment&reference=${response.reference}&plan_id=${planId}&user_id=${userId}`;
                },
                onClose: function () {
                    alert('Payment was canceled.');
                }
            });
            handler.openIframe();
        });
    });
});

</script>

