<?php
session_start();
include "config/koneksi.php";

// Redirect ke halaman login jika user belum login
if (!isset($_SESSION['user'])) {
  header('location: login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplikasi Kasir | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="index.php" class="nav-link">Home</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- User dropdown menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user mr-2"></i> <!-- Tambahkan class mr-2 di sini -->
            <span><?php echo $_SESSION['user']['nama']; ?></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a href="logout.php" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'logout' ? 'active' : ''; ?>">
              <i class="fas fa-power-off mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link">
        <img src="dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-medium">Aplikasi Kasir</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Dashboard -->
            <li class="nav-item menu-open">
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="index.php" class="nav-link <?php echo !isset($_GET['page']) ? 'active' : ''; ?>">
                    <i class="fas fa-home nav-icon"></i>
                    <p>Dashboard</p>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Customer -->
            <li class="nav-item">
              <a href="?page=customer" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'customer' ? 'active' : ''; ?>">
                <i class="fas fa-user nav-icon"></i>
                <p>Pelanggan</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="?page=transaction" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'transaction' ? 'active' : ''; ?>">
                <i class="fas fa-cash-register nav-icon"></i>
                <p>Transaksi</p>
              </a>
            </li>

            <!-- Product -->
            <li class="nav-item">
              <a href="?page=product" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'product' ? 'active' : ''; ?>">
                <i class="fas fa-box nav-icon"></i>
                <p>Stok Produk</p>
              </a>
            </li>

            <!-- Gallery -->
            <li class="nav-item">
              <a href="?page=sellings" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'sellings' ? 'active' : ''; ?>">
                <i class="fas fa-table nav-icon"></i>
                <p>Pembelian</p>
              </a>
            </li>

            <?php if ($_SESSION['user']['level'] === 'admin'): ?>
              <li class="nav-item">
                <a href="?page=registrasi" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'registrasi' ? 'active' : ''; ?>">
                  <i class="fas fa-id-card nav-icon"></i>
                  <p>Registrasi</p>
                </a>
              </li>
            <?php endif; ?>


          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Aplikasi Kasir</h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <?php
              // Filter input untuk mencegah LFI
              $page = isset($_GET['page']) ? basename($_GET['page']) : 'dashboard';
              $file = 'page/' . $page . '.php';

              // Cek apakah file yang diminta ada, jika tidak tampilkan halaman error
              if (file_exists($file)) {
                include $file;
              } else {
                echo "<p>Halaman tidak ditemukan.</p>";
              }
              ?>
            </div>
            <!-- /.d-flex -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>Copyright &copy; Z. S. Yasmin</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0
      </div>
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE -->
  <script src="dist/js/adminlte.js"></script>

  <!-- OPTIONAL SCRIPTS -->
  <script src="plugins/chart.js/Chart.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard3.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>