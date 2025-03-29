<?php include "header.php";

// Fetch sales where the report belongs to the seller
$sql = "SELECT oi.order_id, o.date, oi.price
        FROM ".$siteprefix."order_items oi
        JOIN ".$siteprefix."orders o ON oi.order_id = o.order_id
        JOIN  ".$siteprefix."reports_files p ON oi.item_id = p.id
        LEFT JOIN ".$siteprefix."reports r ON r.id = oi.report_id
        WHERE r.user = ? 
        AND o.status = 'paid'
        ORDER BY o.date DESC";
        
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

?>


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
                        <th>File Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td>#<?php echo $row['order_id']; ?></td>
                            <td><?php echo formatDateTime2($row['date']); ?></td>
                            <td>₦<?php echo number_format($row['price'], 2); ?></td>
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
