<?php include "header.php";


checkActiveLog($active_log);
if ($seller != 1) {
 header("Location: index.php");

}
// Fetch sales where the report belongs to the seller
$sql = "
    SELECT 
        wh.amount,
        oi.order_id,
        o.date,
        r.title,
        p.title AS file_type
    FROM {$siteprefix}order_items oi
    JOIN {$siteprefix}orders o ON oi.order_id = o.order_id
    JOIN {$siteprefix}reports_files p ON oi.item_id = p.id
    LEFT JOIN {$siteprefix}reports r ON r.id = oi.report_id
    LEFT JOIN {$siteprefix}wallet_history wh 
      ON wh.reason = CONCAT('Payment from Order ID: ', oi.order_id) 
      AND wh.status = 'credit'
    WHERE r.user = ? 
      AND o.status = 'paid'
    ORDER BY o.date DESC
";

// ✅ Prepare & Bind
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $user_id); // If user_id is integer use "i"
$stmt->execute();
$result = $stmt->get_result();

// ✅ Initialize counters
$total_amount = 0;
$total_resources_sold = 0;

// ✅ Loop through results
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_amount += (float)$row['amount']; // Use wallet_history.amount
        $total_resources_sold++;
    }
    $result->data_seek(0); // Reset pointer if needed later
}

?>
<div class="container mt-5">
    <div class="row mb-4">
        <!-- Total Amount Earned -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Amount Earned</h5>
                    <p class="card-text text-white"><?php echo $sitecurrency . formatNumber($total_amount, 2); ?></p>
                </div>
            </div>
        </div>
        <!-- Total Resources Sold -->
        <div class="col-md-3">
            <a href="resources-sold.php">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Resources Sold</h5>
                    <p class="card-text text-white"><?php echo $total_resources_sold; ?></p>
                </div>
            </div>
            </a>
        </div>
    </div>
</div>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">My Sales</h2>

    <?php if ($result->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>File</th>
                        <th>File Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td>#<?php echo $row['order_id']; ?></td>
                            <td><?php echo formatDateTime($row['date']); ?></td>
                            <td><?php echo $sitecurrency;echo formatNumber($row['amount'], 2); ?></td>
                            <td><?php echo $row['title']; ?> </td>
                            <td><?php echo getFileExtension($row['file_type']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">You have no sales yet.</div>
    <?php } ?>
</div>

<?php include "footer.php"; ?>