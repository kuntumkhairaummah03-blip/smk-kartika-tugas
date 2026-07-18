<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$id_siswa = $_SESSION['id'];
$id_tugas = $_GET['id'];

$tugas = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM tugas WHERE id='$id_tugas'")
);

$deadline_berakhir = strtotime($tugas['deadline']) < time();

$cek_pengumpulan = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM pengumpulan
        WHERE id_tugas='$id_tugas'
        AND id_siswa='$id_siswa'"
    )
);

$sudah_upload = $cek_pengumpulan > 0;

if(isset($_POST['upload'])){

    if($deadline_berakhir){
        echo "
        <script>
        alert('Maaf, deadline pengumpulan tugas telah berakhir!');
        window.location='tugas.php';
        </script>
        ";
        exit;
    }

    if($sudah_upload){
        echo "
        <script>
        alert('Anda sudah mengumpulkan tugas ini!');
        window.location='tugas.php';
        </script>
        ";
        exit;
    }

    $nama_file = time().'_'.$_FILES['file_tugas']['name'];
    $tmp = $_FILES['file_tugas']['tmp_name'];

    if(!is_dir("../uploads")){
        mkdir("../uploads");
    }

    move_uploaded_file($tmp,"../uploads/".$nama_file);

    $status_pengumpulan = "Tepat Waktu";

    mysqli_query($conn,"
    INSERT INTO pengumpulan
    (
        id_tugas,
        id_siswa,
        file_tugas,
        tanggal_upload,
        status_pengumpulan
    )
    VALUES
    (
        '$id_tugas',
        '$id_siswa',
        '$nama_file',
        NOW(),
        '$status_pengumpulan'
    )
    ");

    echo "
    <script>
    alert('Tugas berhasil diupload');
    window.location='riwayat.php';
    </script>
    ";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Upload Tugas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#eefbf2;
font-family:Poppins,sans-serif;
}

.box{
max-width:700px;
margin:50px auto;
background:white;
padding:30px;
border-radius:20px;
box-shadow:0 10px 20px rgba(0,0,0,.08);
}

h3{
color:#15803d;
font-weight:700;
margin-bottom:15px;
}

.btn-upload{
background:#22c55e;
color:white;
border:none;
padding:10px 20px;
border-radius:10px;
}

.btn-upload:hover{
background:#16a34a;
color:white;
}

</style>

</head>
<body>

<div class="box">

<h3><?= htmlspecialchars($tugas['judul']); ?></h3>

<p><?= htmlspecialchars($tugas['deskripsi']); ?></p>

<p>
<b>Deadline:</b>

<?= date('d M Y H:i', strtotime($tugas['deadline'])); ?>

<?php if($deadline_berakhir){ ?>
<span class="badge bg-danger ms-2">Ditutup</span>
<?php } else { ?>
<span class="badge bg-success ms-2">Masih Dibuka</span>
<?php } ?>
</p>

<?php

$sisa = strtotime($tugas['deadline'].' 23:59:59') - time();

if($sisa > 0){

    $hari = floor($sisa / 86400);
    $jam = floor(($sisa % 86400) / 3600);

    echo "
    <div class='alert alert-info'>
        ⏳ Sisa waktu: {$hari} hari {$jam} jam
    </div>
    ";
}

?>

<form method="POST" enctype="multipart/form-data">

<?php if($sudah_upload){ ?>

<div class="alert alert-warning">
<b>Tugas Sudah Dikumpulkan</b><br>
Anda sudah mengupload tugas ini.
</div>

<?php } elseif($deadline_berakhir){ ?>

<div class="alert alert-danger">
<b>Deadline Berakhir!</b><br>
Tugas ini sudah tidak dapat dikumpulkan lagi.
</div>

<?php } else { ?>

<div class="mb-3">
<label class="form-label">Pilih File Tugas</label>

<input
type="file"
name="file_tugas"
class="form-control"
required>

</div>

<button
type="submit"
name="upload"
class="btn-upload">
Upload Tugas
</button>

<?php } ?>

<a href="tugas.php" class="btn btn-secondary">
Kembali
</a>

</form>

</div>

</body>
</html>

