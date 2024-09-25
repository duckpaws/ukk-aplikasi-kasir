<?php
include '../config/koneksi.php';

if (isset($_GET['kode'])) {
    $kode_transaksi = $_GET['kode'];

    // Query to fetch sales data
    $query = "
        SELECT p.kode_transaksi, p.total_pembelian, p.jumlah_dibayar, 
               GROUP_CONCAT(dp.nama_produk SEPARATOR ', ') AS nama_produk, 
               GROUP_CONCAT(dp.jumlah_produk SEPARATOR ', ') AS jumlah_produk, 
               GROUP_CONCAT(dp.harga SEPARATOR ', ') AS harga_produk, 
               pelanggan.nama_pelanggan, p.tanggal_penjualan
        FROM penjualan p
        JOIN detailpenjualan dp ON p.id_penjualan = dp.id_penjualan
        JOIN pelanggan ON p.id_pelanggan = pelanggan.id_pelanggan
        WHERE p.kode_transaksi = '$kode_transaksi'
        GROUP BY p.kode_transaksi, p.total_pembelian, p.jumlah_dibayar, pelanggan.nama_pelanggan, p.tanggal_penjualan
    ";
    $result = mysqli_query($koneksi, $query);

    // Check if the query ran successfully
    if (!$result) {
        die("Query gagal dijalankan: " . mysqli_error($koneksi));
    }

    // Fetch sales data
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        die("Transaksi tidak ditemukan.");
    }

    // Calculate change (kembalian)
    $kembalian = $row['jumlah_dibayar'] - $row['total_pembelian'];
} else {
    die("Kode transaksi tidak diberikan.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .invoice-container {
            background-color: #fff;
            padding: 20px;
            max-width: 700px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-details p {
            margin: 5px 0;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        table td {
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .total, .paid, .kembalian {
            font-weight: bold;
            font-size: 18px;
        }
        .text-center {
            text-align: center;
        }
        .print-btn {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .print-btn button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .print-btn button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <h2 class="title">Invoice Penjualan</h2>
        
        <div class="invoice-details">
            <p>Tanggal: <strong><?php echo date('d-m-Y', strtotime($row['tanggal_penjualan'])); ?></strong></p>
            <p>Kode Transaksi: <strong><?php echo $row['kode_transaksi']; ?></strong></p>
            <p>Nama Pelanggan: <strong><?php echo $row['nama_pelanggan']; ?></strong></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-right">Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Split product, quantity, and price by commas
                $products = explode(', ', $row['nama_produk']);
                $quantities = explode(', ', $row['jumlah_produk']);
                $prices = explode(', ', $row['harga_produk']);

                for ($i = 0; $i < count($products); $i++) {
                    echo "<tr>";
                    echo "<td>{$products[$i]}</td>";
                    echo "<td class='text-center'>{$quantities[$i]}</td>";
                    echo "<td class='text-right'>Rp " . number_format($prices[$i], 2, ',', '.') . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="total">
            <p class="text-right">Total Pembelian: Rp <?php echo number_format($row['total_pembelian'], 2, ',', '.'); ?></p>
        </div>

        <div class="paid">
            <p class="text-right">Jumlah Dibayar: Rp <?php echo number_format($row['jumlah_dibayar'], 2, ',', '.'); ?></p>
        </div>

        <?php if ($kembalian > 0) { ?>
            <div class="kembalian">
                <p class="text-right">Kembalian: Rp <?php echo number_format($kembalian, 2, ',', '.'); ?></p>
            </div>
        <?php } else { ?>
            <div class="kembalian">
                <p class="text-right">Kembalian: Rp 0</p>
            </div>
        <?php } ?>

        <div class="print-btn">
            <button onclick="window.print()">Cetak</button>
        </div>
    </div>
</body>
</html>
