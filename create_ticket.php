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
        <option value="Non-payment">Non-payment</option>
        <option value="Quality Issue">Quality Issue</option>
    </select></div> 
    
    <div class="mb-3"> <label>Order Reference:</label>
    <select name="order_id" id="order_id" class="form-control" required onchange="getOrderDetails(this.value)">
        <?php 
        $sql = "SELECT * FROM ".$siteprefix."orders WHERE user = '$user_id' ORDER BY date DESC";
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($result)): ?>
            <option value="<?= $row['order_id']; ?>"><?= $row['order_id']; ?>(<?= $row['status']; ?>)</option>
        <?php endwhile; ?>
    </select></div>

    <div id="orderDetails" class="mb-3">
        <!-- Order details will be loaded here -->
    </div>

    <script>
    function getOrderDetails(orderId) {
        $.ajax({
            url: 'get_order_details.php',
            type: 'POST',
            data: { order_id: orderId },
            success: function(response) {
                $('#orderDetails').html(response);
            }
        });
    }
    </script>

    <div class="mb-3"><label>Recipient Involved:</label>
    <input type="text" name="recipient" class="form-control" required>
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