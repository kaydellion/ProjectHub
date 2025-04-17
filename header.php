<?php include "backend/connect.php"; 


//previous page
$_SESSION['previous_page'] = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$previousPage=$_SESSION['previous_page'];
$current_page = urlencode(basename($_SERVER['PHP_SELF']) . '?' . $_SERVER['QUERY_STRING']);

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
           
        $_SESSION['user_role'] = $user_type;

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
                <div class="col-md-6 col-8 d-flex align-items-center">
                <ul class="top-bar-info d-flex flex-column flex-md-row text-white">
                    <li class="m-1"><i class="fa fa-phone"></i> <?php echo $sitenumber;?></li>
                    <li class="m-1"><i class="fa fa-envelope"></i><?php echo $sitemail; ?></li>
                </ul>
                </div>
                <div class="col-md-6 col-4 d-flex justify-content-end align-items-center">
                <ul class="top-bar-links d-flex">
                    <?php if($active_log==0){ ?>
                    <li class="bg-black p-1"><a class="text-white" href="become_a_seller.php">Become a Seller</a></li>
                    <?php } else {?>
                    <li class="bg-black p-1"><a class="text-white" href="logout.php">Logout</a></li>
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
                        <a class="navbar-brand" href="index.php"> <img class="logo" src="img/<?php echo $siteimg; ?>" alt="logo"> </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="menu_icon"><i class="fas fa-bars"></i></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="about-us.php">About</a></li>
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
                                        <a class="dropdown-item" href="saved-reports.php">Saved Reports</a>
                                        <a class="dropdown-item" href="my_orders.php">My Purchases</a>
                                        <a class="dropdown-item" href="manual_orders.php">Manual Purchases</a>
                                        <a class="dropdown-item" href="wallet.php">My Wallet</a>
                                    </div>
                                </li>
                               <?php if($seller==1){ ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="blog.php" id="navbarDropdown_1"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Seller Portal
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown_1">
                                        <a class="dropdown-item" href="models.php">Manage Models</a>
                                        <a class="dropdown-item" href="sales.php">Sales Analytics</a>
                                        <a class="dropdown-item" href="wallet.php">Revenue Management</a>
                                        <a class="dropdown-item" href="reviews.php">Customer Feedback</a>
                                    </div>
                                </li>
                                <?php }} ?>



                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="blog.php" id="navbarDropdown_2"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       Support
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown_2">
                                         <a class="dropdown-item" href="tickets.php">Support Tickets</a>
                                        <a class="dropdown-item" href="contact.php">Contact Us</a>
                                        <a class="dropdown-item" href="faq.php">FAQ</a>
                                        <a class="dropdown-item" href="loyalty-program.php">Loyalty System</a>
                                    </div>
                                </li>
                                
                            </ul>
                        </div>
                        <div class="hearer_icon d-flex align-items-center">
                            <a id="search_1" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <a href="cart.php">
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
                                    <a class="dropdown-item" href="dashboard.php">My Account</a>
                                    <a class="dropdown-item" href="notifications.php">Notifications</a>
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

    <input type="hidden" id="order_id" value="<?php echo $order_id; ?>">
    <input type="hidden" id="user_id" value="<?php if($active_log==1){echo $user_id; }?>">