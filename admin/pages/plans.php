<?php include "header.php"; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Subscription Plans Table -->
    <div class="card">
        <h5 class="card-header">Manage Subscription Plans</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Plan Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Discount</th>
                        <th>Downloads</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Benefits</th>
                        <th>Image</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php 
                    $query = "SELECT * FROM " . $siteprefix . "subscription_plans ORDER BY created_at DESC";
                    $result = mysqli_query($con, $query);
                    
                    if (!$result) {
                        die('Query Failed: ' . mysqli_error($con));
                    }

                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $plan_id = $row['s'];
                        $plan_name = $row['name'];
                        $plan_price = $row['price'];
                        $plan_description = $row['description'];
                        $plan_discount = $row['discount'];
                        $plan_downloads = $row['downloads'];
                        $plan_duration = $row['duration'];
                        $plan_status = $row['status'];
                        $plan_benefits = $row['benefits'];
                        $plan_image = $row['image'];
                        $created_at = $row['created_at'];
                    ?>
                        <tr>
                            <td><strong><?php echo $i; ?></strong></td>
                            <td><?php echo $plan_name; ?></td>
                            <td><?php echo $plan_price; ?></td>
                            <td><?php echo $plan_description; ?></td>
                            <td><?php echo $plan_discount; ?>%</td>
                            <td><?php echo $plan_downloads; ?></td>
                            <td><?php echo $plan_duration; ?> Days</td>
                            <td><span class="badge bg-label-<?php echo getBadgeColor($plan_status); ?> me-1"><?php echo $plan_status; ?></span></td>
                            <td><?php echo $plan_benefits; ?></td>
                            <td>
                                <img src="../../uploads/<?php echo $plan_image; ?>" alt="Plan Image" width="50">
                            </td>
                            <td><?php echo $created_at; ?></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary text-small dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i> Manage
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="edit-plan.php?plan=<?php echo $plan_id; ?>">
                                            <i class="bx bx-edit-alt me-1"></i> Edit Plan
                                        </a>
                                        <a class="dropdown-item delete" href="delete.php?action=deleteplans&table=subscription_plans&item=<?php echo $plan_id; ?>&page=<?php echo $current_page; ?>">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php $i++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>