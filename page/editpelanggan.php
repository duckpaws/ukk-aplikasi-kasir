<?php
// Koneksi database
include 'config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data pelanggan
    $query = "SELECT * FROM pelanggan WHERE id_pelanggan = $id";
    $result = mysqli_query($koneksi, $query);
    $customer = mysqli_fetch_assoc($result);

    // Update data jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_pelanggan = $_POST['nama_pelanggan'];
        $alamat = $_POST['alamat'];
        $no_telepon = $_POST['no_telepon'];

        $updateQuery = "UPDATE pelanggan SET nama_pelanggan='$nama_pelanggan', alamat='$alamat', no_telepon='$no_telepon' WHERE id_pelanggan=$id";
        mysqli_query($koneksi, $updateQuery);

        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='?page=customer';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
        }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Pelanggan</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Pelanggan</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama_pelanggan">Nama Pelanggan</label>
                                    <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" value="<?php echo $customer['nama_pelanggan']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" name="alamat" id="alamat" value="<?php echo $customer['alamat']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_telepon">Nomor Telepon</label>
                                    <input type="text" class="form-control" name="no_telepon" id="no_telepon" value="<?php echo $customer['no_telepon']; ?>" required>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="?page=customer" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>

    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
