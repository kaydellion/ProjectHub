<?php include "header.php"; 
checkActiveLog($active_log);
$sql = "SELECT * FROM ".$siteprefix."disputes WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($con, $sql);
?>


<div class="container py-5">
<div class="row">
<div class="col-md-12">
<div class="d-flex justify-content-between align-items-center mb-4">
<h3>Dispute Resolution - My Tickets</h3>
<a href="create_ticket.php" class="btn btn-primary">Create New Ticket</a>
</div>
<table class="table table-bordered border-primary">
    <tr>
        <th>Ticket ID</th>
        <th>Category</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['ticket_number']; ?></td>
            <td><?= $row['category']; ?></td>
            <td><span class="badge bg-<?php echo getBadgeColor($row['status']); ?>"><?= $row['status']; ?></span></td>
            <td><a href="ticket.php?ticket_number=<?= $row['ticket_number']; ?>">View Ticket</a></td>
        </tr>
    <?php endwhile; ?>
</table>

</div>
</div>
</div>
<?php include "footer.php"; ?>