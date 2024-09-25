<?php
if (isset($_POST['total_pembelian']) && isset($_POST['bayar'])) {
    $total_pembelian = (float)$_POST['total_pembelian'];
    $bayar = (float)$_POST['bayar'];
    $kembalian = $bayar - $total_pembelian;

    // Pastikan kembalian tidak negatif
    if ($kembalian < 0) {
        $kembalian = 0;
    }

    echo number_format($kembalian, 2); // Format angka desimal
}
?>
