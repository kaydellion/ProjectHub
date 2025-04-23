<?php include "header.php";  ?> 

<div class="container py-5">
<div class="row">
<div class="col-md-12">
<div class="d-flex justify-content-between align-items-center mb-4">
<h3>Create a Ticket</h3>
<a href="tickets.php" class="btn btn-primary">Back to tickets</a>
</div>

<form method="POST" enctype="multipart/form-data">
    
<div class="mb-3">
<label>Dispute Category:</label>
    <select name="category" class="form-control"  required>
    <option value="Product Quality Issues">Product Quality Issues</option>
        <option value="Wrong Item Received">Wrong Item Received</option>
        <option value="Item Not Delivered">Item Not Delivered</option>
        <option value="Refund Issues">Refund Issues</option>
        <option value="Login/Access Problems">Login/Access Problems</option>
        <option value="Account Security">Account Security</option>
        <option value="Technical Bugs">Technical Bugs</option>
        <option value="User Experience Issues">User Experience Issues</option>
        <option value="Poor Support Experience">Poor Support Experience</option>
        <option value="Policy Disputes">Policy Disputes</option>
        <option value="Loyalty Program">Loyalty Program</option>
        <option value="Affiliate Program">Affiliate Program</option>
        <option value="Fake or Misleading Reviews">Fake or Misleading Reviews</option>
        <option value="Payment Issues">Payment Issues</option>
    </select></div> 
    
    <div class="mb-3"> <label>Order Reference:</label>
    <select name="order_id" id="order_id" class="form-control" required onchange="getOrderDetails(this.value)">
        <option value="">Select Order</option>
        <?php 
        $sql = "SELECT * FROM ".$siteprefix."orders WHERE user = '$user_id' ORDER BY date DESC";
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($result)): ?>
            <option value="<?= $row['order_id']; ?>"><?= $row['order_id']; ?>(<?= $row['status']; ?>)</option>
        <?php endwhile; ?>
    </select></div>

   <p>Recipient Involved</p>
   <div id="orderDetails">
        <!-- Order details will be loaded here -->
    </div>

    
    <div class="mb-3"><label>Issue Title:</label>
    <textarea name="issue" class="form-control" maxlength="100" rows="3"></textarea>
    </div>
    
    <div class="mb-3"><label>Upload Evidence:</label>
    <input type="file" name="evidence[]" class="form-control" multiple required>
    </div>

    <button type="submit" class="btn-kayd w-100" name="create_dispute" value="dispute">Start Dispute</button>
</form>

</div>
</div>
</div>
<?php include "footer.php"; ?>