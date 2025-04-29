<?php 
include "header.php";

// Get ticket number from URL
$ticket_id = $_GET['ticket_number'];
$sql = "SELECT id, user_id, ticket_number, category, recipient_id,order_reference, status, issue 
    FROM ".$siteprefix."disputes 
    WHERE ticket_number = '$ticket_id'";

$result = mysqli_query($con, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $sender_id = $row['user_id'];
    $recipient_id = $row['recipient_id'];
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

//details
if($recipient_id){
$rDetails = getUserDetails($con, $siteprefix, $recipient_id);
$recipient_email = $rDetails['email'];
$recipient_name = $rDetails['display_name'];
$recipient_wallet = $rDetails['wallet'];}

$sDetails = getUserDetails($con, $siteprefix, $sender_id);
$sender_email = $sDetails['email'];
$sender_name = $sDetails['display_name'];
$sender_wallet = $sDetails['wallet'];
?>


<div class="container py-5">
<div class="row">
<div class="col-md-12">
<div class="d-flex justify-content-between align-items-center mb-4">
<h6>Dispute Resolution - <?php echo $issue; ?>
<br> (Order: <?php echo $order_reference; ?>/Ticket: <?php echo $ticket_number; ?>)
<?php
//select evidence
$evidence_sql = "SELECT * FROM ".$siteprefix."evidence WHERE dispute_id = '$dispute_id'";
$evidence_result = mysqli_query($con, $evidence_sql);
while ($evidence = mysqli_fetch_assoc($evidence_result)) {
    if (!empty($evidence['file_path'])) {
        $evidenceFiles = explode(',', $evidence['file_path']);
        echo '<div class="mt-2">';
        foreach ($evidenceFiles as $file) {
            $filename = basename(trim($file));
            echo '<a href="'.$siteurl.$imagePath.htmlspecialchars($file).'" target="_blank" 
                    class="btn btn-sm btn-outline-primary me-2 mb-2">
                    View Evidence: '.htmlspecialchars($filename).'
                </a>';
        }
        echo '</div>';
    }
}?></h6>
<span class="badge bg-<?php echo getBadgeColor($status); ?>"><?php echo $status; ?></span>
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
    }
    if (mysqli_num_rows($msg_result) == 0) {
        echo '<p>No messages found</p>';
    }  
       while ($message = mysqli_fetch_assoc($msg_result)) {
        $image=  $siteurl.$imagePath.$message['profile_image'];
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
              echo '<a href=" '.$siteurl.$imagePath.htmlspecialchars($file) . '" target="_blank" 
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

<h5>Actions</h5>
<div class="mb-4">
    <input type="hidden" id="ticket_id" value="<?= $ticket_id; ?>">
    <select class="form-select mb-2" id="statusAction">
        <option value="">Select Action...</option>
        <option value="resolved">Mark as Resolved</option>
        <option value="under-review">Mark as Under Review</option>
    </select>
    <button onclick="updateStatus()" class="btn btn-primary mb-3">Update Status</button>
    
    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#walletModal">
        Manage User Wallet
    </button>
</div>

<!-- Wallet Modal -->
<div class="modal fade" id="walletModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage User Wallet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="walletForm">
                    <div class="mb-3">
                        <input type="hidden" name="dispute_id" value="<?= $ticket_id; ?>">
                        <input type="hidden" name="update-wallet-dispute" value="update-wallet-dispute">
                        <label class="form-label">Select User</label>
                        <select class="form-select" name="user" required>
                            <option value="<?php echo $sender_id; ?>"><?php echo $sender_name ?>(<?php echo $sitecurrency.$sender_wallet ?>)</option>
                           <?php if($recipient_id){?>
                            <option value="<?php echo $recipient_id; ?>"><?php echo $recipient_name; ?>(<?php echo $sitecurrency.$recipient_wallet?>)</option> <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Action</label>
                        <select class="form-select" name="wallet-action" required>
                            <option value="add">Add Funds</option>
                            <option value="deduct">Deduct Funds</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateWallet()">Submit</button>
            </div>
        </div>
    </div>
</div>

<h4>Send Message</h4>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="dispute_id" value="<?= $ticket_id; ?>">
    <p><textarea name="message" class="form-control" rows="3" required></textarea></p>
    <div class="mb-3">
        <label for="fileUpload" class="form-label">Attach Files (Max 5MB)</label>
        <input type="file" class="form-control" id="fileUpload" name="attachment[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
    </div>
    <button type="submit" name="send_dispute_message" class="btn btn-primary w-100" value="submit">Send Message</button>
</form>
<?php if ($status == 'resolved'):  ?>
<h4>Dispute Resolved</h4>
<p>This dispute has been resolved, you can view the resolution above</p>
<?php endif; ?>















</div>
</div>
</div>
<?php include "footer.php"; ?>