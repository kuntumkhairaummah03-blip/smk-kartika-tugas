<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$id_siswa = $_SESSION['id'];
$id = $_GET['id'];

$tugas = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM tugas
WHERE id='$id'
"));

if(isset($_POST['upload'])){

    $nama_file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];

    move_uploaded_file($tmp,"../uploads/".$nama_file);

    mysqli_query($conn,"
    INSERT INTO pengumpulan
    (id_tugas,id_siswa,file_tugas,tanggal_upload)
    VALUES
    ('$id','$id_siswa','$nama_file',NOW())
    ");

    echo "<script>
    alert('Tugas berhasil dikumpulkan');
    window.location='tugas.php';
    </script>";
}
?>

<!DOCTYPE html>

<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Detail Tugas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    font-family:Poppins,sans-serif;
    background:linear-gradient(135deg,#dcfce7,#f0fdf4,#ffffff);
    min-height:100vh;
    padding:25px;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}


.btn-top{
    text-decoration:none;
    color:white;
    padding:10px 18px;
    border-radius:12px;
    font-weight:600;
}

.btn-dashboard{
    background:#16a34a;
}

.btn-dashboard:hover{
    background:#15803d;
    color:white;
}

.btn-logout{
    background:#ef4444;
}

.btn-logout:hover{
    background:#dc2626;
    color:white;
}

.box{
    background:white;
    padding:35px;
    border-radius:25px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.judul{
    color:#15803d;
    font-weight:700;
    margin-bottom:15px;
}

.deadline{
    background:#f0fdf4;
    padding:12px 15px;
    border-radius:10px;
    margin-top:15px;
}

.btn-green{
    background:#22c55e;
    color:white;
    border:none;
    padding:12px 25px;
    border-radius:12px;
    font-weight:600;
}

.btn-green:hover{
    background:#16a34a;
}

@media(max-width:768px){

    .topbar{
        flex-direction:column;
        gap:10px;
    }

    .btn-top{
        width:100%;
        text-align:center;
    }
}

</style>

</head>

<body>

<div class="topbar">

<a href="dashboard.php" class="btn-top btn-dashboard">
    <i class="fas fa-home"></i> Dashboard
</a>

<a href="../auth/logout.php" class="btn-top btn-logout">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>

</div>

<div class="box">

<h2 class="judul">
    <?= htmlspecialchars($tugas['judul_tugas']); ?>
</h2>

<p>
    <?= nl2br(htmlspecialchars($tugas['deskripsi'])); ?>
</p>

<div class="deadline">
    <b>📅 Deadline:</b>
    <?= date('d M Y H:i', strtotime($tugas['deadline'])); ?>
</div>

<hr>

<form method="POST" enctype="multipart/form-data">

<label class="mb-2">
    <b>Upload File Tugas</b>
</label>

<input
type="file"
name="file"
class="form-control mb-3"
required>

<button type="submit" name="upload" class="btn-green">
    <i class="fas fa-upload"></i> Upload Tugas
</button>

</form>

</div>

</body>
</html>
