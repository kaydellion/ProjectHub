<?php include "header.php";
checkActiveLog($active_log);
?>

 <div class="container mt-5">
        <div class="row">
        <div class="col-lg-12">
        <h3>LOYALTY PROGRAM.</h3>
        <p>Our subscription program offers a cost-effective and convenient way for users to access a repository of reports and insights without the hassle of individual purchases. 
        This model ensures continuous access to the latest information, empowering users to make timely and well-informed choices.Buyers on our platform can subscribe to any of the 
        plans below and enjoy massive discounts our reports, services and trainings.</p>
            
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
    die('No subscription plans found.');
}
?>

</div></div>
<!-- Modal for login prompt -->
<<!-- Modal for login prompt -->
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
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("payButton").addEventListener("click", function() {
            let button = this;
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
                key: 'pk_test_3156df58b30d0b29f8319737a485b5b31fd97c9c', // Replace with live key in production
                email: email,
                amount: amount,
                currency: 'NGN',
                ref: 'PH-' + Date.now() + '-' + Math.floor(Math.random() * 1000),
                metadata: {
                    custom_fields: [{ display_name: "Plan Name", variable_name: "plan_name", value: planName }]
                },
                callback: function(response) {
                    window.location.href = `backend/verify_payment.php?action=verify_payment&reference=${response.reference}&plan_id=${planId}&user_id=${userId}`;
                },
                onClose: function() {
                    alert('Payment was canceled.');
                }
            });
            handler.openIframe();
        });
    });
</script>