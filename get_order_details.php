<?php
include "backend/connect.php";

if(isset($_POST['order_id'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);

    if(empty($order_id)) {
        echo '<div class="seller-materials mb-3"><select name="seller" class="form-control"><option value="">None</option></select></div>';
        exit();
    }
    
    // Get materials grouped by seller
    $sql = "SELECT m.*, o.*, s.display_name as shop_name, s.s as seller_id, r.title as material_name
            FROM ".$siteprefix."order_items m 
            JOIN ".$siteprefix."orders o ON m.order_id = o.order_id 
            JOIN ".$siteprefix."reports r ON r.id = m.report_id 
            JOIN ".$siteprefix."users s ON r.user = s.s
            WHERE m.order_id = ? 
            GROUP BY s.s";
            
    // Prepare statement
    if($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $order_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if($result) {
            $current_seller = '';
            $output = '<div class="order-details mb-3">';
            
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    if($current_seller != $row['shop_name']) {
                        if($current_seller != '') {
                            $output .= '</select></div>';
                        }
                        $current_seller = $row['shop_name'];
                        $output .= '<div class="seller-materials">';
                        $output .= '<select name="seller" class="form-control"">';
                        $output .= '<option value="">None</option>';
                    }
                    
                    $output .= '<option value="'.htmlspecialchars($row['seller_id']).'">'.
                              htmlspecialchars($row['material_name']).' ('.htmlspecialchars($row['shop_name']).')'.
                              '</option>';
                }
                
                $output .= '</select></div></div>';
                echo $output;
            } else {
                $output .= '<div class="seller-materials mb-3">';
                $output .= '<select name="seller" class="form-control">';
                $output .= '<option value="">None</option>';
                $output .= '</select></div></div>';
                echo $output;
            }
        } else {
            echo "Error executing query: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }
}
?>
