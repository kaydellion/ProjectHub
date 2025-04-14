
<?php
include "header.php"; // Include the header file

// Fetch all product reports
$query = "SELECT pr.product_id, pr.user_id, pr.reason, pr.report_date, 
                 u.display_name AS user_name, u.email AS user_email, 
                 r.title AS product_title 
          FROM " . $siteprefix . "product_reports pr
          LEFT JOIN " . $siteprefix . "users u ON pr.user_id = u.s
          LEFT JOIN " . $siteprefix . "reports r ON pr.product_id = r.id
          ORDER BY pr.report_date DESC";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($con));
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Product Reports</h4>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Name</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['product_title']; ?></td>
                                <td><?php echo $row['user_name']; ?></td>
                                <td><?php echo $row['reason']; ?></td>
        
                                <td><?php echo date('Y-m-d H:i:s', strtotime($row['report_date'])); ?></td>
                                <td>
                                    <!-- View Button -->
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewReportModal<?php echo $row['product_id']; ?>">View</button>
                                </td>
                            </tr>

                            <!-- Modal for Viewing Report -->
                            <div class="modal fade" id="viewReportModal<?php echo $row['product_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewReportModalLabel<?php echo $row['product_id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewReportModalLabel<?php echo $row['product_id']; ?>">Report Details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Title:</strong> <?php echo htmlspecialchars($row['product_title']); ?></p>
                                            <p><strong>Name:</strong> <?php echo htmlspecialchars($row['user_name']); ?></p>
                                       
                                            <p><strong>Date:</strong> <?php echo date('Y-m-d H:i:s', strtotime($row['report_date'])); ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No product reports found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>