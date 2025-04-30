<?php include "header.php";
$show="none"; $textshow="none";
    if(isset($_GET['user_login'])){
    $user_log=$_GET['user_login'];
    $sql = "SELECT * from ".$siteprefix."users where s='$user_log'";
    $sql2 = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($sql2))
    {$username = $row['email']; $pass = $row['password']; $status = $row['status']; }
    if($status=="inactive"){$textshow="block";} $show="block"; 
    } ifLoggedin($active_log);
   
   ?>



    <!--================login_part Area =================-->
    <section class="login_part mt-5">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6 col-md-6 order-2 order-md-1">
                    <div class="login_part_text text-center">
                        <div class="login_part_text_iner">
                            <h2>New to Project Report Hub?
                            </h2>
                            <p>Join ProjectReportHub.ng today and unlock instant access to quality project reports,academic resources, and student-friendly tools. Stay ahead with the latest researchtrends and innovations across all fields of study!</p>
                           <p> <a href="signup.php" class="btn_3">Create an Account</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="login_part_form">
                        <div class="login_part_form_iner">
                        <div class="alert alert-success alert-dismissible mb-3 fade show" id="myAlert" role="alert" style="display:<?php echo $show; ?>">
                        Congratulations! Your account has been successfully created. Thank you for registering! <span style="display: <?php echo $textshow;?>"> your email to verify your account.</span></div>
                            <h3>Welcome Back ! <br>
                                Please Sign in now</h3>
                            <form class="row contact_form" action="#" method="post" novalidate="novalidate">
                                <div class="col-md-12 form-group p_star">
                                    <input type="text" class="form-control" id="name" name="name" value=""
                                        placeholder="Email or Username">
                                </div>
                                <div class="col-md-12 form-group p_star">
                                    <input type="password" class="form-control" id="password" name="password" value=""
                                        placeholder="Password">
                                </div>
                                <div class="col-md-12 form-group">
                                    <div class="creat_account d-flex align-items-center">
                                        <input type="checkbox" id="f-option" name="selector">
                                        <label for="f-option">Remember me</label>
                                    </div>
                                    <button type="submit" value="submit" name="signin" class="btn_3">
                                        log in
                                    </button>
                                    <a class="lost_pass" href="forgot-password.php">forget password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================login_part end =================-->











<?php include "footer.php"; ?>