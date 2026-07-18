<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$data = mysqli_query($conn,"
SELECT
p.*,
u.nama,
t.judul
FROM pengumpulan p
JOIN users u ON p.id_siswa = u.id
JOIN tugas t ON p.id_tugas = t.id
ORDER BY p.id DESC
");

$total = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM pengumpulan"));

$sudah = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM pengumpulan
WHERE nilai IS NOT NULL
AND nilai <> ''
"));

$belum = $total - $sudah;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Penilaian Tugas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:linear-gradient(135deg,#eefbf2,#f7fff9);
}

/* SIDEBAR */

.sidebar{
position:fixed;
left:20px;
top:20px;
width:260px;
height:calc(100vh - 40px);
padding:30px;
background:white;
border-radius:30px;
box-shadow:0 10px 30px rgba(0,0,0,.08);
overflow-y:auto;
}

.logo{
text-align:center;
margin-bottom:40px;
}

.logo .icon{
font-size:60px;
}

.logo h2{
color:#15803d;
font-weight:700;
margin-top:10px;
}

.logo p{
color:#666;
font-size:14px;
}

.sidebar a{
display:block;
padding:14px 18px;
margin-bottom:10px;
text-decoration:none;
color:#15803d;
font-weight:600;
border-radius:15px;
transition:.3s;
}

.sidebar a:hover{
background:#22c55e;
color:white;
}

.sidebar a i{
margin-right:10px;
}

.active{
background:#22c55e;
color:white !important;
}

/* MAIN */

.main{
margin-left:300px;
padding:30px;
}

.top-card{
background:white;
padding:35px;
border-radius:30px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 10px 25px rgba(0,0,0,.05);
}

.avatar{
width:90px;
height:90px;
border-radius:50%;
background:#22c55e;
display:flex;
align-items:center;
justify-content:center;
font-size:40px;
color:white;
}

/* STATISTIK */

.row-card{
display:flex;
gap:20px;
margin-top:30px;
}

.stat{
flex:1;
background:white;
padding:25px;
border-radius:25px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
text-align:center;
color:#15803d;
}

.stat i{
font-size:35px;
margin-bottom:15px;
}

.stat h1{
font-size:42px;
font-weight:700;
}

/* BOX */

.box{
background:white;
padding:30px;
border-radius:30px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
margin-top:30px;
overflow-x:auto;
}

/* =========================
   TABEL PENILAIAN SISWA
========================= */

.table{
width:100%;
border-collapse:collapse;
table-layout:auto;
}

.table th{
color:#15803d;
font-weight:700;
font-size:14px;
padding:16px 12px;
white-space:nowrap;
border:none;
vertical-align:middle;
}

.table td{
padding:16px 12px;
font-size:14px;
vertical-align:middle;
}

.table tbody tr{
border-bottom:1px solid #edf2f7;
}

.table tbody tr:hover{
background:#f4fff7;
}

/* KOLOM NAMA */

.table td:nth-child(1){
white-space:nowrap;
font-weight:600;
width:140px;
}

/* KOLOM JUDUL */

.table th:nth-child(2),
.table td:nth-child(2){
width:auto;
}

/* FILE */

.table th:nth-child(3),
.table td:nth-child(3){
width:130px;
text-align:center;
}

/* NILAI */

.table th:nth-child(4),
.table td:nth-child(4){
width:90px;
text-align:center;
}

/* STATUS */

.table th:nth-child(5),
.table td:nth-child(5){
width:170px;
text-align:center;
}

.table th:nth-child(6){
width:150px;
text-align:center;
padding-left:0;
padding-right:0;
}

.table thead th:nth-child(6){
text-align:center !important;
position:relative;
left:110px;
}
/* BADGE NILAI */

.nilai{
background:#dcfce7;
color:#166534;
padding:10px 16px;
border-radius:14px;
font-weight:700;
font-size:14px;
display:inline-block;
min-width:60px;
text-align:center;
}

/* STATUS */

.status-ok{
background:#16a34a;
color:white;
padding:8px 14px;
border-radius:12px;
font-size:13px;
font-weight:600;
display:inline-block;
white-space:nowrap;
}

.status-no{
background:#facc15;
color:#222;
padding:8px 14px;
border-radius:12px;
font-size:13px;
font-weight:600;
display:inline-block;
white-space:nowrap;
}

/* DOWNLOAD */

.download{
color:#16a34a;
font-weight:600;
text-decoration:none;
white-space:nowrap;
}

.download:hover{
text-decoration:underline;
}

/* BUTTON NILAI */

.btn-nilai{
background:#16a34a;
color:white;
height:40px;
width:120px;
padding:0;
border-radius:12px;
text-decoration:none;
font-weight:600;
font-size:13px;
display:flex;
align-items:center;
justify-content:center;
margin:0 auto;
white-space:nowrap;
transition:.3s;
}

.btn-nilai:hover{
background:#15803d;
color:white;
}

/* RESPONSIVE */

@media(max-width:1000px){

.sidebar{
position:relative;
width:100%;
height:auto;
left:0;
top:0;
margin-bottom:20px;
}

.main{
margin-left:0;
}

.row-card{
flex-direction:column;
}

.table{
min-width:700px;
}


}

</style>

</head>
<body>

<div class="sidebar">

<div class="logo">
<div class="icon"><img src="../assets/css/images/logo_kartika.png" style="width:60px;height:auto;"></div>
<h2>SMK Kartika</h2>
<p>Sistem Pengumpulan Tugas</p>
</div>

<a href="dashboard.php">
<i class="fas fa-chart-line"></i> Dashboard
</a>

<a href="buat_tugas.php">
<i class="fas fa-plus-circle"></i> Buat Tugas
</a>

<a href="daftar_tugas.php">
<i class="fas fa-book"></i> Daftar Tugas
</a>

<a href="nilai.php" class="active">
<i class="fas fa-star"></i> Penilaian
</a>

<a href="profil.php">
<i class="fas fa-user"></i> Profil Guru
</a>

<a href="../auth/logout.php">
<i class="fas fa-sign-out-alt"></i> Logout
</a>

</div>

<div class="main">

<div class="top-card">

<div>
<h1>Penilaian Tugas</h1>
<p class="text-muted">
Kelola dan berikan nilai tugas siswa
</p>
</div>

<div class="avatar">
⭐
</div>

</div>

<div class="row-card">

<div class="stat">
<i class="fas fa-folder-open"></i>
<h1><?= $total ?></h1>
<p>Total Pengumpulan</p>
</div>

<div class="stat">
<i class="fas fa-check-circle"></i>
<h1><?= $sudah ?></h1>
<p>Sudah Dinilai</p>
</div>

<div class="stat">
<i class="fas fa-clock"></i>
<h1><?= $belum ?></h1>
<p>Belum Dinilai</p>
</div>

</div>

<div class="box">

<h4 class="mb-4">
<i class="fas fa-star text-success"></i>
Data Penilaian Siswa
</h4>

<table class="table table-hover align-middle">

<thead>
<tr>
<th>Nama Siswa</th>
<th>Judul Tugas</th>
<th>File</th>
<th>Nilai</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

<?php while($d=mysqli_fetch_assoc($data)){ ?>

<tr>

<td>
<div style="font-weight:700">
<?= $d['nama']; ?>
</div>
</td>

<td style="line-height:1.6;">
<?= $d['judul']; ?>
</td>

<td>
<a class="download"
href="../uploads/<?= $d['file_tugas']; ?>"
target="_blank">
<i class="fas fa-download"></i>
Download
</a>
</td>

<td>

<?php if($d['nilai']){ ?>

<span class="nilai">
<?= $d['nilai']; ?>
</span>

<?php } else { ?>

<span class="text-muted">
-
</span>

<?php } ?>

</td>

<td>

<?php if($d['nilai']){ ?>

<span class="status-ok">
Sudah Dinilai
</span>

<?php } else { ?>

<span class="status-no">
Belum Dinilai
</span>

<?php } ?>

</td>

<td>

<td>

<a href="edit_nilai.php?id=<?= $d['id']; ?>" class="btn-nilai">
    <i class="fas fa-pen"></i>
    <?= $d['nilai'] ? 'Edit Nilai' : 'Beri Nilai'; ?>
</a>

</td>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>