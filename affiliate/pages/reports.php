<?php include "header.php"; ?>

<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Hoverable Table rows -->
    <div class="card">
        <h5 class="card-header">Manage Reports</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Report Title</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Pricing</th>
                        <th>Price</th>
                        <th></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php 
                    $query = "SELECT r.*, l.category_name AS category, sc.category_name AS subcategory 
                              FROM ".$siteprefix."reports r 
                              LEFT JOIN ".$siteprefix."categories l ON r.category = l.id 
                              LEFT JOIN ".$siteprefix."categories sc ON r.subcategory = sc.id";
                    $result = mysqli_query($con, $query);
                    if (!$result) {
                        die('Query Failed: ' . mysqli_error($con));
                    }
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $report_id = $row['id'];
                        $title = $row['title'];
                        $category = $row['category'];
                        $subcategory = $row['subcategory'];
                        $pricing = $row['pricing'];
                        $price = $row['price'];
                        $tags = $row['tags'];

                    ?>
                    <tr>
                        <td><strong><?php echo $i; ?></strong></td>
                        <td><?php echo htmlspecialchars($title); ?></td>
                        <td><?php echo htmlspecialchars($category); ?></td>
                        <td><?php echo htmlspecialchars($subcategory); ?></td>
                        <td><?php echo htmlspecialchars($pricing); ?></td>
                        <td><?php echo htmlspecialchars($price); ?></td>
                        
                        <td>
                            <!-- View Product Button -->
                            <a href="../../product.php?id=<?php echo $report_id; ?>" target="_blank" class="btn btn-info btn-sm me-2">View Product</a>
                        </td>
                        <td>
                            <?php if ($pricing != 'free'): ?>
                                <?php
                                $check_query = "SELECT * FROM ".$siteprefix."affiliate_products WHERE user_id = '$user_id' AND product_id = '$report_id'";
                                $check_result = mysqli_query($con, $check_query);

                                if (mysqli_num_rows($check_result) > 0):
                                    $affiliate_data = mysqli_fetch_assoc($check_result);
                                    $affiliate_link = $affiliate_data['affiliate_link'];
                                    $encoded_affiliate_link = $affiliate_link; // Encode the affiliate link
                                ?>
                                    <!-- Copy Affiliate Link -->
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="affiliate_link_<?php echo $report_id; ?>" value="<?php echo htmlspecialchars($encoded_affiliate_link, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                        <button class="btn btn-primary btn-sm" onclick="copyToClipboard('<?php echo $report_id; ?>')">Copy Link</button>
                                    </div>
                                <?php else: ?>
                                    <!-- Add to List Button -->
                                    <form method="post">
                                    
                                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                        <input type="hidden" name="affliate_id" value="<?php echo $affliate; ?>">
                                        <input type="hidden" name="product_id" value="<?php echo $report_id; ?>">
                                        <button type="submit" class="btn btn-success btn-sm" name="add_to_affiliate_list">Add to List</button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $i++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(reportId) {
        var copyText = document.getElementById("affiliate_link_" + reportId);
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand("copy");
        alert("Copied: " + copyText.value);
    }
</script>

<?php include "footer.php"; ?>