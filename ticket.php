<?php 
include "header.php";

// Get ticket number from URL
$ticket_id = $_GET['ticket_number'];
$sql = "SELECT id,user_id, ticket_number, category, order_reference, status, issue 
    FROM ".$siteprefix."disputes 
    WHERE ticket_number = ?";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $ticket_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $user_id = $row['user_id'];
    $ticket_number = $row['ticket_number'];
    $category = $row['category'];
    $order_reference = $row['order_reference'];
    $issue = $row['issue'];
    $status = $row['status'];
    $dispute_id = $row['id'];
} else {
    // Redirect if ticket not found
    header("Location: $previousPage");
    exit();
}



?>


<div class="container py-5">
<div class="row">
<div class="col-md-12">
<div class="d-flex justify-content-between align-items-center mb-4">
<h4>Dispute Resolution - <?php echo $issue; ?>
<?php
//select evidence
$evidence_sql = "SELECT * FROM ".$siteprefix."evidence WHERE dispute_id = '$dispute_id'";
$evidence_result = mysqli_query($con, $evidence_sql);
while ($evidence = mysqli_fetch_assoc($evidence_result)) {
    if (!empty($evidence['file'])) {
        $evidenceFiles = explode(',', $evidence['file']);
        echo '<div class="mt-2">';
        foreach ($evidenceFiles as $file) {
            $filename = basename(trim($file));
            echo '<a href="'.$imagePath.htmlspecialchars($file).'" target="_blank" 
                    class="btn btn-sm btn-outline-primary me-2 mb-2">
                    View Evidence: '.htmlspecialchars($filename).'
                </a>';
        }
        echo '</div>';
    }
}?></h4>
<span class="badge bg-<?php getBadgeColor($status); ?>"><?php echo $status; ?></span>
</div>


<div class="card mb-4">
<div class="card-body">
<h5 class="mb-3">Ticket Messages</h5>
    <?php
    $msg_sql = "SELECT m.*,u.display_name as name,u.profile_picture as profile_image
     FROM ".$siteprefix."dispute_messages m
    JOIN ".$siteprefix."users u ON m.sender_id = u.s
     WHERE dispute_id = '$ticket_id' ORDER BY created_at DESC";
    $msg_result = mysqli_query($con, $msg_sql);
    // Debug: Check if query successful
    if (!$msg_result) {
        echo "Debug - Query Error: " . mysqli_error($con) . "<br>";
    }while ($message = mysqli_fetch_assoc($msg_result)) {
        $image=  $imagePath.$message['profile_image'];
        $files = $message['file'] ? explode(',', $message['file']) : [];
        ?>
        <div class="d-flex mb-3">
            <div class="flex-shrink-0">
                <img src="<?= $image ?>" 
                     class="rounded-circle" width="50" height="50" alt="User">
            </div>
            <div class="flex-grow-1 ms-3">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-1"><?= htmlspecialchars($message['name']) ?></h6>
                    <small class="text-muted"><?= date('M d, Y H:i', strtotime($message['created_at'])) ?></small>
                </div>
                <p class="mb-1"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                <?php if ($message['file']): ?>
                       <?php 
              if (!empty($files)) {
              echo '<div class="mt-2">';
              foreach ($files as $file) {
              $filename = basename(trim($file));
              echo '<a href=" '.$imagePath.htmlspecialchars($file) . '" target="_blank" 
                        class="btn btn-sm btn-outline-primary me-2 mb-2">
                        View ' . htmlspecialchars($filename) . '
                    </a>';
            } echo '</div>'; } ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>
</div>

<?php if ($status == 'pending' || $status == 'awaiting-response'): ?>
<h4>Send Message</h4>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="dispute_id" value="<?= $ticket_id; ?>">
    <p><textarea name="message" class="form-control" rows="3" required></textarea></p>
    <div class="mb-3">
        <label for="fileUpload" class="form-label">Attach Files (Max 5MB)</label>
        <input type="file" class="form-control" id="fileUpload" name="attachment[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
    </div>
    <button type="submit" name="send_dispute_message" class="btn-kayd w-100" value="submit">Send Message</button>
</form>
<?php endif; if ($status == 'under-review'):  ?>
<h4>Resolve Dispute Under Review</h4>
<p>This dispute is currently under review by our support team, we will get back to you soonest</p>
<?php endif; ?>
<?php if ($status == 'resolved'):  ?>
<h4>Dispute Resolved</h4>
<p>This dispute has been resolved, you can view the resolution above</p>
<?php endif; ?>














</div>
</div>
</div>
<?php include "footer.php"; ?>