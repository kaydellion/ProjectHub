<?php
include "header.php"; // Include the header
checkActiveLog($active_log);

// Check if the user is logged in and is a seller
if ($seller == 1) {
    // Fetch all users following the seller
    $followersQuery = "SELECT u.s AS user_id, u.first_name, u.last_name, u.email, u.type AS user_type 
                       FROM {$siteprefix}followers f
                       JOIN {$siteprefix}users u ON f.user_id = u.s
                       WHERE f.seller_id = $user_id";
    $followersResult = mysqli_query($con, $followersQuery);
    $followers = [];
    while ($row = mysqli_fetch_assoc($followersResult)) {
        $followers[] = $row;
    }


} else {
    // Redirect non-sellers to the dashboard
    header("Location: dashboard.php");
    exit;
}
?>

<div class="container py-5">
    <h2 class="mb-4">Your Followers</h2>
    <?php if (!empty($followers)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($followers as $follower): ?>
                    <?php
                    // Check if the seller is already following this user
                    $checkFollowQuery = "SELECT * FROM {$siteprefix}followers WHERE user_id = $user_id AND seller_id = " . $follower['user_id'];
                    $checkFollowResult = mysqli_query($con, $checkFollowQuery);
                    $isFollowing = mysqli_num_rows($checkFollowResult) > 0;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($follower['first_name'] . ' ' . $follower['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($follower['email']); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $follower['user_id']; ?>">
                                <input type="hidden" name="seller_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="follow_seller_submit" value="1">

                                <?php if ($isFollowing): ?>
                                    <!-- Unfollow Button -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" id="followingDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            Following
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="followingDropdown">
                                            <li>
                                                <button type="submit" name="actioning" value="unfollow" class="dropdown-item">
                                                    Unfollow
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                <?php else: ?>
                                    <!-- Follow Button -->
                                    <button type="submit" name="actioning" value="follow" class="btn btn-outline-primary btn-sm">
                                        Follow
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No followers found.</p>
    <?php endif; ?>
</div>

<?php include "footer.php"; // Include the footer ?>