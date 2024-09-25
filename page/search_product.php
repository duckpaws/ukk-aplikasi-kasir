<?php
// Include koneksi database
include('config/koneksi.php');

// Cek apakah ada data yang dikirim melalui metode POST
if (isset($_POST['query'])) {
    $search_query = $_POST['query'];

    // Ubah kolom 'id' menjadi kolom yang benar, misalnya 'barcode'
    $query = "SELECT barcode, nama_produk, harga 
              FROM produk 
              WHERE barcode LIKE '%$search_query%' 
              OR nama_produk LIKE '%$search_query%' 
              LIMIT 5"; // Batasi hasil pencarian

    $result = mysqli_query($koneksi, $query);

    // Jika ada hasil, tampilkan sebagai suggestion
    if (mysqli_num_rows($result) > 0) {
        echo '<ul class="list-group">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li class="list-group-item pilih_produk" 
                      data-id="' . $row['barcode'] . '" 
                      data-nama="' . $row['nama_produk'] . '" 
                      data-harga="' . $row['harga'] . '">
                      ' . $row['barcode'] . ' - ' . $row['nama_produk'] . ' (Rp ' . $row['harga'] . ')
                  </li>';
        }
        echo '</ul>';
    } else {
        echo '<p class="text-danger">Produk tidak ditemukan</p>';
    }
}
?>
