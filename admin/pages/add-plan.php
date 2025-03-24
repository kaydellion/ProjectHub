<?php include "header.php"; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <form method="POST" >
                <p class="text-bold text-dark">Create a new subscription plan.</p>

                <div class="form-row row">
                    <div class="form-group col-md-6 mb-3">
                        <label for="planName">Plan Name</label>
                        <input type="text" class="form-control" id="planName" name="planName" required>
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="planPrice">Plan Price (₦)</label>
                        <input type="number" class="form-control" id="planPrice" name="planPrice" required>
                    </div>
                </div>
                 <!-- File Upload Field -->
    <div class="form-group mb-3">
    <label for="planImage">Upload Plan Image (JPG, PNG, GIF)</label>
    <input type="file" class="form-control" id="planImage" name="planImage" accept="image/*" required>
    </div>

    <div class="form-group mb-3">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>


                <div class="form-row row">
                    <div class="form-group col-md-6 mb-3">
                        <label for="discount">Discount (%)</label>
                        <input type="number" class="form-control" id="discount" name="discount" required>
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="downloads">Number of Downloads</label>
                        <input type="number" class="form-control" id="downloads" name="downloads" required>
                    </div>
                </div>

                <div class="form-row row">
    <div class="form-group col-md-6 mb-3">
        <label for="planDuration">Plan Duration</label>
        <select class="form-select" id="planDuration" name="planDuration" required onchange="handleDurationChange()">
            <option selected>- Select Duration -</option>
            <option value="Monthly">Monthly</option>
            <option value="Yearly">Yearly</option>
        </select>
    </div>
    <div class="form-group col-md-6 mb-3" id="durationField" style="display: none;">
        <label for="durationCount" id="durationLabel">Number of Months</label>
        <input type="number" class="form-control" id="durationCount" name="durationCount" min="1" placeholder="Enter number">
    </div>
</div>

                    <div class="mb-3">
                        <label for="planStatus">Plan Status</label>
                        <select class="form-select" id="planStatus" name="planStatus" required>
                            <option selected>- Select Status -</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
              

                <div class="mb-3">
                    <label>Additional Benefits:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="benefit1" name="benefits[]" value="Exclusive Training">
                        <label class="form-check-label" for="benefit1">Exclusive Training</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="benefit2" name="benefits[]" value="Priority Support">
                        <label class="form-check-label" for="benefit2">Priority Support</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="benefit3" name="benefits[]" value="Early Access Reports">
                        <label class="form-check-label" for="benefit3">Early Access Reports</label>
                    </div>
                </div>

                <p><button class="w-100 btn btn-primary" name="addPlan" value="add-plan">Create Plan</button></p>
            </form>
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
