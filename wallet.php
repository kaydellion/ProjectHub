<?php include "header.php"; ?>
<main id="main">


<style>
.card{
	border: none;
	border-radius: 10px;
	width:100%;
}
.fa-ellipsis-v{
	font-size: 10px;
	color: #C2C2C4;
	margin-top: 6px;
	cursor: pointer;
}
.text-dark{
	font-weight: bold;
	margin-top: 8px;
	font-size: 13px;
	letter-spacing: 0.5px;
}
.card-bottom{
	background: #3E454D;
	border-radius: 6px;
}
.flex-column{
	color: #adb5bd;
	font-size: 13px;
}
.flex-column p{
	letter-spacing: 1px;
	font-size: 18px;
}
.btn-secondary{
	height: 40px!important;
	margin-top: 3px;
}
.btn-secondary:focus{
	box-shadow: none;
}
.card{
background: none !important;
}
</style>



<!-- ======= Services Section ======= -->
<section id="services" class="services mt-5">
<div class="container">
<div class="row" style="margin-top:5%;">
<div class="col-12">


<div class="section-title">
<h4>My Wallet</h4>
</div>
                     
                     

  <div class="card">
    <p class="text-dark">Your Account Balance</p>
    <div class="card-bottom pt-3 px-4 mb-3">
      <div class="d-flex flex-row justify-content-between text-align-center">
        <div class="d-flex flex-column text-white"><span>Balance amount</span><p> &#8358;<span class="text-white"><?php echo $wallet; ?></span></p></div>
        <button class="btn btn-secondary" data-toggle="modal" data-target="#withDraw">Withdraw <i class="fa fa-download text-white"></i></button>
      </div>
      <div class="pt-1 pb-3">
      <a href="withdrawhistory.php" class="btn btn-secondary" >View Withdrawal History</a>
      </div>
    </div>
  </div>







  <h6 class="text-primary pt-3 ">Wallet Transaction History</h6>
  <div class="table-responsive pb-5">
  <table class="table table-bordered table-hover text-center" style='text-align:center;'>
  <thead>
  <tr>
      <th scope="col" >Amount</th>
      <th scope="col" >Reference</th>
      <th scope="col" >Type</th>
      <th scope="col">Date</th>
  </tr>
  </thead>
  <tbody>
   <?php
$sql = "SELECT * FROM ".$siteprefix."wallet_history WHERE user='$user_id' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
if(mysqli_num_rows($sql2) <= 0 ) {
print "<tr><td colspan='4' style='text-align:center;'>No wallet transactions available</td></tr>";
}
else{
while($row = mysqli_fetch_array($sql2)){
$cash= $row['amount'];
$action= $row['status'];
$note= $row['reason'];
$date= $row['date'];

$class="badge bg-success";
if($action=="debit"){
$class="badge bg-danger";
}
echo' <tr>
      <td  width="200px">&#8358;'.$cash.'</td>
      <td  width="200px">'.$note.'</td>
      <td width="200px"><span class="'.$class.'">'.$action.'</span></td>
      <td width="200px">'.$date.'</td>
    </tr>';
}}
?>
  
</tbody>
</table> 
          
</div> 
</div>     
        
        
    
           </div>
           </div>
          </div>
    </section><!-- End Services Section -->


  



</main><!-- End #main -->

<!-- Modal -->
<div class="modal fade" id="withDraw" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Withraw from wallet</h5>
      </div>
      <div class="modal-body">
        <p><b>Amount Available for withdrawal:  <span class="text-primary">&#8358;<?php echo $wallet; ?></span></b></p>
        <form method="post" action="" onsubmit="return confirm('Proceed with withdrawal?');"  >
        <?php  $disable=""; if($bank_name=="" || $bank_accname=="" || $bank_number==""){ $disable="disabled"; ?>
        <p class="text-danger"><a href="payment.php" class="btn btn-danger">Update Payment Details</a><br>
        <i>This is a required detail to be eligible to withdraw</i></p>
        <?php } else{ ?>
       <p>Your Bank Details</p>
       <p><input type="text" name="bank" class="form-control" value="<?php echo $bank_name; ?>"  placeholder="Enter bank name" required/><br>
       <input type="text" name="bankname" class="form-control" value="<?php echo $bank_accname; ?>" placeholder="Enter bank account name"  required/><br>
       <input type="number" name="bankno" class="form-control" value="<?php echo $bank_number; ?>" placeholder="Enter bank account number"  required/></p>
       <?php } ?>
       <p class="pt-3"><input type="number" name="amount" class="form-control"  max="<?php echo $userwallet; ?>" min="500" placeholder="Enter Amount to withdraw" required/>
       <span class="text-small text-primary">Minimum withdrawal is &#8358;500</span></p>
       
      </div>
      <div class="modal-footer">
        <button type="submit" value="withdraw" name="withdraw" class="btn btn-primary" <?php echo $disable; ?>>Withdraw</button></form>
      </div>
    </div>
  </div>
</div>
 <?php include "footer.php"; ?>