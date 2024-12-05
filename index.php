<?php
$host      = "localhost";
$user      = "root";
$pass      = "";
$db        = "bengkod";

$koneksi   = mysqli_connect($host,$user,$pass,$db);
if(!$koneksi){//cek koneksi
    die("Tidak bisa terkoneksi ke database");
}

$sukses    = "";
$error     = "";

// Menambahkan Data (Simpan)
if(isset($_POST['simpan'])){
   $isi             = $_POST['isi'];
   $tgl_awal        = $_POST['tgl_awal'];
   $tgl_akhir       = $_POST['tgl_akhir'];

   if($isi && $tgl_awal && $tgl_akhir){
        // Menentukan status kegiatan berdasarkan tanggal
        $status = date('Y-m-d') < $tgl_awal ? 'Belum' : (date('Y-m-d') <= $tgl_akhir ? 'Proses' : 'Selesai');
        $sqli   = "INSERT INTO kegiatan (isi, tgl_awal, tgl_akhir, status) VALUES ('$isi', '$tgl_awal', '$tgl_akhir', '$status')";
        $q1     = mysqli_query($koneksi, $sqli);
        if($q1){
            $sukses = "Berhasil memasukkan data baru";
        }else{
            $error = "Gagal memasukkan data: " . mysqli_error($koneksi);
        }
   }else{
        $error = "Silahkan masukkan semua data";
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="mx-auto">
    <!-- Form Input Data -->
    <div class="card">
        <div class="card-header">Create / Edit Data</div>
        <div class="card-body">
            <?php if($error): ?>
                <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if($sukses): ?>
                <div class="alert alert-success" role="alert"><?php echo $sukses; ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="isi">Kegiatan</label>
                    <input type="text" class="form-control" name="isi" id="isi" placeholder="Masukkan kegiatan">
                </div>
                <div class="form-group">
                    <label for="tanggalawal">Tanggal Awal</label>
                    <input type="date" class="form-control" name="tgl_awal" id="tanggalawal">
                </div>
                <div class="form-group">
                    <label for="tanggalakhir">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="tgl_akhir" id="tanggalakhir">
                </div>
                <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
            </form>
        </div>
    </div>

    <!-- Menampilkan Data -->
    <div class="card">
        <div class="card-header text-white bg-secondary">Data Kegiatan</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kegiatan</th>
                        <th>Awal</th>
                        <th>Akhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql2 = "SELECT * FROM kegiatan ORDER BY id DESC";
                    $q2   = mysqli_query($koneksi, $sql2);
                    $urut = 1;
                    while($r2 = mysqli_fetch_array($q2)){
                        $id        = $r2['id'];
                        $isi       = $r2['isi'];
                        $tgl_awal  = $r2['tgl_awal'];
                        $tgl_akhir = $r2['tgl_akhir'];
                        $status    = $r2['status'];
                    ?>
                    <tr>
                        <td><?php echo $urut++; ?></td>
                        <td><?php echo $isi; ?></td>
                        <td><?php echo $tgl_awal; ?></td>
                        <td><?php echo $tgl_akhir; ?></td>
                        <td><?php echo $status; ?></td>
                        <td>
                            <a href="?edit=<?php echo $id; ?>" class="btn btn-warning btn-sm">Ubah</a>
                            <a href="?hapus=<?php echo $id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
