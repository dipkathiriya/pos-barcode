<?php 
require_once "connectdb.php";
if (!$_SESSION['role'] == $amploye and $admin and $warehouse or $_SESSION['username'] == "") {
  header('location:logout.php');
}
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="icon" href="assets/img/favicon.jpg" />
  <title>POS | BARCODE SYSTEMS</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">


 <!-- iCheck for checkboxes and radio inputs -->
 <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
  <!-- DataTables -->
  <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/jquery/pnotify.custom.css">
  

 <!-- SweetAlert2 -->
 <link rel="stylesheet" href="assets/plugins/sweetalert2/sweetalert2.min.css">

</head>
<body class="hold-transition sidebar-mini">
<script src="js/comman.js"></script>

<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/plugins/jquery/pnotify.custom.js"></script>
<script src="assets/plugins/jquery-validation/jquery.validate.min.js"></script>

  <div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <!-- <a href="index3.html" class="nav-link">Home</a> -->
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <!-- <a href="#" class="nav-link">Contact</a> -->
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li>
        <div class="container mt-2">
          <a href="user_profile.php">
            <i class="fas fa-user mx-2"></i>
            <span><?php echo $_SESSION['username']?></span>
          </a>
        </div>
      </li>
      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div class="container mt-3 mb-3">
      <img src="assets/img/i2.1.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .9 ;width:50px;background-color:aliceblue;">
      <span class="brand-text font-weight-light mx-3" style="color: white;font-size:20px;">POS BARCODE</span>
   </div> 

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <?php 
          if($_SESSION['role'] == $admin){
        ?>
           
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                
              </p>
            </a>
          </li>
        <?php 
          }
          if($_SESSION['role'] == $amploye){
        ?>
           
          <li class="nav-item">
            <a href="user.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                
              </p>
            </a>
          </li>
        <?php
          }
          if($_SESSION['role'] == $warehouse){
        ?>
           
          <li class="nav-item">
            <a href="warehouse.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                
              </p>
            </a>
          </li>
        <?php
          }
          if($_SESSION['role'] == $admin){
        ?>  
          <li class="nav-item">
            <a href="category.php" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>
                Category
              
              </p>
            </a>
          </li>
        <?php 
          }
          if($_SESSION['role'] == $admin){
        ?>

          <li class="nav-item">
            <a href="addproduct.php" class="nav-link">
            <i class="nav-icon fas fa-edit"></i>
              <p>
               Product
              
              </p>
            </a>
          </li>
        <?php 
          }
          if($_SESSION['role'] == $admin){
        ?>
          <li class="nav-item">
            <a href="productlist.php" class="nav-link">
            <i class="nav-icon fas fa-th-list"></i>
              <p>
               Product List
              
              </p>
            </a>
          </li>
          <?php 
         }
         if($_SESSION['role'] == $warehouse ){
          ?>
          <li class="nav-item">
            <a href="vender_master.php" class="nav-link">
            <i class="nav-icon fas fa-calculator"></i>
              <p>
               Vender Master
              </p>
            </a>
          </li>
          <?php 
         }
         if($_SESSION['role'] == $warehouse ){
          ?>
          <li class="nav-item">
            <a href="vender_list.php" class="nav-link">
            <i class="nav-icon fas fa-list"></i>
              <p>
               Vender List
              </p>
            </a>
          </li>
          <?php 
          }
          if($_SESSION['role'] == $warehouse){
          ?>
          <li class="nav-item">
            <a href="openingstock.php" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Warehouse Opening Stock
              </p>
            </a>
          </li>
          <?php 
          }
          if($_SESSION['role'] == $warehouse){
          ?>
          <li class="nav-item">
            <a href="warehouse_openingstock_report.php" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Warehouse OpeningStock Report
              </p>
            </a>
          </li>
          <?php 
          }
          if($_SESSION['role'] == $warehouse){
          ?>
          <li class="nav-item">
            <a href="stock_approval.php" class="nav-link">
            <i class="nav-icon fas fa-check"></i>
              <p>
                Warehouse Stock Approval
              </p>
            </a>
          </li>
          <?php 
          }
          if($_SESSION['role'] == $admin){
          ?>
          <li class="nav-item">
            <a href="store_openingstock.php" class="nav-link">
            <i class="nav-icon fas fa-list-alt"></i>
              <p>
              Store Opening Stock
              </p>
            </a>
          </li>
        <?php 
          }
          if($_SESSION['role'] == $warehouse){
        ?>
          <li class="nav-item">
            <a href="purchase_master.php" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
               Purchase Master
              </p>
            </a>
          </li>
          <?php
          }
          if($_SESSION['role'] == $warehouse){
          ?>
          <li class="nav-item">
            <a href="purchasemaster_list.php" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
               Purchase Master List
              </p>
            </a>
          </li>
        <?php 
          }
          if($_SESSION['role'] == $admin OR $_SESSION['role'] == $amploye){
        ?>
          <li class="nav-item">
            <a href="pos.php" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
              <p>
               POS
              
              </p>
            </a>
          </li>
          
        <?php
          }
          if($_SESSION['role'] == $admin OR $_SESSION['role'] == $amploye){
        ?>
          <li class="nav-item">
            <a href="orderlist.php" class="nav-link">
            <i class="nav-icon fas fa-list-ol"></i>
              <p>
             OrderList
              
              </p>
            </a>
          </li>
        <?php 
          }
          if($_SESSION['role'] == $admin){
        ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                 Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="tablereport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Table Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="graphreport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Graph Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="stock_report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="opning_stock_report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Opning Stock Report</p>
                </a>
              </li>
            </ul>
          </li>
        <?php 
         }
         if($_SESSION['role'] == $admin){
        ?>
          <li class="nav-item">
            <a href="product_stock_request.php" class="nav-link">
            <i class="nav-icon fas fa-calculator"></i>
              <p>
               Product Stock Request
              </p>
            </a>
          </li>
         

          <?php

         }
         if($_SESSION['role'] == $admin){
          ?>
          <li class="nav-item">
            <a href="productstock_list.php" class="nav-link">
            <i class="nav-icon fas fa-list"></i>
              <p>
               Product Stock List
              </p>
            </a>
          </li>
        <?php
        }
        if($_SESSION['role'] == $admin){
        ?>
          <li class="nav-item">
            <a href="registration.php" class="nav-link">
            <i class="nav-icon far fa-plus-square"></i>
              <p>
               Registration
              
              </p>
            </a>
          </li>
        <?php
        }
          if($_SESSION['role'] == $admin){
        ?>
          <li class="nav-item">
            <a href="changepassword.php" class="nav-link">
            <i class="nav-icon fas fa-user-lock"></i>
              <p>
               Change Password
              
              </p>
            </a>
          </li>
        <?php
        }
        if($_SESSION['role'] == $admin OR $_SESSION['role'] == $amploye OR $_SESSION['role'] == $warehouse){
        ?>
          <li class="nav-item">
            <a href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
               
              </p>
            </a>
          </li>
        <?php 
        }
          
        ?>   
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
