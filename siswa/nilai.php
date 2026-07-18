<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$id_siswa = $_SESSION['id'];

$data = mysqli_query($conn,"
SELECT * FROM pengumpulan
WHERE id_siswa='$id_siswa'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Nilai Saya</title>

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


.topbar{
    display:flex;
    justify-content:flex-end;
    align-items:center;
    padding:25px 40px 10px;
}

.btn-dashboard{
    background:#16a34a;
    color:white !important;
    text-decoration:none;
    padding:12px 24px;
    border-radius:14px;
    font-weight:600;
    display:flex;
    align-items:center;
    gap:8px;
    box-shadow:0 8px 20px rgba(22,163,74,.25);
    transition:.3s;
}

.btn-dashboard:hover{
    background:#15803d;
    transform:translateY(-2px);
}

.btn-top{
padding:10px 14px;
border-radius:12px;
text-decoration:none;
font-size:14px;
color:white;
box-shadow:0 10px 20px rgba(0,0,0,.15);
}

.btn-back{
background:#15803d;
}

.btn-logout{
background:#dc2626;
}


.main{
margin:40px;
}

.header{
    margin:15px 40px 30px;
    padding:40px;
    border-radius:28px;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:white;
    position:relative;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(34,197,94,.25);
}

.header::before{
    content:'';
    position:absolute;
    width:220px;
    height:220px;
    border-radius:50%;
    background:rgba(255,255,255,.12);
    top:-90px;
    right:-90px;
}

.header h2{
    font-size:46px;
    font-weight:700;
    margin-bottom:10px;
}

.header p{
    font-size:18px;
    opacity:.95;
}

.header .icon{
    position:absolute;
    right:40px;
    top:50%;
    transform:translateY(-50%);
    font-size:65px;
    color:rgba(255,255,255,.9);
}

.icon{
font-size:45px;
color:#22c55e;
}


.grid{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
margin-top:25px;
}


.card-task{
background:white;
padding:25px;
border-radius:25px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
transition:.3s;
}

.card-task:hover{
transform:translateY(-8px);
}

.title{
font-weight:600;
color:#15803d;
font-size:18px;
}

.sub{
font-size:13px;
color:gray;
margin-top:5px;
}


.nilai{
margin-top:15px;
font-size:32px;
font-weight:700;
color:#22c55e;
}

.badge{
display:inline-block;
margin-top:10px;
padding:5px 10px;
border-radius:10px;
font-size:12px;
}

.ok{background:#dcfce7;color:#15803d;}
.wait{background:#fee2e2;color:#dc2626;}

@media(max-width:900px){
.grid{
grid-template-columns:1fr;
}
}

</style>

</head>

<body>


<div class="topbar">

    <a href="dashboard.php" class="btn-dashboard">
        <i class="fas fa-home"></i> Dashboard
    </a>

</div>

</div>

<div class="main">


<div class="header">

    <div>
        <h2>Nilai Saya</h2>
        <p>Hasil penilaian tugas kamu</p>
    </div>

    <div class="icon">
        <i class="fas fa-star"></i>
    </div>

</div>

<div class="grid">

<?php if(mysqli_num_rows($data) == 0){ ?>
<p style="margin-top:20px;color:gray;">Belum ada nilai</p>
<?php } ?>

<?php while($d=mysqli_fetch_assoc($data)){ ?>

<div class="card-task">

<div class="title">
<?= "Tugas: ".$d['id_tugas']; ?>
</div>

<div class="sub">
📅 <?= $d['tanggal_upload']; ?>
</div>

<?php if($d['nilai'] != NULL){ ?>

<div class="nilai">
<?= $d['nilai']; ?>
</div>

<span class="badge ok">Sudah Dinilai</span>

<?php } else { ?>

<div class="nilai" style="color:#dc2626;font-size:18px;">
Belum Dinilai
</div>

<span class="badge wait">Menunggu Guru</span>

<?php } ?>

</div>

<?php } ?>

</div>

</div>

</body>
</html>