<?php include "header.php"; ?>

<?php
// Check if the user is a seller
checkActiveLog($active_log);
if ($seller != 1) {
    header("Location: index.php");
    exit;
}

// Fetch resources sold with total revenue
$sql = "
    SELECT 
        r.title AS resource_title,
        COUNT(oi.s) AS total_sold,
        SUM(oi.price) AS total_revenue
    FROM {$siteprefix}order_items oi
    JOIN {$siteprefix}orders o ON oi.order_id = o.order_id
    JOIN {$siteprefix}reports r ON r.id = oi.report_id
    WHERE r.user = ? 
      AND o.status = 'paid'
    GROUP BY r.id
    ORDER BY total_sold DESC
";

// Prepare and execute the query
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $user_id); // If user_id is integer, use "i"
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <h2 class="mb-4">Resources Sold</h2>

    <?php if ($result && $result->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Resource Title</th>
                        <th>Total Sold</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['resource_title']); ?></td>
                            <td><?php echo $row['total_sold']; ?></td>
                            <td><?php echo $sitecurrency . number_format($row['total_revenue'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">No resources sold yet.</div>
    <?php } ?>
</div>

<?php include "footer.php"; ?>