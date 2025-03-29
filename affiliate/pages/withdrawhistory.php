<?php include "header.php"; ?>



<div class="container">
<div class="row" style="margin-top:10%;">
<div class="col-12">


<div class="section-title">
<h4 class="text-primary">Withdrawal History</h4>
</div>
<div class="table-responsive pt-1 pb-5"> 
        <table class="table text-dark" id="dataTable" style='text-align:center;'>
        <thead class="kayd-bg">
            <tr>
                <th>Amount</th>
                <th>Date</th>
                <th>Paid To</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
 <?php
$sql = "SELECT * FROM " . $siteprefix . "withdrawal WHERE user = '$user_id' ORDER BY s DESC";
$sql2 = mysqli_query($con, $sql);
if (mysqli_num_rows($sql2) > 0) {
    while ($insertedRecord = mysqli_fetch_array($sql2)) {
        $amount = $insertedRecord['amount'];
        $status = $insertedRecord['status'];
        $bank = $insertedRecord['bank'];
        $bankname = $insertedRecord['bank_name'];
        $bankno = $insertedRecord['bank_number'];
        $date = formatDateTime($insertedRecord['date']);
        
        $badge = "bg-success";
        
        if ($status == "pending") {
        $badge = "bg-warning";
        }else if($status=="failed"){
         $badge = "bg-danger";   
        }

        echo "<tr>
                <td>₦$amount</td>
                <td>$date</td>
                <td>$bank | $bankno | $bankname</td>
                <td><span class='badge $badge text-white'>$status</span></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4' style='text-align:center;'>No withdrawal transactions found.</td></tr>";
}
?>
        
        </tbody>
    </table>
</div> </div></div></div>
<?php include "footer.php"; ?>