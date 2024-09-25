<?php
// Koneksi database
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus pelanggan
    $query = "DELETE FROM pelanggan WHERE id_pelanggan = $id";
    mysqli_query($koneksi, $query);

    header('Location: ?page=daftarpelanggan'); // Redirect setelah delete
    exit();
}
?>
