<?php include "header.php"; 
$sql = "SELECT d.*, u.first_name,u.last_name
        FROM ".$siteprefix."disputes d 
        LEFT JOIN ".$siteprefix."users u ON d.user_id = u.s 
        WHERE d.status='pending' 
        ORDER BY d.created_at DESC";
$result = mysqli_query($con, $sql);
?>


<div class="container py-5">
<div class="row">
<div class="col-md-12">
<div class="d-flex justify-content-between align-items-center mb-4">
<h3>Pending Disputes</h3>
</div>
<table class="table table-bordered border-primary">
    <tr>
        <th>Ticket ID</th>
        <th>Category</th>
        <th>Reporter</th>
        <th>Reported Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['ticket_number']; ?></td>
            <td><?= $row['category']; ?></td>
            <td><?= $row['first_name']." ".$row['last_name']; ?></td>
            <td><?= $row['created_at']; ?></td>
          
            <td><span class="badge bg-<?php echo getBadgeColor($row['status']); ?>"><?= $row['status']; ?></span></td>
            <td><a href="ticket.php?ticket_number=<?= $row['ticket_number']; ?>">View Ticket</a></td>
        </tr>
    <?php endwhile; ?>
</table>

</div>
</div>
</div>
<?php include "footer.php"; ?>