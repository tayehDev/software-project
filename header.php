<!DOCTYPE html>
<?php
include "config.php";

if($_GET['action']=="logout")
{
  session_destroy();
  sleep(1);
  header("Location: index.php");
  exit(); 
}
?>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Platform Stores</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="assets/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="assets/css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="assets/css/font-style.css">
        
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        
    </head>

    <body>

        
        <?php include "firebase_listener.php";?>


        <!-- Navbar start -->
        <div class="container-fluid fixed-top">
            <div class="container topbar bg-primary d-none d-lg-block" dir="ltr" >
                <div class="d-flex justify-content-between">

                    <div class="top-link " style="color:white;" >
                        Marketplace
                    </div>
                </div>
            </div>
            <div class="container px-0">

                    <a href="index.php" class="navbar-brand" style="padding:0 !important;margin:0 !important "><h1 class="text-primary display-6" style="padding:0 !important;margin:0 !important ">Platform Stores</h1></a>

                <nav class="navbar navbar-light bg-white navbar-expand-xl m-0 p-0" style="padding:0 !important;margin:0 !important " dir="ltr" >
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                        <div class="navbar-nav mx-auto">
                            <a href="index.php" class="nav-item nav-link active">Home</a>
                            <?php
                            if(empty($_SESSION['user_role_account']))
                            {
                            ?>
                            <a href="about_us.php" class="nav-item nav-link">About</a>
                            <a href="signup.php" class="nav-item nav-link">Signup</a>
                            <a href="signin.php" class="nav-item nav-link">Signin</a>
                            <?php
                            }
                            ?>
                            
                            <?php
                            if($_SESSION['user_role_account']==1 || $_SESSION['user_role_account']==2)
                            {
                            ?>

                              <a href="received_orders.php" class="nav-item nav-link">Received Orders</a>
                              <a href="products.php" class="nav-item nav-link">Products</a>
                              <a href="chatAITrain.php" class="nav-item nav-link">
                                  Chat AI
                              </a>
                              
                            <?php
                            }
                            ?>
                            
                            <?php
                            if($_SESSION['user_role_account']==1 || $_SESSION['user_role_account']==4)
                            {
                            ?>
                              <a href="courier_orders.php" class="nav-item nav-link">Courier Orders</a>
                            <?php
                            }
                            ?>
                            
                            <?php
                            if( $_SESSION['user_role_account']==3)
                            {
                            ?>
                             <a href="sent_orders.php" class="nav-item nav-link">Sent Orders</a>
                            <?php
                            }
                            ?>

                            <?php
                            if($_SESSION['user_role_account']==1 || $_SESSION['user_role_account']==2|| $_SESSION['user_role_account']==3|| $_SESSION['user_role_account']==4)
                            {
                            
                              
                            ?>
                            
                            
                            
                            <a href="my_account.php" style="width:120px;" class="nav-item nav-link">
                                My account
                            </a>
                            <?php
                            }
                            ?>
                            
                            <?php
                            if($_SESSION['user_role_account']==1)
                            {
                            ?>

                            <a href="area.php" class="nav-item nav-link">
                                Areas
                            </a>
                            <a href="category.php" class="nav-item nav-link">
                                Category
                            </a>
                            <a href="head_img.php" class="nav-item nav-link">
                                Header
                            </a>
                            <a href="aboutUs.php" class="nav-item nav-link">
                                About
                            </a>
                            
                            <a href="users.php" class="nav-item nav-link">
                                Users
                            </a>

                            <?php
                            }
                            ?>
<?php
                            if($_SESSION['user_role_account']==1 || $_SESSION['user_role_account']==2|| $_SESSION['user_role_account']==3|| $_SESSION['user_role_account']==4)
                            {
                            ?>
                            <a id="notification_bell" href="received_orders.php" style="display:none;" onclick="manageTag('notifications/<?php echo $_SESSION['user_id'];?>', 'set',0);" class="position-relative me-4 my-auto" title="طلبات جديدة واردة" >
                                <i class="fa fa-bell fa-2x"></i>
                                <span id="notification" class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; right: 15px; height: 20px; min-width: 20px;"></span>
                            </a>
                            <a href="?action=logout" class="nav-item nav-link" style="color:#e30000;">Logout</a>
                            <?php
                            }
                            ?>

                        </div>
                            
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->
        <br>

