<?php include "header.php"; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Recent Orders -->
    <div class="card">
        <h5 class="card-header">Recent Orders</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Number of Sales</th>
                        <th>Total Money Made</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php
                    $query = "
                        SELECT 
                            oi.report_id AS product_id, 
                            COUNT(oi.s) AS sales_count, 
                            SUM(oi.price) AS total_revenue,
                            MAX(o.date) AS recent_date
                        FROM 
                            ".$siteprefix."order_items oi
                        JOIN 
                            ".$siteprefix."orders o ON oi.order_id = o.order_id
                        WHERE 
                            oi.affiliate_id = '$affliate' AND o.status = 'paid'
                        GROUP BY 
                            oi.report_id
                        ORDER BY 
                            recent_date DESC
                        LIMIT 5
                    ";
                    $result = mysqli_query($con, $query);

                    if (!$result) {
                        die('Query Failed: ' . mysqli_error($con));
                    }

                    $i = 1;
                    $modals = ""; // Store modals separately

                    while ($row = mysqli_fetch_assoc($result)) {
                        $report_id = $row['product_id'];
                        $sales_count = $row['sales_count'];
                        $total_revenue = $row['total_revenue'];

                        // Get the product name from the reports table
                        $product_query = "SELECT title FROM ".$siteprefix."reports WHERE id = '$report_id'";
                        $product_result = mysqli_query($con, $product_query);
                        $product_data = mysqli_fetch_assoc($product_result);
                        $product_name = $product_data['title'] ?? 'Unknown Product';

                        // Get the product image from the reports_images table
                        $image_query = "SELECT picture FROM ".$siteprefix."reports_images WHERE report_id = '$report_id' LIMIT 1";
                        $image_result = mysqli_query($con, $image_query);
                        $image_data = mysqli_fetch_assoc($image_result);
                        $product_image = $image_data['picture'] ?? 'default-image.jpg'; // Use a default image if none is found
                    ?>
                    <tr>
                        <td><strong><?php echo $i; ?></strong></td>
                        <td>
                            <img src="<?php echo $siteurl;?>uploads/<?php echo htmlspecialchars($product_image); ?>" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td><?php echo htmlspecialchars($product_name); ?></td>
                        <td><?php echo $sales_count; ?></td>
                        <td><?php echo $sitecurrency . number_format($total_revenue, 2); ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#transactionsModal_<?php echo $report_id; ?>">View Transactions</button>
                        </td>
                    </tr>
                    <?php
                        // Generate modal content separately
                        $modals .= '
                        <div class="modal fade" id="transactionsModal_'.$report_id.'" tabindex="-1" aria-labelledby="transactionsModalLabel_'.$report_id.'" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="transactionsModalLabel_'.$report_id.'">Transactions for '.htmlspecialchars($product_name).'</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Customer Name</th>
                                                    <th>Profile Picture</th>
                                                    <th>Price</th>
                                                    <th>Date</th>
                                                    <th>Loyalty ID</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                        // Fetch transactions for the product
                        $transactions_query = "
                            SELECT 
                                oi.order_id, 
                                CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
                                u.profile_picture,
                                oi.price, 
                                o.date AS created_at, 
                                oi.loyalty_id
                            FROM 
                                ".$siteprefix."order_items oi
                            JOIN 
                                ".$siteprefix."orders o ON oi.order_id = o.order_id
                            JOIN 
                                ".$siteprefix."users u ON o.user = u.s
                            WHERE 
                                oi.report_id = '$report_id' AND oi.affiliate_id = '$affliate' AND o.status = 'paid'
                        ";
                        $transactions_result = mysqli_query($con, $transactions_query);

                        if (!$transactions_result) {
                            die('Query Failed: ' . mysqli_error($con));
                        }

                        while ($transaction = mysqli_fetch_assoc($transactions_result)) {
                            $order_id = $transaction['order_id'];
                            $customer_name = $transaction['customer_name'];
                            $profile_picture = $transaction['profile_picture'] ?? 'default-profile.jpg';
                            $price = $transaction['price'];
                            $date = $transaction['created_at'];
                            $loyalty_id = $transaction['loyalty_id'];

                            $modals .= "
                            <tr>
                                <td>".htmlspecialchars($order_id)."</td>
                                <td>".htmlspecialchars($customer_name)."</td>
                                <td>
                                    <img src="{$siteurl}{$imagePath}{$profile_picture}" alt=\"Profile Picture\" style=\"width: 50px; height: 50px; object-fit: cover; border-radius: 50%;\">
                                </td>
                                <td>{$sitecurrency}".number_format($price, 2)."</td>
                                <td>".htmlspecialchars($date)."</td>
                                <td>".htmlspecialchars($loyalty_id)."</td>
                            </tr>";
                        
                        }

                        $modals .= '
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>';
                        $i++;
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View All Button -->
    <div class="text-center mt-3">
        <a href="sales.php" class="btn btn-primary">View All</a>
    </div>
</div>

<!-- Append Modals Outside the Table -->
<?php echo $modals; ?>


<?php include "footer.php"; ?>