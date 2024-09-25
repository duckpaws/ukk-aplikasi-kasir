<?php
include 'config/koneksi.php';

// Query to get sales data
$query = "
    SELECT p.kode_transaksi, p.total_pembelian, p.jumlah_dibayar, 
           dp.nama_produk, dp.jumlah_produk, dp.harga, 
           pelanggan.nama_pelanggan, p.tanggal_penjualan
    FROM penjualan p
    JOIN detailpenjualan dp ON p.id_penjualan = dp.id_penjualan
    JOIN pelanggan ON p.id_pelanggan = pelanggan.id_pelanggan
    ORDER BY p.kode_transaksi, dp.nama_produk
";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query gagal dijalankan: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penjualan</title>
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <style>
        .content {
            padding: 20px;
        }
        .card {
            margin-top: 3px;
        }
        td, th {
            vertical-align: middle;
        }
        .center-bold {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Penjualan</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal Transaksi</th>
                                            <th>Kode Transaksi</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah Produk</th>
                                            <th>Harga Produk</th>
                                            <th>Total Harga</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($result) > 0) {
                                            $currentKodeTransaksi = null;
                                            $rowSpan = 0;

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                if ($currentKodeTransaksi !== $row['kode_transaksi']) {
                                                    // Calculate rowspan (number of products)
                                                    $kodeTransaksi = $row['kode_transaksi'];
                                                    $rowSpanQuery = "
                                                        SELECT COUNT(*) as total_rows
                                                        FROM detailpenjualan dp
                                                        JOIN penjualan p ON dp.id_penjualan = p.id_penjualan
                                                        WHERE p.kode_transaksi = '$kodeTransaksi'
                                                    ";
                                                    $rowSpanResult = mysqli_query($koneksi, $rowSpanQuery);
                                                    $rowSpanData = mysqli_fetch_assoc($rowSpanResult);
                                                    $rowSpan = $rowSpanData['total_rows'];

                                                    // Display the first row for the new transaction
                                                    echo "<tr>";
                                                    echo "<td rowspan='{$rowSpan}'>" . date('d/m/Y', strtotime($row['tanggal_penjualan'])) . "</td>";
                                                    echo "<td rowspan='{$rowSpan}'>" . $row['kode_transaksi'] . "</td>";
                                                    echo "<td rowspan='{$rowSpan}'>" . $row['nama_pelanggan'] . "</td>";
                                                    echo "<td>" . $row['nama_produk'] . "</td>";
                                                    echo "<td>" . $row['jumlah_produk'] . "</td>";
                                                    echo "<td>Rp " . number_format($row['harga'], 2, ',', '.') . "</td>";
                                                    echo "<td rowspan='{$rowSpan}' class='center-bold'>Rp " . number_format($row['total_pembelian'], 2, ',', '.') . "</td>";
                                                    echo "<td rowspan='{$rowSpan}'><button onclick='cetakTransaksi(\"" . $row['kode_transaksi'] . "\")' class='btn btn-info btn-sm'>Cetak</button></td>";
                                                    echo "</tr>";

                                                    $currentKodeTransaksi = $row['kode_transaksi'];
                                                } else {
                                                    // Output the additional product rows for the same transaction
                                                    echo "<tr>";
                                                    echo "<td>" . $row['nama_produk'] . "</td>";
                                                    echo "<td>" . $row['jumlah_produk'] . "</td>";
                                                    echo "<td>Rp " . number_format($row['harga'], 2, ',', '.') . "</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else {
                                            echo "<tr><td colspan='8'>Tidak ada data penjualan</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/adminlte.min.js"></script>

    <script>
        function cetakTransaksi(kode_transaksi) {
            const printWindow = window.open('page/print_penjualan.php?kode=' + kode_transaksi, '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    </script>
</body>
</html>
