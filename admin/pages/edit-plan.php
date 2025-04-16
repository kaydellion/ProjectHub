<?php include "header.php"; 

$plan_id = $_GET['plan'] ?? null;
if (!$plan_id) {
  header("Location: plans.php");
  exit();
}

// Fetch the plan details
$query = "SELECT * FROM " . $siteprefix . "subscription_plans WHERE s = '$plan_id'";
$result = mysqli_query($con, $query);
if (!$result) {
    die('Query Failed: ' . mysqli_error($con));
}
$row = mysqli_fetch_assoc($result);

if ($row) {
    $name = $row['name'];
    $price = $row['price'];
    $description = $row['description'];
    $discount = $row['discount'];
    $downloads = $row['downloads'];
    $duration = $row['duration'];
    $no_of_duration = $row['no_of_duration'];
    $status = $row['status'];
    $benefits = $row['benefits'];
    $image = $row['image'];
    $created_at = $row['created_at'];
} else {
    debug('Plan not found.');
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Plan</h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <h6>Basic Information</h6>
                        <div class="mb-3">
                           <!-- <label class="form-label" for="plan-id">Plan ID</label>  -->
                            <input type="hidden" id="plan-id" name="id" class="form-control" value="<?php echo $plan_id; ?>" readonly required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="plan-name">Plan Name</label>
                            <input type="text" class="form-control" name="name" id="plan-name" value="<?php echo $name; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="plan-description">Description</label>
                            <textarea id="plan-description" name="description" class="form-control"><?php echo $description; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="plan-price">Price</label>
                            <input type="number" id="plan-price" name="price" class="form-control" step="0.01" value="<?php echo $price; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="plan-discount">Discount (%)</label>
                            <input type="number" id="plan-discount" name="discount" class="form-control" value="<?php echo $discount; ?>">
                        </div>
                        <div class="mb-3">
                        <label for="downloads">Number of Downloads</label>
                        <input type="number" class="form-control" id="downloads" name="downloads" value=<?php echo $downloads; ?> required>
                    </div>
            
                      
   
<!-- Plan Duration -->
<div class="mb-3">
    <label for="planDuration">Plan Duration</label>
    <select class="form-select" id="planDuration" name="planDuration" required onchange="handleDurationChange()">
        <option value="">- Select Duration -</option>
        <option value="Monthly" <?php echo ($duration == "Monthly") ? "selected" : ""; ?>>Monthly</option>
        <option value="Yearly" <?php echo ($duration == "Yearly") ? "selected" : ""; ?>>Yearly</option>
    </select>
</div>

<!-- Number of Duration -->
<div class="mb-3" id="durationField" style="display: <?php echo (!empty($duration)) ? 'block' : 'none'; ?>;">
    <label for="durationCount" id="durationLabel">
        <?php
        if ($duration == "Monthly") {
            echo "Number of Months";
        } elseif ($duration == "Yearly") {
            echo "Number of Years";
        }
        ?>
    </label>
    <input type="number" class="form-control" id="durationCount" name="durationCount" min="1" value="<?php echo $row['no_of_duration']; ?>" placeholder="Enter number">
</div>
    <!-- Additional Benefits -->
    <div class="mb-3">
        <label>Additional Benefits:</label>

        <?php 
        // Convert stored benefits into an array
        $selected_benefits = explode(", ", $benefits); 
        ?>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="benefit1" name="benefits[]" value="Exclusive Training" 
                <?php echo (in_array("Exclusive Training", $selected_benefits)) ? "checked" : ""; ?>>
            <label class="form-check-label" for="benefit1">Exclusive Training</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="benefit2" name="benefits[]" value="Priority Support" 
                <?php echo (in_array("Priority Support", $selected_benefits)) ? "checked" : ""; ?>>
            <label class="form-check-label" for="benefit2">Priority Support</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="benefit3" name="benefits[]" value="Early Access Reports" 
                <?php echo (in_array("Early Access Reports", $selected_benefits)) ? "checked" : ""; ?>>
            <label class="form-check-label" for="benefit3">Early Access Reports</label>
        </div>
    </div>


                        <div class="mb-3">
                            <label class="form-label">Current Image</label><br>
                            <?php if (!empty($image)): ?>
                                <img src="../../uploads/<?php echo $image; ?>" class="img-fluid" width="150">
                            <?php else: ?>
                                <p>No image available</p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="plan-image">Upload New Image</label>
                            <input type="file" class="form-control" id="plan-image" name="image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active" <?php echo ($status == 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" name="updatePlan" class="btn btn-primary w-100">Update Plan</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function handleDurationChange() {
        const durationSelect = document.getElementById('planDuration');
        const durationField = document.getElementById('durationField');
        const durationLabel = document.getElementById('durationLabel');
        const selectedValue = durationSelect.value;

        if (selectedValue === 'Monthly') {
            durationField.style.display = 'block';
            durationLabel.textContent = 'Number of Months';
        } else if (selectedValue === 'Yearly') {
            durationField.style.display = 'block';
            durationLabel.textContent = 'Number of Years';
        } else {
            durationField.style.display = 'none';
        }
    }
</script>
<?php include "footer.php"; ?>
