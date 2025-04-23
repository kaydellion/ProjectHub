<?php include "header.php";
?>
<?php
// Fetch the number of pending or payment resend manual orders
$pendingOrResendQuery = "SELECT COUNT(*) AS pending_or_resend_count 
                         FROM ".$siteprefix."manual_payments 
                         WHERE user_id = ? AND status IN ('pending', 'payment resend')";
$stmt = $con->prepare($pendingOrResendQuery);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$pendingOrResendResult = $stmt->get_result();
$pendingOrResendRow = $pendingOrResendResult->fetch_assoc();
$pendingOrResendCount = $pendingOrResendRow['pending_or_resend_count'] ?? 0;

// Fetch the number of approved manual orders
$approvedQuery = "SELECT COUNT(*) AS approved_count 
                  FROM ".$siteprefix."manual_payments 
                  WHERE user_id = ? AND status = 'approved'";
$stmt = $con->prepare($approvedQuery);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$approvedResult = $stmt->get_result();
$approvedRow = $approvedResult->fetch_assoc();
$approvedCount = $approvedRow['approved_count'] ?? 0;
?>

<?php
// Fetch manual payments
$sql = "
    SELECT order_id, date_created AS date, amount AS total_amount, status, rejection_reason 
    FROM ".$siteprefix."manual_payments 
    WHERE user_id = ? 
    ORDER BY date DESC";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<div class="container mt-5">
    <div class="row mb-4">
        <!-- Pending or Payment Resend Manual Orders -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Pending/Payment Resend</h5>
                    <p class="card-text text-white"><?php echo $pendingOrResendCount; ?></p>
                </div>
            </div>
        </div>

        <!-- Approved Manual Orders -->
        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Approved Orders</h5>
                    <p class="card-text text-white"><?php echo $approvedCount; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container mt-5 mb-5">
    <h2 class="mb-4">My Manual Orders</h2>

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
                                <span class="badge bg-<?php echo ($row['status'] == 'approved') ? 'success' : (($row['status'] == 'pending') ? 'danger' : 'warning'); ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'payment resend') { ?>
                                    <!-- Button to update proof of payment -->
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateProofModal<?php echo $row['order_id']; ?>">
                                        Update Proof
                                    </button>
                                <?php } else { ?>
                                    <!-- Button to view order details -->
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewOrderModal<?php echo $row['order_id']; ?>">
                                        View Details
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>

                        <?php if ($row['status'] == 'payment resend') { ?>
                            <!-- Modal for updating proof of payment -->
                            <div class="modal fade" id="updateProofModal<?php echo $row['order_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateProofModalLabel<?php echo $row['order_id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateProofModalLabel<?php echo $row['order_id']; ?>">Update Proof of Payment</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="proof_of_payment">Upload New Proof of Payment</label>
                                                    <input type="file" class="form-control" id="proof_of_payment" name="proof_of_payment" required>
                                                </div>
                                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary" name="update_proof">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <!-- Modal for viewing order details -->
                            <div class="modal fade" id="viewOrderModal<?php echo $row['order_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewOrderModalLabel<?php echo $row['order_id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewOrderModalLabel<?php echo $row['order_id']; ?>">Order Details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Order ID:</strong> #<?php echo $row['order_id']; ?></p>
                                            <p><strong>Date:</strong> <?php echo formatDateTime($row['date']); ?></p>
                                            <p><strong>Total Amount:</strong> ₦<?php echo number_format($row['total_amount'], 2); ?></p>
                                            <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
                                            <?php if ($row['status'] == 'payment resend' && !empty($row['rejection_reason'])) { ?>
                                                <p><strong>Rejection Reason:</strong> <?php echo htmlspecialchars($row['rejection_reason']); ?></p>
                                            <?php } ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">You have no manual orders yet.</div>
    <?php } ?>

</div>

<?php include "footer.php"; ?>