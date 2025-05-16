<?php include "backend/connect.php"; 


//previous page
$_SESSION['previous_page'] = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$previousPage=$_SESSION['previous_page'];
$current_page = urlencode(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME) . '?' . $_SERVER['QUERY_STRING']);;

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
        $loyalty_id = $row['loyalty'];
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
           
        $_SESSION['user_role'] = $user_type;
        $_SESSION['user_id'] = $id;
        $_SESSION['user_seller']=$seller;

        $active_log = 1;
        $user_id=$id;
        $username=$display_name;
        $user_reg_date=formatDateTime($created_date);
        $user_lastseen=formatDateTime($last_login);


}}


//if($active_log==0){header("location: signup.php");}
//$adminlink=$siteurl.'/admin';
include "backend/start_order.php";
include "backend/actions.php"; 

//exclude pages tht require user to be logged in
$current_page = basename($_SERVER['PHP_SELF']);
$excluded_pages = array('cart', 'pay_success', 'pay_failed', 'checkout', 'free_order_handler',
'dashboard','loyalty-status','saved-reports','my_orders','manual_orders', 'wallet',
'notifications','tickets','models','sales','reviews','my_orders','order_details','settings','tickets',
'resources-sold','resource','edit-report','change-password','create_ticket','add-report','delete','saved-models',
'withdrawhistory');
if (in_array($current_page, $excluded_pages)) {
    checkActiveLog($active_log); 
} else {
    //ifLoggedin($active_log);
}
?>

<!doctype html>
<html lang="zxx">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : $sitename ?></title>
    <meta name="description" content="<?= isset($sitedescription) ? htmlspecialchars($sitedescription) : $sitename ?>">
    <meta name="keywords" content="<?= isset($sitekeywords) ? htmlspecialchars($sitekeywords) : $sitename ?>">
    <meta name="author" content="<?= isset($sitename) ? htmlspecialchars($sitename) : $sitename ?>">
    <meta property="og:title" content="<?= isset($page_title) ? htmlspecialchars($page_title) : $sitename ?>" />
    <meta property="og:description" content="<?= isset($sitedescription) ? htmlspecialchars($sitedescription) : $sitename ?>" />
    <meta property="og:image" content="<?= isset($siteurl) ? htmlspecialchars($siteurl) : $sitename ?>img/<?= isset($siteimg) ? htmlspecialchars($siteimg) : $sitename ?>" />
    <meta property="og:url" content="<?= isset($siteurl) ? htmlspecialchars($siteurl) : $sitename ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="<?= isset($sitename) ? htmlspecialchars($sitename) : $sitename ?>" />
    <meta property="og:locale" content="en_US" />
    <link rel="icon" href="<?php echo $siteurl; ?>img/<?php echo $siteimg; ?>">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo $siteurl; ?>css/bootstrap.min.css">
    <!-- animate CSS -->
    <link rel="stylesheet" href="<?php echo $siteurl; ?>css/animate.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="<?php echo $siteurl; ?>css/owl.carousel.min.css">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="<?php echo $siteurl; ?>css/all.css">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="<?php echo $siteurl; ?>css/flaticon.css">
    <link rel="stylesheet" href="<?php echo $siteurl; ?>css/themify-icons.css">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="<?php echo $siteurl; ?>css/magnific-popup.css">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="<?php echo $siteurl; ?>css/slick.css">
    <!-- style CSS -->
    <link rel="stylesheet" href="<?php echo $siteurl; ?>css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php include 'backend/tinymce.php'; ?>
</head>

