<?php include "header.php"; ?>

<div class="container mt-4">
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-md-3">
            <a href="users.php">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Users</h5>
                        <p class="card-text counter" data-target="<?php echo $totalUsers; ?>">0</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Total Sales Card -->
        <div class="col-md-3">
            <a href="transactions.php">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-white">No of Orders</h5>
                        <p class="card-text counter" data-target="<?php echo $totalSales; ?>">0</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Total Reports Card -->
        <div class="col-md-3">
            <a href="reports.php">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Reports</h5>
                        <p class="card-text counter" data-target="<?php echo $totalReports; ?>">0</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Total Profit Card -->
        <div class="col-md-3">
            <a href="profits.php">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Profit</h5>
                        <p class="card-text counter" 
                           data-target="<?php echo number_format($totalProfit, 2, '.', ''); ?>" 
                           data-currency="<?php echo $sitecurrency; ?>">0</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending Reports Card -->
<div class="col-md-3">
    <a href="pending-reports.php">
        <div class="card text-white bg-dark mb-3">
            <div class="card-body">
                <h5 class="card-title text-white">Pending Reports</h5>
                <p class="card-text counter" data-target="<?php echo $pendingReportsCount; ?>">0</p>
            </div>
        </div>
    </a>
</div>

<!-- Pending Payments Card -->
<div class="col-md-3">
    <a href="pending-orders.php">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title text-white">Pending Payments</h5>
                <p class="card-text counter" data-target="<?php echo $pendingPaymentsCount; ?>">0</p>
            </div>
        </div>
    </a>
</div>

<!-- Pending Withdrawals Card -->
<div class="col-md-3">
    <a href="pending-withdrawals.php">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title text-white">Pending Withdrawals</h5>
                <p class="card-text counter" data-target="<?php echo $pendingWithdrawalsCount; ?>">0</p>
            </div>
        </div>
    </a>
</div>

<!-- Pending Disputes Card -->
<div class="col-md-3">
    <a href="new-disputes.php">
        <div class="card text-white bg-dark mb-3">
            <div class="card-body">
                <h5 class="card-title text-white">Pending Disputes</h5>
                <p class="card-text counter" data-target="<?php echo $pendingDisputesCount; ?>">0</p>
            </div>
        </div>
    </a>
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

<!-- Counter Animation Script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const counters = document.querySelectorAll('.counter');

    counters.forEach(counter => {
        const target = parseFloat(counter.getAttribute('data-target'));
        const currency = counter.getAttribute('data-currency') || '';
        const isDecimal = target % 1 !== 0;
        let count = 0;
        const duration = 1000; // animation duration in ms
        const increment = target / (duration / 10); // increment per step

        const updateCount = () => {
            count += increment;
            if (count < target) {
                counter.innerText = currency + (isDecimal ? count.toFixed(2) : Math.floor(count));
                setTimeout(updateCount, 10);
            } else {
                counter.innerText = currency + (isDecimal ? target.toFixed(2) : Math.floor(target));
            }
        };

        updateCount();
    });
});
</script>
<?php include "footer.php"; ?>