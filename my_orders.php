<?php include "header.php";

// Fetch user's orders (only regular orders with status 'paid')
$sql = "
    SELECT order_id, date, total_amount, status 
    FROM ".$siteprefix."orders 
    WHERE user = ? AND status = 'paid'
    ORDER BY date DESC";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">My Orders</h2>

    <?php if ($result->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td>#<?php echo $row['order_id']; ?></td>
                            <td><?php echo formatDateTime($row['date']); ?></td>
                            <td>₦<?php echo number_format($row['total_amount'], 2); ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($row['status'] == 'paid') ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="order_details.php?order_id=<?php echo $row['order_id']; ?>" class="text-small btn btn-kayd btn-sm">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">You have no orders yet.</div>
    <?php } ?>

</div>

<?php include "footer.php"; ?>