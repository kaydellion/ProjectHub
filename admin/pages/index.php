<?php include "header.php"; ?>

<div class="container mt-4">
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-md-3"><a href="users.php">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Users</h5>
                    <p class="card-text"><?php echo $totalUsers; ?></p>
                </div>
            </div></a>
        </div>

        <!-- Total Sales Card -->
        <div class="col-md-3"><a href="transactions.php">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">No of Orders</h5>
                    <p class="card-text"><?php echo $totalSales; ?></p>
                </div>
            </div></a>
        </div>

        <!-- Total Reports Card -->
        <div class="col-md-3"><a href="reports.php">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Reports</h5>
                    <p class="card-text"><?php echo $totalReports; ?></p>
                </div>
            </div></a>
        </div>

        <!-- Total Profit Card -->
        <div class="col-md-3"><a href="profits.php">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Profit</h5>
                    <p class="card-text"><?php echo $sitecurrency; echo number_format($totalProfit, 2); ?></p>
                </div>
            </div></a>
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
    <p><a href="transactions.php" class="btn btn-primary">View all sales</a></p>
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