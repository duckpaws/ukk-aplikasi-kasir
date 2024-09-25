<?php

// Query untuk mengambil data dari tabel produk
$query = "SELECT id, barcode, nama_produk, satuan, harga, stok, terjual FROM produk";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Deskripsi halaman">
    <meta name="author" content="Nama Anda">
    <title>Daftar Produk</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <style>
        .content {
            padding: 20px;
        }

        .card {
            margin-top: 20px;
        }

        .search-input {
            margin-bottom: 10px;
            max-width: 300px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Search Feature placed above the card -->
                        <div class="input-group search-input">
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari pelanggan...">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i> <!-- Icon Search -->
                                </span>
                            </div>
                        </div>

                        <!-- Card -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Produk</h3>
                                <a href="?page=tambahproduk" class="btn btn-primary btn-sm float-right">Tambah Data</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered table-striped" id="productTable">
                                    <thead>
                                        <tr>
                                            <th>Barcode</th>
                                            <th>Nama Produk</th>
                                            <th>Satuan</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th style="width: 150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Mengecek jika ada data
                                        if (mysqli_num_rows($result) > 0) {
                                            // Looping data dari database
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";
                                                echo "<td>" . $row['barcode'] . "</td>";
                                                echo "<td>" . $row['nama_produk'] . "</td>";
                                                echo "<td>" . $row['satuan'] . "</td>";
                                                echo "<td>Rp " . number_format($row['harga'], 2, ',', '.') . "</td>";
                                                echo "<td>" . $row['stok'] . "</td>";
                                                // Action Buttons
                                                echo "<td>
                                                    <a href='?page=editproduk&id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                                    <a href='?page=deleteproduk&id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this item?\");'>Delete</a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>Tidak ada data produk</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/jszip/jszip.min.js"></script>
    <script src="../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>

    <script>
        // JavaScript untuk fitur pencarian
        document.getElementById("searchInput").addEventListener("keyup", function () {
            var input = this.value.toLowerCase();
            var rows = document.querySelectorAll("#productTable tbody tr");
            rows.forEach(function (row) {
                var productName = row.querySelector("td:nth-child(2)").textContent.toLowerCase(); // Nama Produk di kolom kedua
                row.style.display = productName.includes(input) ? "" : "none";
            });
        });
    </script>
</body>

</html>
