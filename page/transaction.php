<?php
// Memulai sesi jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/koneksi.php';

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Menghapus produk dari keranjang
if (isset($_POST['delete_item'])) {
    $index = $_POST['delete_item']; // Mendapatkan indeks produk yang akan dihapus
    unset($_SESSION['keranjang'][$index]); // Menghapus produk dari keranjang
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // Mengurutkan ulang indeks array
}

// Mengambil data pelanggan untuk search (Select2)
$query_pelanggan = "SELECT id_pelanggan, nama_pelanggan FROM pelanggan";
$result_pelanggan = mysqli_query($koneksi, $query_pelanggan);

// Menyimpan pelanggan dalam sesi
if (isset($_POST['pelanggan']) && !empty($_POST['pelanggan'])) {
    $_SESSION['pelanggan'] = $_POST['pelanggan']; // Simpan pelanggan ke sesi
}

// Inisialisasi variabel hasil pencarian
$result_produk = null;

// Pencarian produk
if (isset($_POST['cari_produk']) && !empty($_POST['keyword'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']); // Menghindari SQL Injection
    $query_produk = "SELECT * FROM produk WHERE barcode LIKE '%$keyword%' OR nama_produk LIKE '%$keyword%'";
    $result_produk = mysqli_query($koneksi, $query_produk);

    if (!$result_produk) {
        echo "<script>alert('Query error: " . mysqli_error($koneksi) . "');</script>";
    }

    if (mysqli_num_rows($result_produk) == 0) {
        echo "<script>alert('Produk tidak ditemukan');</script>";
    }
}

// Menambahkan produk ke keranjang
if (isset($_POST['add_to_cart'])) {
    $id_produk = $_POST['id_produk'] ?? null;
    $jumlah = $_POST['jumlah'] ?? 1;

    if ($id_produk) {
        $query_produk = "SELECT * FROM produk WHERE id = $id_produk";
        $result_produk_single = mysqli_query($koneksi, $query_produk);

        if ($result_produk_single && mysqli_num_rows($result_produk_single) > 0) {
            $produk = mysqli_fetch_assoc($result_produk_single);
            if ($produk['stok'] > 0) {
                if ($produk['stok'] >= $jumlah) {
                    $subtotal = $produk['harga'] * $jumlah;
                    $_SESSION['keranjang'][] = [
                        'id_produk' => $produk['id'],
                        'nama_produk' => $produk['nama_produk'],
                        'barcode' => $produk['barcode'], // Menyimpan barcode ke keranjang
                        'harga' => $produk['harga'],
                        'jumlah' => $jumlah,
                        'subtotal' => $subtotal
                    ];

                    // Kurangi stok produk di database
                    $stok_baru = $produk['stok'] - $jumlah;
                    $query_update_stok = "UPDATE produk SET stok = $stok_baru, terjual = terjual + $jumlah WHERE id = $id_produk";
                    mysqli_query($koneksi, $query_update_stok);

                    $result_produk = null;
                } else {
                    echo "<script>alert('Stok tidak cukup untuk produk ini.');</script>";
                }
            } else {
                echo "<script>alert('Stok untuk produk ini habis.');</script>";
            }
        } else {
            echo "<script>alert('Produk tidak ditemukan.');</script>";
        }
    } else {
        echo "<script>alert('ID Produk tidak valid.');</script>";
    }
}

// Menghitung total pembelian
$total_pembelian = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $total_pembelian += $item['subtotal'];
}

// Inisialisasi variabel kembalian
$kembalian = 0;

if (isset($_POST['selesaikan_transaksi']) && !empty($_SESSION['keranjang'])) {
    if (isset($_POST['bayar']) && $_POST['bayar'] !== '') {
        if (isset($_SESSION['pelanggan']) && !empty($_SESSION['pelanggan'])) {
            $id_pelanggan = $_SESSION['pelanggan'];

            // Ambil status member dari pelanggan untuk menentukan apakah pelanggan umum atau member
            $query_kategori = "SELECT status_member FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'";
            $result_kategori = mysqli_query($koneksi, $query_kategori);
            $pelanggan = mysqli_fetch_assoc($result_kategori);
            $status_member = $pelanggan['status_member'];

            // Jika pelanggan berstatus member, terapkan diskon 10%
            if ($status_member === 'member') {
                $total_pembelian = $total_pembelian * 0.9; // Potongan 10%
            }

            $jumlah_dibayar = $_POST['bayar'];
            $metode_pembayaran = $_POST['metode_pembayaran'];
            $tanggal_penjualan = date("Y-m-d H:i:s");

            $kode_transaksi = 'TRX-' . time();

            if ($jumlah_dibayar >= $total_pembelian) {
                $kembalian = $jumlah_dibayar - $total_pembelian;

                $query_penjualan = "INSERT INTO penjualan (kode_transaksi, id_pelanggan, total_pembelian, jumlah_dibayar, metode_pembayaran, tanggal_penjualan) 
                                    VALUES ('$kode_transaksi', '$id_pelanggan', '$total_pembelian', '$jumlah_dibayar', '$metode_pembayaran', '$tanggal_penjualan')";
                
                if (mysqli_query($koneksi, $query_penjualan)) {
                    $id_penjualan = mysqli_insert_id($koneksi);
                
                    foreach ($_SESSION['keranjang'] as $item) {
                        $query_detail = "INSERT INTO detailpenjualan (id_penjualan, nama_produk, jumlah_produk, subtotal, harga) 
                                         VALUES ('$id_penjualan', '{$item['nama_produk']}', '{$item['jumlah']}', '{$item['subtotal']}', '{$item['harga']}')";
                        mysqli_query($koneksi, $query_detail);
                    }

                    $_SESSION['keranjang'] = [];
                    echo "<script>window.location.href = '?page=sellings';</script>";
                    exit;
                } else {
                    echo "Error: " . mysqli_error($koneksi);
                }
            } else {
                echo "<script>alert('Jumlah bayar tidak cukup');</script>";
            }
        } else {
            echo "<script>alert('Pilih pelanggan terlebih dahulu.');</script>";
        }
    } else {
        echo "<script>alert('Masukkan jumlah bayar terlebih dahulu.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Form Kasir</title>

  <!-- CSS dependencies -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
    /* Custom Styles */
    body {
      background-color: #f8f9fa; /* Light background for a modern feel */
    }
    .card {
      border-radius: 10px; /* Rounded corners for the card */
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    }
    .card-header {
      background-color: #007bff; /* Primary color for the header */
      color: white;
      border-radius: 10px 10px 0 0; /* Rounded corners for the header */
    }
    .btn-primary {
      background-color: #0056b3; /* Darker primary button color */
      border: none;
    }
    .btn-success {
      background-color: #28a745; /* Green success button */
      border: none;
    }
    .btn-danger {
      background-color: #dc3545; /* Red danger button */
      border: none;
    }
    .table th, .table td {
      vertical-align: middle; /* Center-align the content */
    }
    #kembalian {
      font-weight: bold;
      color: #28a745; /* Green color for return message */
    }
  </style>

  <script>
    // Function to calculate change
    function hitungKembalian() {
        var totalPembelian = <?= $total_pembelian; ?>;
        var bayar = document.getElementById('bayar').value;
        var kembalian = bayar - totalPembelian;

        if (kembalian >= 0) {
            document.getElementById('kembalian').innerHTML = "Kembalian: Rp " + new Intl.NumberFormat('id-ID').format(kembalian);
        } else {
            document.getElementById('kembalian').innerHTML = "Jumlah bayar tidak cukup";
        }
    }
  </script>
</head>
<body>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Transaksi Kasir</h3>
          </div>

          <!-- Form Pencarian Produk -->
          <form method="post">
            <div class="card-body">
              <!-- Search Pelanggan menggunakan Select2 -->
              <div class="form-group">
                <label for="search_pelanggan">Cari Pelanggan</label>
                <select class="form-control" id="search_pelanggan" name="pelanggan" required>
                    <option value="" disabled <?= !isset($_SESSION['pelanggan']) ? 'selected' : ''; ?>>Cari Pelanggan</option>
                    <?php while ($row = mysqli_fetch_assoc($result_pelanggan)): ?>
                        <option value="<?= $row['id_pelanggan']; ?>" <?= (isset($_SESSION['pelanggan']) && $_SESSION['pelanggan'] == $row['id_pelanggan']) ? 'selected' : ''; ?>>
                            <?= $row['nama_pelanggan']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
              </div>

              <!-- Form untuk Pencarian Produk -->
              <div class="form-group">
                <label for="search_product">Cari Produk</label>
                <input type="text" name="keyword" class="form-control" placeholder="Masukkan Barcode atau Nama Produk">
              </div>
              
              <button type="submit" name="cari_produk" class="btn btn-primary">Cari</button>
            </div>
          </form>

          <!-- Hasil Pencarian Produk -->
          <?php if ($result_produk): ?>
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = mysqli_fetch_assoc($result_produk)): ?>
                    <tr>
                      <td><?= $row['nama_produk']; ?></td>
                      <td><?= number_format($row['harga'], 0, ',', '.'); ?></td>
                      <td><?= $row['stok']; ?></td>
                      <td>
                        <form method="post">
                          <input type="hidden" name="id_produk" value="<?= $row['id']; ?>">
                          <input type="number" name="jumlah" value="1" min="1" max="<?= $row['stok']; ?>" class="form-control">
                      </td>
                      <td>
                          <button type="submit" name="add_to_cart" class="btn btn-success">Tambah ke Keranjang</button>
                        </form>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>

          <!-- Tampilkan Keranjang Belanja -->
          <div class="card-body">
            <h5>Keranjang Belanja:</h5>
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Barcode</th> <!-- Kolom baru untuk barcode -->
                  <th>Nama Produk</th>
                  <th>Harga</th>
                  <th>Jumlah</th>
                  <th>Subtotal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($_SESSION['keranjang'] as $index => $item): ?>
                  <tr>
                    <td><?= $item['barcode']; ?></td> <!-- Tampilkan barcode -->
                    <td><?= $item['nama_produk']; ?></td>
                    <td><?= number_format($item['harga'], 0, ',', '.'); ?></td>
                    <td><?= $item['jumlah']; ?></td>
                    <td><?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
                    <td>
                      <form method="post">
                        <button type="submit" name="delete_item" value="<?= $index; ?>" class="btn btn-danger">Hapus</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <h4>Total: Rp <?= number_format($total_pembelian, 0, ',', '.'); ?></h4>
          </div>

          <!-- Form Pembayaran -->
          <form method="post">
            <div class="card-body">
              <div class="form-group">
                <label for="bayar">Jumlah Bayar</label>
                <input type="number" name="bayar" id="bayar" class="form-control" required oninput="hitungKembalian()">
              </div>
              <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select name="metode_pembayaran" class="form-control">
                  <option value="cash">Cash</option>
                  <option value="transfer">Transfer</option>
                </select>
              </div>
              <p id="kembalian"></p>
              <button type="submit" name="selesaikan_transaksi" class="btn btn-success">Selesaikan Transaksi</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- JS dependencies -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
      $('#search_pelanggan').select2();  // Inisialisasi Select2 pada elemen select pelanggan
  });
</script>

</body>
</html>
