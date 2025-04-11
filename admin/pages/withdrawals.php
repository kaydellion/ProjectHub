<?php include "header.php"; ?>



<div class="container-xxl flex-grow-1 container-p-y">
              <div class="card">
                <div class="table-responsive pt-3">

                <h6 class="p-3">Withdrawal Payments</h6>
                  <table class="table table-striped project-orders-table">
                    <thead>
                      <tr>
                        <th class="ml-5">Amount</th>
                        <th>User</th>
                        <th>Account Details</th>
                        <th>Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
<?php

$sql = "SELECT w.*,u1.display_name,u1.email,u1.mobile_number FROM " . $siteprefix . "withdrawal w  LEFT JOIN 
            ".$siteprefix."users u1 ON w.user = u1.s WHERE w.status != 'pending' ORDER BY w.s DESC";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
while ($insertedRecord = mysqli_fetch_array($result)) {
    $amount = formatNumber($insertedRecord['amount']);
        $status = $insertedRecord['status'];
        $bank = $insertedRecord['bank'];
        $therow = $insertedRecord['s'];
        $bankname = $insertedRecord['bank_name'];
        $bankno = $insertedRecord['bank_number'];
        $tenant_mail = $insertedRecord['email'];
        $tenant_name = $insertedRecord['display_name'];
        $tenant_phone= $insertedRecord['mobile_number'];
        $tenant= $insertedRecord['user'];
        $date = formatDateTime($insertedRecord['date']);
        
        $badge = "bg-success";
        
        if ($status == "pending") {
        $badge = "bg-warning";
        }else if($status=="failed"){
         $badge = "bg-danger";   
        }
  

    echo "<tr>
            <td>&#8358;$amount</td>
            <td>$tenant_name<br>$tenant_phone<br>$tenant_mail</td>
             <td>$bank | $bankno | $bankname</td>
            <td>$date</td>
            <td><span class='badge $badge text-white'>$status</span></td>";

         

}}
else{
    echo'<tr><td colspan="7" style="text-align:center;"> No withdrawals found </td></tr>';
}
?>
                    </tbody>
                  </table>


                </div>
              </div>
            </div>
          
  
        






<?php include "footer.php"; ?>