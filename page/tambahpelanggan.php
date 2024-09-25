<?php
ob_start(); // Start output buffering

$koneksi = mysqli_connect("localhost", "root", "", "dbkasir");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $status_member = $_POST['status_member'];

    // Generate kode_member berdasarkan logika CONCAT dari SQL
    $kode_member = strtoupper( // Untuk memastikan kode member huruf besar
        substr($nama_pelanggan, 0, 1) . // Huruf pertama nama
        (strpos($nama_pelanggan, ' ') !== false ? substr($nama_pelanggan, strpos($nama_pelanggan, ' ') + 1, 1) : '') . // Huruf pertama setelah spasi
        (strpos(substr($nama_pelanggan, strpos($nama_pelanggan, ' ') + 1), ' ') !== false ? substr($nama_pelanggan, strpos(substr($nama_pelanggan, strpos($nama_pelanggan, ' ') + 1), ' ') + 1, 1) : '') . // Huruf pertama setelah spasi kedua (jika ada)
        substr($no_telepon, -4) // 4 angka terakhir dari nomor telepon
    );

    // Query untuk memasukkan data ke tabel pelanggan
    $query = "INSERT INTO pelanggan (nama_pelanggan, alamat, no_telepon, status_member, kode_member) 
              VALUES ('$nama_pelanggan', '$alamat', '$no_telepon', '$status_member', '$kode_member')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='?page=customer';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Ensure output buffering ends here if needed
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Data Pelanggan</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Tambah Data Pelanggan</h3>
            </div>
            <form method="post">
              <div class="card-body">
                <div class="form-group">
                  <label for="nama_pelanggan">Nama Pelanggan</label>
                  <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" placeholder="Masukkan Nama Pelanggan" required>
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan Alamat"></textarea>
                </div>
                <div class="form-group">
                  <label for="no_telepon">Nomor Telepon</label>
                  <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="Masukkan Nomor Telepon">
                </div>
                <div class="form-group">
                  <label for="status_member">Status Member</label>
                  <select class="form-control" id="status_member" name="status_member">
                    <option value="reguler">Reguler</option>
                    <option value="member">Member</option>
                  </select>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="../plugins/jquery/jquery.min.js"></script>
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
