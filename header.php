<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BsPOS | Sale</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
      <!-- User -->
      <ul class="navbar-nav">
        <li>
          <div class="text-primary"> <?= $_SESSION['user_name'] ?> </div>
        </li>
      </ul>
      <!-- Right navbar links -->
      <?php
      $uri = explode('.', $_SERVER['REQUEST_URI']);
      $url = $uri[0];
      ?>
      <ul class="navbar-nav ml-auto">
        <?php if ($url != "/product_add_update") : ?>
          <li class="nav-item">
            <div class="nav-link">
              <input type="text" name="search" class="from-control" placeholder="Search..." onkeyup="loadDataList(this.value)">
            </div>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="/home.php" class="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">BasicPOS</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Sale -- Home -->
            <?php if ($_SESSION['user_role'] != 3) : ?>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-cart-shopping"></i>
                  <p>
                    Sale
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Sale</a>
                  </li>
                </ul>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Sale Vouchers</a>
                  </li>
                </ul>
              </li>
            <?php endif; ?>

            <!-- Purchase -->
            <?php if ($_SESSION['user_role'] != 2) : ?>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-sack-dollar"></i>
                  <p>
                    Purchase
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Purchase</a>
                  </li>
                </ul>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Purchase Vouchers</a>
                  </li>
                </ul>
              </li>
            <?php endif ?>

            <!-- Inventory -->
            <li class="nav-item <?php if(str_contains($url, 'product')) {
                                  echo 'menu-open';
                                } ?>">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-boxes-stacked"></i>
                <p>
                  Inventory
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="/product.php" class="nav-link">Products List</a>
                </li>
              </ul>
              <?php if ($_SESSION['user_role'] == 1) : ?>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Products Adjust</a>
                  </li>
                </ul>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">SalePrice Change</a>
                  </li>
                </ul>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="/product_category.php" class="nav-link">Categories</a>
                  </li>
                </ul>
              <?php endif; ?>
            </li>

            <!-- Contact -->
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-address-book"></i>
                <p>
                  Contact
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <?php if ($_SESSION['user_role'] != 3) : ?>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Customer</a>
                  </li>
                </ul>
              <?php endif; ?>
              <?php if ($_SESSION['user_role'] != 2) : ?>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Supplier</a>
                  </li>
                </ul>
              <?php endif; ?>
            </li>

            <!-- income and expense -->
            <?php if ($_SESSION['user_role'] == 1) : ?>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-money-check-dollar"></i>
                  <p>Income & Expanse</p>
                </a>
              </li>
            <?php endif; ?>

            <!-- Reports -->
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-money-check"></i>
                <p>
                  Report
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <?php if ($_SESSION['user_role'] != 3) : ?>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Sale Reports</a>
                  </li>
                </ul>
              <?php endif; ?>
              <?php if ($_SESSION['user_role'] != 2) : ?>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Purchase Reports</a>
                  </li>
                </ul>
              <?php endif; ?>
              <?php if ($_SESSION['user_role'] == 1) : ?>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Inventory Reports</a>
                  </li>
                </ul>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">Financial Reports</a>
                  </li>
                </ul>
              <?php endif; ?>
            </li>

            <!-- User -->
            <?php if ($_SESSION['user_role'] == 1) : ?>
              <li class="nav-item">
                <a href="user.php" class="nav-link">
                  <i class="nav-icon fas fa-users"></i>
                  <p>User Control</p>
                </a>
              </li>
            <?php endif ?>

            <!-- exit -->
            <li class="nav-item">
              <a href="/_actions/logout.php" class="nav-link">
                <i class="nav-icon fas fa-sign-out"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>