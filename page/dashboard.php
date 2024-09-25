<?php
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "dbkasir"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query jumlah akun terdaftar dari tabel user
$sql_user = "SELECT COUNT(*) as total_users FROM user";
$result_user = $conn->query($sql_user);
$total_users = $result_user->fetch_assoc()['total_users'];

// Query jumlah member dari tabel pelanggan
$sql_pelanggan = "SELECT COUNT(*) as total_pelanggan FROM pelanggan";
$result_pelanggan = $conn->query($sql_pelanggan);
$total_pelanggan = $result_pelanggan->fetch_assoc()['total_pelanggan'];

// Query jumlah transaksi dari tabel penjualan
$sql_penjualan = "SELECT COUNT(*) as total_penjualan FROM penjualan";
$result_penjualan = $conn->query($sql_penjualan);
$total_penjualan = $result_penjualan->fetch_assoc()['total_penjualan'];

// Query jumlah produk dari tabel produk
$sql_produk = "SELECT COUNT(*) as total_produk FROM produk";
$result_produk = $conn->query($sql_produk);
$total_produk = $result_produk->fetch_assoc()['total_produk'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fa fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Akun Terdaftar</span>
                            <span class="info-box-number"><?php echo $total_users; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Member</span>
                            <span class="info-box-number"><?php echo $total_pelanggan; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Transaksi</span>
                            <span class="info-box-number"><?php echo $total_penjualan; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jenis Produk</span>
                            <span class="info-box-number"><?php echo $total_produk; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
