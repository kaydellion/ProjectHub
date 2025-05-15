<?php include "header.php";

// Fetch all profits
$sql = "SELECT * FROM ".$siteprefix."profits ORDER BY date DESC";
$stmt = $con->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">All Profits</h2>

    <?php if ($result->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>S/N</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Report ID</th>
                        <th>Order ID</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php 
                    $sn = 1; // Initialize the serial number counter
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $sn++; ?></td> <!-- Increment the counter -->
                            <td>₦<?php echo formatNumber($row['amount'], 2); ?></td>
                            <td><?php echo $row['type']; ?></td>
                            <td><?php echo $row['report_id']; ?></td>
                            <td>#<?php echo $row['order_id']; ?></td>
                            <td><?php echo formatDateTime($row['date']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">No profits recorded yet.</div>
    <?php } ?>
</div>

<?php include "footer.php"; ?>