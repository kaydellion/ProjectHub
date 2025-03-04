<?php include "backend/connect.php"; 


//previous page
$_SESSION['previous_page'] = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$previousPage = $_SESSION['previous_page'] ?? 'index.php';

$code = "";
if (isset($_COOKIE['userID'])) {$code = $_COOKIE['userID'];}
$check = "SELECT * FROM ".$siteprefix."users WHERE s = '" . $code . "'";
$query = mysqli_query($con, $check);
if (mysqli_affected_rows($con) == 0) {
    $active_log = 0;
} else {
    $sql = "SELECT * FROM ".$siteprefix."users  WHERE s  = '".$code."'";
    $sql2 = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($sql2)) {
        $id = $row["s"];
        $display_name = $row['display_name'];
        $first_name = $row['first_name']; 
        $middle_name = $row['middle_name'];
        $last_name = $row['last_name'];
        $profile_picture = !empty($row['profile_picture']) ? $row['profile_picture'] : 'user.png';
        $mobile_number = $row['mobile_number'];
        $email = $row['email'];
        $password = $row['password'];
        $gender = $row['gender'];
        $address = $row['address'];
        $user_type = $row['type'];
        $seller = $row['seller'];
        $status = $row['status'];
        $last_login = $row['last_login'];
        $created_date = $row['created_date'];
        $preference = $row['preference'];
        $bank_name = $row['bank_name'];
        $bank_accname = $row['bank_accname'];
        $bank_number = $row['bank_number'];
        $loyalty = $row['loyalty'];
        $wallet = $row['wallet'];
        $affliate = $row['affliate'];
        $facebook = $row['facebook'];
        $twitter = $row['twitter'];
        $instagram = $row['instagram'];
        $linkedln = $row['linkedln'];
        $kin_name = $row['kin_name'];
        $kin_number = $row['kin_number'];
        $kin_email = $row['kin_email'];
        $biography = $row['biography'];
        $kin_relationship = $row['kin_relationship'];
           

        $active_log = 1;
        $user_id=$id;
        $username=$display_name;
        $user_reg_date=formatDateTime($created_date);
        $user_lastseen=formatDateTime($last_login);


}}


//if($active_log==0){header("location: signup.php");}
//$adminlink=$siteurl.'/admin';
include "backend/actions.php"; 
include "backend/start_order.php";
?>

<!doctype html>
<html lang="zxx">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $sitename; ?></title>
    <link rel="icon" href="img/<?php echo $siteimg; ?>">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- animate CSS -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="css/all.css">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="css/slick.css">
    <!-- style CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!--::header part start::-->
    <header class="main_menu home_menu">
        <div class="top-bar bg-kayd">
            <div class="container p-1">
            <div class="row justify-content-between">
                <div class="col-md-6 col-8 d-flex align-items-center">
                <ul class="top-bar-info d-flex flex-column flex-md-row text-white">
                    <li class="mr-3"><i class="fa fa-phone"></i> <?php echo $sitenumber;?></li>
                    <li><i class="fa fa-envelope"></i><?php echo $sitemail; ?></li>
                </ul>
                </div>
                <div class="col-md-6 col-4 d-flex justify-content-end align-items-center">
                <ul class="top-bar-links d-flex">
                    <?php if($active_log==0){ ?>
                    <li class="bg-black p-1 p-lg-3"><a class="text-white" href="become_a_seller.php">Become a Seller</a></li>
                    <?php } else {?>
                    <li class="bg-black p-1 p-lg-3"><a class="text-white" href="logout.php">Logout</a></li>
                    <?php } ?>
                </ul>
                </div>
            </div>
            </div>
        </div>
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="index.php"> <img class="logo" src="img/<?php echo $siteimg; ?>" alt="logo"> </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="menu_icon"><i class="fas fa-bars"></i></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="blog.php" id="navbarDropdown_1"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     Marketplace
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown_1">
                                        <div class="row">
                                            <?php
                                                $sql = "SELECT * FROM " . $siteprefix . "categories WHERE parent_id IS NULL";
                                                $sql2 = mysqli_query($con, $sql);
                                                $count = 0;
                                                while ($row = mysqli_fetch_array($sql2)) {
                                                    if ($count % 2 == 0 && $count != 0) {
                                                        echo '</div><div class="row">';
                                                    }
                                                    echo '<div class="col-12"><a class="dropdown-item" href="category.php?id=' . $row['id'] . '">' . $row['category_name'] . '</a></div>';
                                                    $count++;
                                                }
                                            ?>
                                            <div class="col-12"><a class="dropdown-item" href="marketplace.php">View Marketplace</a></div>
                                        </div>
                                    </div>
                                </li>
                                <?php if($active_log==1){ ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="blog.php" id="navbarDropdown_1"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Client Portal
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown_1">
                                        <a class="dropdown-item" href="product_list.php">My Purchases</a>
                                        <a class="dropdown-item" href="#">Order History</a>
                                        <a class="dropdown-item" href="#">Notifications</a>
                                    </div>
                                </li>
                               <?php if($seller==1){ ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="blog.php" id="navbarDropdown_1"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Seller Portal
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown_1">
                                        <a class="dropdown-item" href="">Manage Models</a>
                                        <a class="dropdown-item" href="">Sales Analytics</a>
                                        <a class="dropdown-item" href="#">Revenue Management</a>
                                        <a class="dropdown-item" href="#">Customer Feedback</a>
                                        <a class="dropdown-item" href="#">Marketing Tools</a>
                                    </div>
                                </li>
                                <?php }} ?>



                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="blog.php" id="navbarDropdown_2"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       Support
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown_2">
                                         <a class="dropdown-item" href="#">Support Tickets</a>
                                        <a class="dropdown-item" href="blog.php">Contact Us</a>
                                        <a class="dropdown-item" href="single-blog.php">FAQ</a>
                                        <a class="dropdown-item" href="single-blog.php">Loyalty System</a>
                                    </div>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="contact.php">Contact</a>
                                </li>
                            </ul>
                        </div>
                        <div class="hearer_icon d-flex align-items-center">
                            <a id="search_1" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <a href="cart.php">
                                <i class="flaticon-shopping-cart-black-shape"></i>
                            </a>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user"></i>
                                </a>
                                <?php if($active_log==1){ ?>
                                <div class="dropdown-menu" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="dashboard.php">My Account</a>
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                </div>
                                <?php }else{ ?>
                                <div class="dropdown-menu" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="signin.php">Sign In</a>
                                    <a class="dropdown-item" href="signup.php">Register</a>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="search_input" id="search_input_box">
            <div class="container">
                <form class="d-flex justify-content-between search-inner" action="search.php" method="get">
                    <input type="text" name="searchterm" class="form-control" id="search_input" placeholder="Search by title,category,keywords or tags or seller">
                    <button type="submit" class="btn"></button>
                    <span class="ti-close" id="close_search" title="Close Search"></span>
                </form>
            </div>
        </div>
    </header>
    <!-- Header part end-->
