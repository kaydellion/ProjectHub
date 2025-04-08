<?php include "header.php"; ?>

<?php
// Query to count total users
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM ".$siteprefix."users WHERE type = 'user'"; 
$totalUsersResult = mysqli_query($con, $totalUsersQuery);
$totalUsers = mysqli_fetch_assoc($totalUsersResult)['total_users'];

// Query to calculate total profit
$totalProfitQuery = "SELECT SUM(amount) AS total_profit FROM ".$siteprefix."profits";
$totalProfitResult = mysqli_query($con, $totalProfitQuery);
$totalProfit = mysqli_fetch_assoc($totalProfitResult)['total_profit'];

// Query to count total reports
$totalReportsQuery = "SELECT COUNT(*) AS total_reports FROM ".$siteprefix."reports";
$totalReportsResult = mysqli_query($con, $totalReportsQuery);
$totalReports = mysqli_fetch_assoc($totalReportsResult)['total_reports'];

// Query to count total sales (paid orders)
$totalSalesQuery = "SELECT COUNT(order_id) AS total_sales FROM ".$siteprefix."orders WHERE status = 'paid'";
$totalSalesResult = mysqli_query($con, $totalSalesQuery);
$totalSales = mysqli_fetch_assoc($totalSalesResult)['total_sales'];
?>

<div class="container mt-4">
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text"><?php echo $totalUsers; ?></p>
                </div>
            </div>
        </div>

        <!-- Total Sales Card -->
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <p class="card-text"><?php echo $totalSales; ?></p>
                </div>
            </div>
        </div>

        <!-- Total Reports Card -->
        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Reports</h5>
                    <p class="card-text"><?php echo $totalReports; ?></p>
                </div>
            </div>
        </div>

        <!-- Total Profit Card -->
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Profit</h5>
                    <p class="card-text">₦<?php echo number_format($totalProfit, 2); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$latestSalesQuery = "
    SELECT 
        o.order_id, 
        o.user, 
        o.total_amount, 
        o.status, 
        o.date AS created_at, 
        oi.report_id, 
        r.title, 
        u.display_name 
    FROM 
        ".$siteprefix."orders o
    JOIN 
        ".$siteprefix."order_items oi ON o.order_id = oi.order_id
    JOIN 
        ".$siteprefix."reports r ON r.id = oi.report_id
    JOIN 
        ".$siteprefix."users u ON o.user = u.s
    WHERE 
        o.status = 'paid'
    ORDER BY 
        o.date DESC 
    LIMIT 10
";
$latestSalesResult = mysqli_query($con, $latestSalesQuery);

?>
<div class="container mt-5">
    <h3 class="mb-4">Recent Sales</h3>
    <div class="table-responsive text-nowrap">
    <table class="table table-hover">
    <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Report Name</th>
                    <th>Total Amount (₦)</th>
                   
                    <th>Date</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php if (mysqli_num_rows($latestSalesResult) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($latestSalesResult)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['display_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td>₦<?php echo number_format($row['total_amount'], 2); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No sales found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include "footer.php"; ?>