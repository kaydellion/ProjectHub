<?php include "header.php";

// Fetch reviews for reports uploaded by the seller
$sql = "SELECT r.report_id, r.user, r.rating, r.review, r.date 
        FROM ".$siteprefix."reviews r
        JOIN ".$siteprefix."reports p ON r.report_id = p.id
        WHERE p.user = ?
        ORDER BY r.date DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">My Reviews</h2>

    <?php if ($result->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Report ID</th>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td>#<?php echo $row['report_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['user']); ?></td>
                            <td>
                                <?php 
                                    $rating = intval($row['rating']); 
                                    echo str_repeat("⭐", $rating); // Display stars based on rating
                                ?>
                            </td>
                            <td><?php echo nl2br(htmlspecialchars($row['review'])); ?></td>
                            <td><?php echo date("d M Y, h:i A", strtotime($row['date'])); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">You have no reviews yet.</div>
    <?php } ?>
</div>

<?php include "footer.php"; ?>
