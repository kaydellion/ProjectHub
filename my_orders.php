<?php include "header.php";

// Pagination setup
$limit = 10; // Number of orders per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Current page
$offset = ($page - 1) * $limit; // Offset for the SQL query

// Fetch total number of orders for pagination
$total_query = "SELECT COUNT(*) AS total_orders FROM " . $siteprefix . "orders WHERE user = ? AND status = 'paid'";
$total_stmt = $con->prepare($total_query);
$total_stmt->bind_param("s", $user_id);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_orders = $total_row['total_orders'];
$total_pages = ceil($total_orders / $limit); // Total number of pages

// Fetch user's orders with pagination
$sql = "
    SELECT order_id, date, total_amount, status 
    FROM " . $siteprefix . "orders 
    WHERE user  = ? AND status = 'paid'
    ORDER BY date DESC
    LIMIT ? OFFSET ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("sii", $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

?>

<div class="container mt-5 mb-5 d-flex justify-content-center">
    <div class="col-lg-10">
        <h2 class="mb-4 text-center">My Orders</h2>

        <?php if ($result->num_rows > 0) { ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-justify">
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
                                <td>₦<?php echo formatNumber($row['total_amount'], 2); ?></td>
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

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php } else { ?>
            <div class="alert alert-info text-center">You have no orders yet.</div>
        <?php } ?>
    </div>
</div>

<?php include "footer.php"; ?>