<body>
    <!--::header part start::-->
    <div class="top-bar bg-kayd">
            <div class="container p-1">
            <div class="row justify-content-between">
                <div class="col-md-6 col-8 d-flex flex-wrap align-items-center">
                <ul class="top-bar-info d-flex flex-wrap flex-md-row text-white">
                    <li class="m-1 text-white"><i class="fa fa-phone"></i><?php echo $sitenumber;?></li>
                    <li class="m-1 text-white"><i class="fa fa-envelope"></i><?php echo $sitemail; ?></li>
                </ul>
                </div>
                <div class="col-md-6 col-12 d-flex flex-wrap justify-content-lg-end align-items-center">
                <ul class="top-bar-links d-flex flex-wrap">
                <li class="bg-secondary text-white p-2 me-2"><a class="text-white text-small" href="<?php echo $siteurl; ?>loyalty-program.php">Loyalty Program</a></li>
                <li class="bg-secondary text-white p-2 me-2"><a class="text-white text-small" href="<?php echo $siteurl; ?>become_an_affliate.php">Affliate Program</a></li>
                <li class="bg-secondary text-white p-2 me-2"><a class="text-white text-small" href="<?php echo $siteurl; ?>marketplace.php">Marketplace</a></li>
                    <?php if($active_log==0){ ?>
                    <li class="bg-dark-orange p-2"><a class="" href="<?php echo $siteurl; ?>become_a_seller.php">Become a Seller</a></li>
                    <?php } else {?>
                    <li class="bg-dark-orange p-2"><a class="" href="<?php echo $siteurl; ?>logout.php">Logout</a></li>
                    <?php } ?>
                </ul>
                </div>
            </div>
            </div>
        </div>
    <header class="main_menu home_menu">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="<?php echo $siteurl; ?>index.php"> <img class="logo" src="<?php echo $siteurl; ?>img/<?php echo $siteimg; ?>" alt="logo"> </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="menu_icon"><i class="fas fa-bars"></i></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="<?php echo $siteurl; ?>index.php">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo $siteurl; ?>about-us.php">About</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_1"
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
                                                $category_name = $row['category_name'];
                                                $alt_names = $row['slug'];
                                                $slugs = $alt_names;
                                                    if ($count % 2 == 0 && $count != 0) {
                                                        echo '</div><div class="row">';
                                                    }
                                                   echo '<div class="col-12"><a class="dropdown-item" href="'.$siteurl.'category/' . $slugs . '">' . $row['category_name'] . '</a></div>';
                                                    $count++;
                                                }
                                            ?>
                                            
                                            
                                
                                            <div class="col-12"><a class="dropdown-item" href="<?php echo $siteurl; ?>marketplace.php">View Marketplace</a></div>
                                        </div>
                                    </div>
                                </li>
                                <?php if($active_log==1){ ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="<?php echo $siteurl; ?>blog.php" id="navbarDropdown_1"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Client Portal
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown_1">
                                    <a class="dropdown-item" href="<?php echo $siteurl; ?>loyalty-status.php">Subscriptions</a>
                                        <a class="dropdown-item" href="<?php echo $siteurl; ?>saved-reports.php">Saved Reports</a>
                                        <a class="dropdown-item" href="<?php echo $siteurl; ?>my_orders.php">My Purchases</a>
                                        <a class="dropdown-item" href="<?php echo $siteurl; ?>manual_orders.php">Manual Purchases</a>
                                        <a class="dropdown-item" href="<?php echo $siteurl; ?>wallet.php">My Wallet</a>
                                    </div>
                                </li>
                               <?php if($seller==1){ ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="<?php echo $siteurl; ?>blog.php" id="navbarDropdown_1"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Seller Portal
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown_1">
                                        <a class="dropdown-item" href="<?php echo $siteurl; ?>models.php">Manage Resources</a>
                                        <a class="dropdown-item" href="<?php echo $siteurl; ?>sales.php">Sales Analytics</a>
                                        <a class="dropdown-item" href="<?php echo $siteurl; ?>wallet.php">Revenue Management</a>
                                        <a class="dropdown-item" href="<?php echo $siteurl; ?>reviews.php">Customer Feedback</a>
                                    </div>
                                </li>
                                <?php }} ?>



                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="<?php echo $siteurl; ?>blog.php" id="navbarDropdown_2"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       Support
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown_2">
                                         <a class="dropdown-item" href="<?php echo $siteurl; ?>tickets.php">Support Tickets</a>
                                        <a class="dropdown-item" href="<?php echo $siteurl; ?>contact.php">Contact Us</a>
                                        <a class="dropdown-item" href="<?php echo $siteurl; ?>faq.php">FAQ</a>
                                     <!---   <a class="dropdown-item" href="loyalty-program.php">Loyalty System</a> ---->
                                    </div>
                                </li>
                                
                            </ul>
                        </div>
                        <div class="hearer_icon d-flex align-items-center">
                            <a id="search_1" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <a href="<?php echo $siteurl; ?>cart.php">
                                <?php
                                $cart_count = getCartCount($con, $siteprefix, $order_id);
                                ?>
                                <div class="position-relative d-inline-block">
                                    <i class="flaticon-shopping-cart-black-shape"></i>
                                    <?php if($cart_count >= 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <span class="cart-count"><?php echo $cart_count; ?></span>
                                        <span class="visually-hidden">items in cart</span>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user"></i>
                                </a>
                                <?php if($active_log==1){ ?>
                                <div class="dropdown-menu" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="<?php echo $siteurl; ?>dashboard.php">My Account</a>
                                    <a class="dropdown-item" href="<?php echo $siteurl; ?>notifications.php">Notifications</a>
                                    <a class="dropdown-item" href="<?php echo $siteurl; ?>logout.php">Logout</a>
                                </div>
                                <?php }else{ ?>
                                <div class="dropdown-menu" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="<?php echo $siteurl; ?>signin.php">Sign In</a>
                                    <a class="dropdown-item" href="<?php echo $siteurl; ?>signup.php">Register</a>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="search_input mb-3" id="search_input_box">
            <div class="container">
                <form class="d-flex justify-content-between search-inner" action="<?php echo $siteurl; ?>search.php" method="get">
                    <input type="text" name="searchterm" class="form-control" id="search_input" placeholder="Search by title,category,keywords or tags or seller">
                    <button type="submit" class="btn"></button>
                    <span class="ti-close" id="close_search" title="Close Search"></span>
                </form>
            </div>
        </div>
    </header>
    <!-- Header part end-->

    <input type="hidden" id="order_id" value="<?php echo $order_id; ?>">
    <input type="hidden" id="user_id" value="<?php if($active_log==1){echo $user_id; }?>">