<?php
session_start();
include '../config/koneksi.php';

$nama  = "KUNTUM KHAIRA UMMAH";
$ttl   = "11 Maret 2005";
$nip   = "20050311202401";
$mapel = "Pemrograman Web & Basis Data";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profil Guru</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:linear-gradient(135deg,#eefbf2,#f7fff9);
}

/* ================= SIDEBAR ================= */

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

.active{
background:#22c55e !important;
color:white !important;
}

/* ================= MAIN ================= */

.main{
margin-left:300px;
padding:30px;
}

/* HEADER */

.top-card{
background:white;
padding:35px 40px;
border-radius:30px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 10px 25px rgba(0,0,0,.05);
}

.top-card h1{
font-size:52px;
font-weight:800;
margin-bottom:5px;
color:#1f2937;
}

.top-card p{
font-size:18px;
}

.avatar{
width:120px;
height:120px;
border-radius:50%;
background:linear-gradient(135deg,#22c55e,#15803d);
display:flex;
align-items:center;
justify-content:center;
font-size:55px;
color:white;
box-shadow:0 10px 25px rgba(34,197,94,.3);
}

/* ================= PROFILE ================= */

.profile-card{
margin-top:30px;
background:white;
border-radius:35px;
padding:45px;
box-shadow:0 15px 40px rgba(0,0,0,.08);
position:relative;
overflow:hidden;
}

.profile-card::before{
content:'';
position:absolute;
top:0;
left:0;
width:100%;
height:8px;
background:linear-gradient(90deg,#16a34a,#22c55e,#4ade80);
}

.profile-header{
display:flex;
align-items:center;
gap:30px;
margin-bottom:35px;
padding-bottom:30px;
border-bottom:1px solid #eef2f7;
}

.profile-icon{
width:120px;
height:120px;
border-radius:50%;
background:linear-gradient(135deg,#22c55e,#15803d);
display:flex;
align-items:center;
justify-content:center;
font-size:55px;
color:white;
box-shadow:0 15px 30px rgba(34,197,94,.25);
}

.profile-title h2{
margin:0;
font-size:38px;
font-weight:800;
color:#15803d;
}

.profile-title p{
margin:8px 0;
font-size:16px;
color:#666;
}

.badge-guru{
display:inline-block;
background:#dcfce7;
color:#15803d;
padding:8px 18px;
border-radius:50px;
font-size:13px;
font-weight:700;
}

/* ================= INFO ================= */

.info{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:25px;
}

.info-box{
background:#f8fffa;
border:1px solid #dff5e6;
padding:28px;
border-radius:25px;
transition:.3s;
}

.info-box:hover{
transform:translateY(-5px);
box-shadow:0 12px 25px rgba(0,0,0,.06);
}

.info-box i{
font-size:30px;
color:#16a34a;
margin-bottom:15px;
}

.info-box h4{
margin:0 0 8px;
font-size:14px;
color:#666;
font-weight:500;
}

.info-box h2{
margin:0;
font-size:26px;
font-weight:700;
color:#15803d;
}

/* ================= STATS ================= */

.stats{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
margin-top:25px;
}

.stat-box{
background:linear-gradient(135deg,#22c55e,#15803d);
color:white;
padding:25px;
border-radius:22px;
text-align:center;
}

.stat-box h2{
font-size:38px;
font-weight:800;
margin-bottom:5px;
}

.stat-box p{
margin:0;
font-size:14px;
}

/* ================= RESPONSIVE ================= */

@media(max-width:768px){

.main{
margin-left:0;
}

.sidebar{
position:relative;
width:100%;
height:auto;
left:0;
top:0;
margin-bottom:20px;
}

.top-card{
flex-direction:column;
gap:20px;
text-align:center;
}

.profile-header{
flex-direction:column;
text-align:center;
}

.info{
grid-template-columns:1fr;
}

.stats{
grid-template-columns:1fr;
}

.top-card h1{
font-size:38px;
}

.profile-title h2{
font-size:28px;
}

.info-box h2{
font-size:20px;
}

}
</style>

</head>

<body>

<!-- SIDEBAR (DISAMAKAN) -->
<div class="sidebar">

<div class="logo">
<div class="icon"><img src="../assets/css/images/logo_kartika.png" style="width:60px;height:auto;"></div>
<h2>SMK Kartika</h2>
<p>Sistem Pengumpulan Tugas</p>
</div>

<a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
<a href="buat_tugas.php"><i class="fas fa-plus-circle"></i> Buat Tugas</a>
<a href="daftar_tugas.php"><i class="fas fa-book"></i> Daftar Tugas</a>
<a href="nilai.php"><i class="fas fa-star"></i> Penilaian</a>

<a href="profil.php" class="active">
<i class="fas fa-user"></i> Profil Guru
</a>

<a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

</div>

<!-- MAIN -->
<div class="main">

<div class="top-card">
<div>
<h1>Profil Guru</h1>
<p class="text-muted">Data akun guru</p>
</div>

<div class="avatar">👩‍🏫</div>
</div>

<div class="profile-card">

<div class="profile-header">
<div class="profile-icon">👩‍🏫</div>
<div class="profile-title">
<h2><?= $nama ?></h2>
</div>
</div>

<div class="info">

<div class="info-box">
<i class="fas fa-id-card"></i>
<h4>NIP</h4>
<h2><?= $nip ?></h2>
</div>

<div class="info-box">
<i class="fas fa-book"></i>
<h4>Mata Pelajaran</h4>
<h2><?= $mapel ?></h2>
</div>

<div class="info-box">
<i class="fas fa-user-check"></i>
<h4>Status</h4>
<h2>Guru Aktif</h2>
</div>

<div class="info-box">
<i class="fas fa-calendar"></i>
<h4>Tanggal Lahir</h4>
<h2><?= $ttl ?></h2>
</div>

</div>

</div>

</div>

</body>
</html>