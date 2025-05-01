
<?php
include "header.php"; // Include the header
checkActiveLog($active_log);

// Check if the user is logged in and is a seller
if ($seller == 1) {
    // Fetch all users the seller is following
    $followingQuery = "SELECT u.s AS user_id, u.first_name, u.last_name, u.email, u.type AS user_type 
                       FROM {$siteprefix}followers f
                       JOIN {$siteprefix}users u ON f.seller_id = u.s
                       WHERE f.user_id = $user_id";
    $followingResult = mysqli_query($con, $followingQuery);
    $following = [];
    while ($row = mysqli_fetch_assoc($followingResult)) {
        $following[] = $row;
    }

    // Handle unfollow action

} else {
    // Redirect non-sellers to the dashboard
    header("Location: dashboard.php");
    exit;
}
?>

<div class="container py-5 d-flex justify-content-center">
<div class="col-lg-10">
    <h2 class="mb-4">Users You Are Following</h2>
    <?php if (!empty($following)): ?>
       
        <table class="table table-striped table-bordered align-middle shadow-sm rounded text-justify">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php foreach ($following as $followed): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($followed['first_name'] . ' ' . $followed['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($followed['email']); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $followed['user_id']; ?>">
                                <input type="hidden" name="seller_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="follow_seller_submit" value="1">

                                <!-- Unfollow Button -->
                                <div class="dropdown">
                                    <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" id="followingDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Following
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="followingDropdown">
                                        <li>
                                            <button type="submit" name="actionings" value="unfollow" class="dropdown-item">
                                                Unfollow
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
                
    <?php else: ?>
        <p>You are not following anyone.</p>
    <?php endif; ?>
</div>
</div>
<?php include "footer.php"; // Include the footer ?>