<?php
// Koneksi database
include '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus produk
    $query = "DELETE FROM produk WHERE id = $id";
    mysqli_query($koneksi, $query);

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='?page=product';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
