
<?php
include "header.php";  // Include your database connection

// Fetch pending manual payments
$query = "SELECT mp.s, mp.order_id, mp.user_id, mp.amount, mp.proof, mp.date_created, u.display_name, u.email 
          FROM " . $siteprefix . "manual_payments mp
          JOIN " . $siteprefix . "users u ON mp.user_id = u.s
          WHERE mp.status = 'payment resend'";
$result = mysqli_query($con, $query);
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">Manage Manual Payments</h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th class="text-nowrap">Date Submitted</th>
                        <th class="text-nowrap">Proof of Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sn = 1; // Initialize serial number
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td class="text-nowrap"><?php echo $sn++; ?></td> <!-- Increment S/N -->
                            <td class="text-nowrap"><?php echo $row['order_id']; ?></td>
                            <td class="text-nowrap"><?php echo $row['display_name'] . " (" . $row['email'] . ")"; ?></td>
                            <td class="text-nowrap"><?php echo $sitecurrency . number_format($row['amount'], 2); ?></td>
                            <td class="text-nowrap"><?php echo date('Y-m-d H:i:s', strtotime($row['date_created'])); ?></td>
                            <td class="text-nowrap">
                                <a href="<?php echo $siteurl.$imagePath.$row['proof']; ?>" target="_blank" class="btn btn-info btn-sm">View Proof</a>
                            </td>
                            <td class="text-nowrap">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#orderDetailsModal<?php echo $row['s']; ?>">View Details</button>
                                <form method="post"  onsubmit="return confirmApprove();">
    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
    <input type="hidden" name="amount" value="<?php echo $row['amount']; ?>">
    <button type="submit" class="btn btn-success btn-sm" name="approve_payment">Approve</button>
</form>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectConfirmationModal<?php echo $row['s']; ?>">Reject</button>
                            </td>
                        </tr>

                        <!-- Order Details Modal -->
                        <div class="modal fade" id="orderDetailsModal<?php echo $row['s']; ?>" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel<?php echo $row['s']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="orderDetailsModalLabel<?php echo $row['s']; ?>">Order Details</h5>
                                        <button type="button" class="close btn-danger" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Order ID:</strong> <?php echo $row['order_id']; ?></p>
                                        <p><strong>User:</strong> <?php echo $row['display_name'] . " (" . $row['email'] . ")"; ?></p>
                                        <p><strong>Amount:</strong> <?php echo $sitecurrency . number_format($row['amount'], 2); ?></p>
                                        <p><strong>Date Submitted:</strong> <?php echo date('Y-m-d H:i:s', strtotime($row['date_created'])); ?></p>
                                        <p><strong>Proof of Payment:</strong> <a href="<?php echo $siteurl.$imagePath.$row['proof']; ?>" target="_blank">View Proof</a></p>
                                    </div>
                                    <div class="modal-footer">
                                    <form method="post" onsubmit="return confirmApprove();">
    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
    <input type="hidden" name="amount" value="<?php echo $row['amount']; ?>">
    <button type="submit" class="btn btn-success btn-sm" name="approve_payment">Approve</button>
</form>
<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectConfirmationModal<?php echo $row['s']; ?>" data-dismiss="modal">Reject</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Confirmation Modal -->
                        <div class="modal fade" id="rejectConfirmationModal<?php echo $row['s']; ?>" tabindex="-1" role="dialog" aria-labelledby="rejectConfirmationModalLabel<?php echo $row['s']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rejectConfirmationModalLabel<?php echo $row['s']; ?>">Confirm Rejection</h5>
                                        <button type="button" class="close btn-danger" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to reject this payment?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <!-- Trigger the Rejection Reason Modal -->
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectionReasonModal<?php echo $row['s']; ?>" data-dismiss="modal">Reject</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rejection Reason Modal -->
                        <div class="modal fade" id="rejectionReasonModal<?php echo $row['s']; ?>" tabindex="-1" role="dialog" aria-labelledby="rejectionReasonModalLabel<?php echo $row['s']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rejectionReasonModalLabel<?php echo $row['s']; ?>">Reason for Rejection</h5>
                                        <button type="button" class="close btn-danger" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="post">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="rejectionReason">Please provide a reason for rejecting this payment:</label>
                                                <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3" required></textarea>
                                            </div>
                                            <!-- Hidden inputs for order_id and user_id -->
                                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger" name="reject_payment">Submit Rejection</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function confirmApprove() {
        return confirm("Are you sure you want to approve this payment?");
    }
</script>
<?php include "footer.php"; ?>